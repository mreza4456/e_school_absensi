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
            school_timezone TEXT;
            jadwal_masuk TIME;
            jadwal_masuk_selesai TIME;
            jadwal_pulang TIME;
            input_datetime TIMESTAMP WITH TIME ZONE;
            day_name TEXT;
            existing_special_absensi INT;
            existing_absensi INT;
        BEGIN
            -- Get school timezone
            SELECT timezone INTO school_timezone
            FROM sekolahs
            WHERE id = NEW.sekolah_id;

            -- Convert input datetime
            input_datetime := (NEW.tanggal || ' ' || NEW.waktu)::TIMESTAMP;

            -- Check for existing special attendance (Izin, Sakit, Alpa)
            SELECT COUNT(*) INTO existing_special_absensi
            FROM absensis
            WHERE uid = NEW.uid
            AND tanggal = input_datetime::DATE
            AND sekolah_id = NEW.sekolah_id
            AND keterangan IN ('Izin', 'Sakit', 'Alpa');

            IF existing_special_absensi > 0 THEN
                RAISE EXCEPTION 'Siswa sudah tercatat absensi khusus pada tanggal ini.';
            END IF;

            -- Check for existing special attendance type
            IF NEW.keterangan IN ('Izin', 'Sakit', 'Alpa') THEN
                SELECT COUNT(*) INTO existing_special_absensi
                FROM absensis
                WHERE uid = NEW.uid
                AND tanggal = input_datetime::DATE
                AND sekolah_id = NEW.sekolah_id
                AND keterangan = NEW.keterangan;

                IF existing_special_absensi > 0  THEN
                    RAISE EXCEPTION 'Absensi khusus sudah ada untuk tanggal ini.';
                END IF;
            END IF;

            -- Jika keterangan sudah diisi, skip penentuan otomatis
            IF NEW.keterangan IS NULL OR NEW.keterangan = '' THEN
                -- Get day name in Indonesian
                SELECT CASE EXTRACT(DOW FROM input_datetime)
                    WHEN 1 THEN 'Senin'
                    WHEN 2 THEN 'Selasa'
                    WHEN 3 THEN 'Rabu'
                    WHEN 4 THEN 'Kamis'
                    WHEN 5 THEN 'Jumat'
                    WHEN 6 THEN 'Sabtu'
                    WHEN 0 THEN 'Minggu'
                END INTO day_name;

                -- Get school schedule
                SELECT jam_masuk, jam_masuk_selesai, jam_pulang
                INTO jadwal_masuk, jadwal_masuk_selesai, jadwal_pulang
                FROM jadwal_harians
                WHERE sekolah_id = NEW.sekolah_id
                AND hari = day_name;

                -- Determine keterangan
                IF input_datetime::TIME >= jadwal_masuk AND input_datetime::TIME <= jadwal_masuk_selesai THEN
                    NEW.keterangan := 'Masuk';
                ELSIF input_datetime::TIME > jadwal_masuk_selesai AND input_datetime::TIME < jadwal_pulang THEN
                    NEW.keterangan := 'Terlambat';
                ELSIF input_datetime::TIME >= jadwal_pulang AND input_datetime::TIME <= (jadwal_pulang + INTERVAL '1 hour') THEN
                    NEW.keterangan := 'Pulang';
                ELSE
                    RAISE EXCEPTION 'Waktu absensi di luar jam sekolah.';
                END IF;
            END IF;

            -- Check for existing attendance
            SELECT COUNT(*) INTO existing_absensi
            FROM absensis
            WHERE uid = NEW.uid
            AND tanggal = input_datetime::DATE
            AND sekolah_id = NEW.sekolah_id
            AND (
                keterangan = NEW.keterangan
                OR (
                    NEW.keterangan IN ('Masuk', 'Terlambat')
                    AND keterangan IN ('Masuk', 'Terlambat')
                )
            );

            IF existing_absensi > 0 THEN
                RAISE EXCEPTION 'Absensi sudah ada untuk tanggal ini.';
            END IF;

            -- Update tanggal and waktu with converted values
            NEW.tanggal := input_datetime::DATE;
            NEW.waktu := input_datetime::TIME;

            RETURN NEW;
        END;
        $$ LANGUAGE plpgsql;

        CREATE TRIGGER check_absensi_before_insert
        BEFORE INSERT ON absensis
        FOR EACH ROW EXECUTE FUNCTION check_absensi_before_insert();
        ";
    }
};
