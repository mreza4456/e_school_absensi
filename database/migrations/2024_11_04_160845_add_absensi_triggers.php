<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $this->down();

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::unprepared($this->mysqlTrigger());
        } elseif ($driver === 'pgsql') {
            DB::unprepared($this->pgsqlTrigger());
        }
    }

    public function down()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS check_absensi_before_insert');
        } elseif ($driver === 'pgsql') {
            DB::unprepared('
                DROP TRIGGER IF EXISTS check_absensi_before_insert ON absensis;
                DROP FUNCTION IF EXISTS check_absensi_before_insert() CASCADE;
            ');
        }
    }

    private function mysqlTrigger()
    {
        return "
        CREATE TRIGGER check_absensi_before_insert
        BEFORE INSERT ON absensis
        FOR EACH ROW
        BEGIN
            DECLARE school_timezone VARCHAR(50);
            DECLARE jadwal_masuk TIME;
            DECLARE jadwal_masuk_selesai TIME;
            DECLARE jadwal_pulang TIME;
            DECLARE input_datetime DATETIME;
            DECLARE day_name VARCHAR(20);
            DECLARE existing_special_absensi INT;
            DECLARE existing_absensi INT;

            -- Get school timezone
            SELECT timezone INTO school_timezone
            FROM sekolahs
            WHERE id = NEW.sekolah_id;

            -- Convert timezone
            SET input_datetime = CONVERT_TZ(CONCAT(NEW.tanggal, ' ', NEW.waktu), '+00:00',
                CASE
                    WHEN school_timezone = 'WIB' THEN '+07:00'
                    WHEN school_timezone = 'WITA' THEN '+08:00'
                    WHEN school_timezone = 'WIT' THEN '+09:00'
                    ELSE '+07:00'
                END);

            -- Check for existing special attendance (Izin, Sakit, Alpa)
            SELECT COUNT(*) INTO existing_special_absensi
            FROM absensis
            WHERE uid = NEW.uid
            AND tanggal = DATE(input_datetime)
            AND sekolah_id = NEW.sekolah_id
            AND keterangan IN ('Izin', 'Sakit', 'Alpa');

            IF existing_special_absensi > 0 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Siswa sudah tercatat absensi khusus pada tanggal ini.';
            END IF;

            -- Check for existing special attendance type
            IF NEW.keterangan IN ('Izin', 'Sakit', 'Alpa') THEN
                SELECT COUNT(*) INTO existing_special_absensi
                FROM absensis
                WHERE uid = NEW.uid
                AND tanggal = DATE(input_datetime)
                AND sekolah_id = NEW.sekolah_id
                AND keterangan = NEW.keterangan;

                IF existing_special_absensi > 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Absensi khusus sudah ada untuk tanggal ini.';
                END IF;
            END IF;

            -- Jika keterangan sudah diisi, skip penentuan otomatis
            IF NEW.keterangan IS NULL OR NEW.keterangan = '' THEN
                -- Get day name in Indonesian
                SET day_name = ELT(WEEKDAY(input_datetime) + 2,
                    'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');

                -- Get school schedule
                SELECT jam_masuk, jam_masuk_selesai, jam_pulang
                INTO jadwal_masuk, jadwal_masuk_selesai, jadwal_pulang
                FROM jadwal_harians
                WHERE sekolah_id = NEW.sekolah_id
                AND hari = day_name;

                -- Determine keterangan
                IF TIME(input_datetime) BETWEEN jadwal_masuk AND jadwal_masuk_selesai THEN
                    SET NEW.keterangan = 'Masuk';
                ELSEIF TIME(input_datetime) > jadwal_masuk_selesai AND TIME(input_datetime) < jadwal_pulang THEN
                    SET NEW.keterangan = 'Terlambat';
                ELSEIF TIME(input_datetime) BETWEEN jadwal_pulang AND ADDTIME(jadwal_pulang, '01:00:00') THEN
                    SET NEW.keterangan = 'Pulang';
                ELSE
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Waktu absensi di luar jam sekolah.';
                END IF;
            END IF;

            -- Check for existing attendance
            SELECT COUNT(*) INTO existing_absensi
            FROM absensis
            WHERE uid = NEW.uid
            AND tanggal = DATE(input_datetime)
            AND sekolah_id = NEW.sekolah_id
            AND (
                keterangan = NEW.keterangan
                OR (
                    NEW.keterangan IN ('Masuk', 'Terlambat')
                    AND keterangan IN ('Masuk', 'Terlambat')
                )
            );

            IF existing_absensi > 0 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Absensi sudah ada untuk tanggal ini.';
            END IF;

            -- Update tanggal and waktu with converted values
            SET NEW.tanggal = DATE(input_datetime);
            SET NEW.waktu = TIME(input_datetime);
        END;
        ";
    }

    private function pgsqlTrigger()
    {
        return "
            CREATE OR REPLACE FUNCTION check_absensi_before_insert()
            RETURNS TRIGGER AS $$
            DECLARE
                v_sekolah RECORD;
                v_jadwal RECORD;
                v_existing_special RECORD;
                v_existing_regular RECORD;
                v_timezone TEXT;
                v_day_name TEXT;
                v_input_time TIME;
            BEGIN
                -- 1. Cek sekolah_id dan dapatkan timezone serta jadwal
                SELECT * INTO v_sekolah
                FROM sekolahs
                WHERE id = NEW.sekolah_id;

                IF NOT FOUND THEN
                    RAISE EXCEPTION 'Sekolah dengan ID % tidak ditemukan', NEW.sekolah_id;
                END IF;

                -- Tentukan nama hari
                SELECT CASE EXTRACT(DOW FROM NEW.tanggal)
                    WHEN 1 THEN 'Senin'
                    WHEN 2 THEN 'Selasa'
                    WHEN 3 THEN 'Rabu'
                    WHEN 4 THEN 'Kamis'
                    WHEN 5 THEN 'Jumat'
                    WHEN 6 THEN 'Sabtu'
                    WHEN 0 THEN 'Minggu'
                END INTO v_day_name;

                -- Dapatkan jadwal untuk hari tersebut
                SELECT * INTO v_jadwal
                FROM jadwal_harians
                WHERE sekolah_id = NEW.sekolah_id
                AND hari = v_day_name;

                IF NOT FOUND THEN
                    RAISE EXCEPTION 'Jadwal tidak ditemukan untuk hari %', v_day_name;
                END IF;

                -- 2. Cek apakah sudah ada absensi khusus (Izin, Sakit, Alpa)
                SELECT * INTO v_existing_special
                FROM absensis
                WHERE siswa_id = NEW.siswa_id
                AND tanggal = NEW.tanggal::DATE
                AND sekolah_id = NEW.sekolah_id
                AND keterangan IN ('Izin', 'Sakit', 'Alpa')
                LIMIT 1;

                IF FOUND THEN
                    RAISE EXCEPTION 'Siswa sudah tercatat % pada tanggal ini', v_existing_special.keterangan;
                END IF;

                -- 3. Tentukan keterangan berdasarkan waktu
                v_input_time := NEW.waktu::TIME;

                IF NEW.keterangan IS NULL OR NEW.keterangan = '' THEN
                    IF v_input_time BETWEEN v_jadwal.jam_masuk AND v_jadwal.jam_masuk_selesai THEN
                        NEW.keterangan := 'Masuk';
                    ELSIF v_input_time > v_jadwal.jam_masuk_selesai AND v_input_time < v_jadwal.jam_pulang THEN
                        NEW.keterangan := 'Terlambat';
                    ELSIF v_input_time BETWEEN v_jadwal.jam_pulang AND (v_jadwal.jam_pulang + INTERVAL '1 hour') THEN
                        NEW.keterangan := 'Pulang';
                    ELSE
                        RAISE EXCEPTION 'Waktu absensi di luar jam sekolah';
                    END IF;
                END IF;

                -- 4. Cek duplikasi absensi reguler
                IF NEW.keterangan IN ('Masuk', 'Terlambat', 'Pulang') THEN
                    -- Untuk absensi Masuk atau Terlambat
                    IF NEW.keterangan IN ('Masuk', 'Terlambat') THEN
                        SELECT * INTO v_existing_regular
                        FROM absensis
                        WHERE siswa_id = NEW.siswa_id
                        AND tanggal = NEW.tanggal::DATE
                        AND sekolah_id = NEW.sekolah_id
                        AND keterangan IN ('Masuk', 'Terlambat')
                        LIMIT 1;

                        IF FOUND THEN
                            RAISE EXCEPTION 'Sudah ada absensi % pada tanggal ini', v_existing_regular.keterangan;
                        END IF;
                    END IF;

                    -- Untuk absensi Pulang
                    IF NEW.keterangan = 'Pulang' THEN
                        SELECT * INTO v_existing_regular
                        FROM absensis
                        WHERE siswa_id = NEW.siswa_id
                        AND tanggal = NEW.tanggal::DATE
                        AND sekolah_id = NEW.sekolah_id
                        AND keterangan = 'Pulang'
                        LIMIT 1;

                        IF FOUND THEN
                            RAISE EXCEPTION 'Sudah ada absensi pulang pada tanggal ini';
                        END IF;
                    END IF;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            -- Recreate trigger
            DROP TRIGGER IF EXISTS check_absensi_before_insert ON absensis;
            CREATE TRIGGER check_absensi_before_insert
                BEFORE INSERT ON absensis
                FOR EACH ROW
                EXECUTE FUNCTION check_absensi_before_insert();
        ";
    }
};
