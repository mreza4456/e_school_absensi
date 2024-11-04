<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regencies = [
            [
                "code" => 1101,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH SELATAN"
            ],
            [
                "code" => 1102,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH TENGGARA"
            ],
            [
                "code" => 1103,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH TIMUR"
            ],
            [
                "code" => 1104,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH TENGAH"
            ],
            [
                "code" => 1105,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH BARAT"
            ],
            [
                "code" => 1106,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH BESAR"
            ],
            [
                "code" => 1107,
                "provinsi_code" => 11,
                "nama" => "KAB. PIDIE"
            ],
            [
                "code" => 1108,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH UTARA"
            ],
            [
                "code" => 1109,
                "provinsi_code" => 11,
                "nama" => "KAB. SIMEULUE"
            ],
            [
                "code" => 1110,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH SINGKIL"
            ],
            [
                "code" => 1111,
                "provinsi_code" => 11,
                "nama" => "KAB. BIREUEN"
            ],
            [
                "code" => 1112,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH BARAT DAYA"
            ],
            [
                "code" => 1113,
                "provinsi_code" => 11,
                "nama" => "KAB. GAYO LUES"
            ],
            [
                "code" => 1114,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH JAYA"
            ],
            [
                "code" => 1115,
                "provinsi_code" => 11,
                "nama" => "KAB. NAGAN RAYA"
            ],
            [
                "code" => 1116,
                "provinsi_code" => 11,
                "nama" => "KAB. ACEH TAMIANG"
            ],
            [
                "code" => 1117,
                "provinsi_code" => 11,
                "nama" => "KAB. BENER MERIAH"
            ],
            [
                "code" => 1118,
                "provinsi_code" => 11,
                "nama" => "KAB. PIDIE JAYA"
            ],
            [
                "code" => 1171,
                "provinsi_code" => 11,
                "nama" => "KOTA BANDA ACEH"
            ],
            [
                "code" => 1172,
                "provinsi_code" => 11,
                "nama" => "KOTA SABANG"
            ],
            [
                "code" => 1173,
                "provinsi_code" => 11,
                "nama" => "KOTA LHOKSEUMAWE"
            ],
            [
                "code" => 1174,
                "provinsi_code" => 11,
                "nama" => "KOTA LANGSA"
            ],
            [
                "code" => 1175,
                "provinsi_code" => 11,
                "nama" => "KOTA SUBULUSSALAM"
            ],
            [
                "code" => 1201,
                "provinsi_code" => 12,
                "nama" => "KAB. TAPANULI TENGAH"
            ],
            [
                "code" => 1202,
                "provinsi_code" => 12,
                "nama" => "KAB. TAPANULI UTARA"
            ],
            [
                "code" => 1203,
                "provinsi_code" => 12,
                "nama" => "KAB. TAPANULI SELATAN"
            ],
            [
                "code" => 1204,
                "provinsi_code" => 12,
                "nama" => "KAB. NIAS"
            ],
            [
                "code" => 1205,
                "provinsi_code" => 12,
                "nama" => "KAB. LANGKAT"
            ],
            [
                "code" => 1206,
                "provinsi_code" => 12,
                "nama" => "KAB. KARO"
            ],
            [
                "code" => 1207,
                "provinsi_code" => 12,
                "nama" => "KAB. DELI SERDANG"
            ],
            [
                "code" => 1208,
                "provinsi_code" => 12,
                "nama" => "KAB. SIMALUNGUN"
            ],
            [
                "code" => 1209,
                "provinsi_code" => 12,
                "nama" => "KAB. ASAHAN"
            ],
            [
                "code" => 1210,
                "provinsi_code" => 12,
                "nama" => "KAB. LABUHANBATU"
            ],
            [
                "code" => 1211,
                "provinsi_code" => 12,
                "nama" => "KAB. DAIRI"
            ],
            [
                "code" => 1212,
                "provinsi_code" => 12,
                "nama" => "KAB. TOBA"
            ],
            [
                "code" => 1213,
                "provinsi_code" => 12,
                "nama" => "KAB. MANDAILING NATAL"
            ],
            [
                "code" => 1214,
                "provinsi_code" => 12,
                "nama" => "KAB. NIAS SELATAN"
            ],
            [
                "code" => 1215,
                "provinsi_code" => 12,
                "nama" => "KAB. PAKPAK BHARAT"
            ],
            [
                "code" => 1216,
                "provinsi_code" => 12,
                "nama" => "KAB. HUMBANG HASUNDUTAN"
            ],
            [
                "code" => 1217,
                "provinsi_code" => 12,
                "nama" => "KAB. SAMOSIR"
            ],
            [
                "code" => 1218,
                "provinsi_code" => 12,
                "nama" => "KAB. SERDANG BEDAGAI"
            ],
            [
                "code" => 1219,
                "provinsi_code" => 12,
                "nama" => "KAB. BATU BARA"
            ],
            [
                "code" => 1220,
                "provinsi_code" => 12,
                "nama" => "KAB. PADANG LAWAS UTARA"
            ],
            [
                "code" => 1221,
                "provinsi_code" => 12,
                "nama" => "KAB. PADANG LAWAS"
            ],
            [
                "code" => 1222,
                "provinsi_code" => 12,
                "nama" => "KAB. LABUHANBATU SELATAN"
            ],
            [
                "code" => 1223,
                "provinsi_code" => 12,
                "nama" => "KAB. LABUHANBATU UTARA"
            ],
            [
                "code" => 1224,
                "provinsi_code" => 12,
                "nama" => "KAB. NIAS UTARA"
            ],
            [
                "code" => 1225,
                "provinsi_code" => 12,
                "nama" => "KAB. NIAS BARAT"
            ],
            [
                "code" => 1271,
                "provinsi_code" => 12,
                "nama" => "KOTA MEDAN"
            ],
            [
                "code" => 1272,
                "provinsi_code" => 12,
                "nama" => "KOTA PEMATANGSIANTAR"
            ],
            [
                "code" => 1273,
                "provinsi_code" => 12,
                "nama" => "KOTA SIBOLGA"
            ],
            [
                "code" => 1274,
                "provinsi_code" => 12,
                "nama" => "KOTA TANJUNG BALAI"
            ],
            [
                "code" => 1275,
                "provinsi_code" => 12,
                "nama" => "KOTA BINJAI"
            ],
            [
                "code" => 1276,
                "provinsi_code" => 12,
                "nama" => "KOTA TEBING TINGGI"
            ],
            [
                "code" => 1277,
                "provinsi_code" => 12,
                "nama" => "KOTA PADANGSIDIMPUAN"
            ],
            [
                "code" => 1278,
                "provinsi_code" => 12,
                "nama" => "KOTA GUNUNGSITOLI"
            ],
            [
                "code" => 1301,
                "provinsi_code" => 13,
                "nama" => "KAB. PESISIR SELATAN"
            ],
            [
                "code" => 1302,
                "provinsi_code" => 13,
                "nama" => "KAB. SOLOK"
            ],
            [
                "code" => 1303,
                "provinsi_code" => 13,
                "nama" => "KAB. SIJUNJUNG"
            ],
            [
                "code" => 1304,
                "provinsi_code" => 13,
                "nama" => "KAB. TANAH DATAR"
            ],
            [
                "code" => 1305,
                "provinsi_code" => 13,
                "nama" => "KAB. PADANG PARIAMAN"
            ],
            [
                "code" => 1306,
                "provinsi_code" => 13,
                "nama" => "KAB. AGAM"
            ],
            [
                "code" => 1307,
                "provinsi_code" => 13,
                "nama" => "KAB. LIMA PULUH KOTA"
            ],
            [
                "code" => 1308,
                "provinsi_code" => 13,
                "nama" => "KAB. PASAMAN"
            ],
            [
                "code" => 1309,
                "provinsi_code" => 13,
                "nama" => "KAB. KEPULAUAN MENTAWAI"
            ],
            [
                "code" => 1310,
                "provinsi_code" => 13,
                "nama" => "KAB. DHARMASRAYA"
            ],
            [
                "code" => 1311,
                "provinsi_code" => 13,
                "nama" => "KAB. SOLOK SELATAN"
            ],
            [
                "code" => 1312,
                "provinsi_code" => 13,
                "nama" => "KAB. PASAMAN BARAT"
            ],
            [
                "code" => 1371,
                "provinsi_code" => 13,
                "nama" => "KOTA PADANG"
            ],
            [
                "code" => 1372,
                "provinsi_code" => 13,
                "nama" => "KOTA SOLOK"
            ],
            [
                "code" => 1373,
                "provinsi_code" => 13,
                "nama" => "KOTA SAWAHLUNTO"
            ],
            [
                "code" => 1374,
                "provinsi_code" => 13,
                "nama" => "KOTA PADANG PANJANG"
            ],
            [
                "code" => 1375,
                "provinsi_code" => 13,
                "nama" => "KOTA BUKITTINGGI"
            ],
            [
                "code" => 1376,
                "provinsi_code" => 13,
                "nama" => "KOTA PAYAKUMBUH"
            ],
            [
                "code" => 1377,
                "provinsi_code" => 13,
                "nama" => "KOTA PARIAMAN"
            ],
            [
                "code" => 1401,
                "provinsi_code" => 14,
                "nama" => "KAB. KAMPAR"
            ],
            [
                "code" => 1402,
                "provinsi_code" => 14,
                "nama" => "KAB. INDRAGIRI HULU"
            ],
            [
                "code" => 1403,
                "provinsi_code" => 14,
                "nama" => "KAB. BENGKALIS"
            ],
            [
                "code" => 1404,
                "provinsi_code" => 14,
                "nama" => "KAB. INDRAGIRI HILIR"
            ],
            [
                "code" => 1405,
                "provinsi_code" => 14,
                "nama" => "KAB. PELALAWAN"
            ],
            [
                "code" => 1406,
                "provinsi_code" => 14,
                "nama" => "KAB. ROKAN HULU"
            ],
            [
                "code" => 1407,
                "provinsi_code" => 14,
                "nama" => "KAB. ROKAN HILIR"
            ],
            [
                "code" => 1408,
                "provinsi_code" => 14,
                "nama" => "KAB. SIAK"
            ],
            [
                "code" => 1409,
                "provinsi_code" => 14,
                "nama" => "KAB. KUANTAN SINGINGI"
            ],
            [
                "code" => 1410,
                "provinsi_code" => 14,
                "nama" => "KAB. KEPULAUAN MERANTI"
            ],
            [
                "code" => 1471,
                "provinsi_code" => 14,
                "nama" => "KOTA PEKANBARU"
            ],
            [
                "code" => 1472,
                "provinsi_code" => 14,
                "nama" => "KOTA DUMAI"
            ],
            [
                "code" => 1501,
                "provinsi_code" => 15,
                "nama" => "KAB. KERINCI"
            ],
            [
                "code" => 1502,
                "provinsi_code" => 15,
                "nama" => "KAB. MERANGIN"
            ],
            [
                "code" => 1503,
                "provinsi_code" => 15,
                "nama" => "KAB. SAROLANGUN"
            ],
            [
                "code" => 1504,
                "provinsi_code" => 15,
                "nama" => "KAB. BATANGHARI"
            ],
            [
                "code" => 1505,
                "provinsi_code" => 15,
                "nama" => "KAB. MUARO JAMBI"
            ],
            [
                "code" => 1506,
                "provinsi_code" => 15,
                "nama" => "KAB. TANJUNG JABUNG BARAT"
            ],
            [
                "code" => 1507,
                "provinsi_code" => 15,
                "nama" => "KAB. TANJUNG JABUNG TIMUR"
            ],
            [
                "code" => 1508,
                "provinsi_code" => 15,
                "nama" => "KAB. BUNGO"
            ],
            [
                "code" => 1509,
                "provinsi_code" => 15,
                "nama" => "KAB. TEBO"
            ],
            [
                "code" => 1571,
                "provinsi_code" => 15,
                "nama" => "KOTA JAMBI"
            ],
            [
                "code" => 1572,
                "provinsi_code" => 15,
                "nama" => "KOTA SUNGAI PENUH"
            ],
            [
                "code" => 1601,
                "provinsi_code" => 16,
                "nama" => "KAB. OGAN KOMERING ULU"
            ],
            [
                "code" => 1602,
                "provinsi_code" => 16,
                "nama" => "KAB. OGAN KOMERING ILIR"
            ],
            [
                "code" => 1603,
                "provinsi_code" => 16,
                "nama" => "KAB. MUARA ENIM"
            ],
            [
                "code" => 1604,
                "provinsi_code" => 16,
                "nama" => "KAB. LAHAT"
            ],
            [
                "code" => 1605,
                "provinsi_code" => 16,
                "nama" => "KAB. MUSI RAWAS"
            ],
            [
                "code" => 1606,
                "provinsi_code" => 16,
                "nama" => "KAB. MUSI BANYUASIN"
            ],
            [
                "code" => 1607,
                "provinsi_code" => 16,
                "nama" => "KAB. BANYUASIN"
            ],
            [
                "code" => 1608,
                "provinsi_code" => 16,
                "nama" => "KAB. OGAN KOMERING ULU TIMUR"
            ],
            [
                "code" => 1609,
                "provinsi_code" => 16,
                "nama" => "KAB. OGAN KOMERING ULU SELATAN"
            ],
            [
                "code" => 1610,
                "provinsi_code" => 16,
                "nama" => "KAB. OGAN ILIR"
            ],
            [
                "code" => 1611,
                "provinsi_code" => 16,
                "nama" => "KAB. EMPAT LAWANG"
            ],
            [
                "code" => 1612,
                "provinsi_code" => 16,
                "nama" => "KAB. PENUKAL ABAB LEMATANG ILIR"
            ],
            [
                "code" => 1613,
                "provinsi_code" => 16,
                "nama" => "KAB. MUSI RAWAS UTARA"
            ],
            [
                "code" => 1671,
                "provinsi_code" => 16,
                "nama" => "KOTA PALEMBANG"
            ],
            [
                "code" => 1672,
                "provinsi_code" => 16,
                "nama" => "KOTA PAGAR ALAM"
            ],
            [
                "code" => 1673,
                "provinsi_code" => 16,
                "nama" => "KOTA LUBUK LINGGAU"
            ],
            [
                "code" => 1674,
                "provinsi_code" => 16,
                "nama" => "KOTA PRABUMULIH"
            ],
            [
                "code" => 1701,
                "provinsi_code" => 17,
                "nama" => "KAB. BENGKULU SELATAN"
            ],
            [
                "code" => 1702,
                "provinsi_code" => 17,
                "nama" => "KAB. REJANG LEBONG"
            ],
            [
                "code" => 1703,
                "provinsi_code" => 17,
                "nama" => "KAB. BENGKULU UTARA"
            ],
            [
                "code" => 1704,
                "provinsi_code" => 17,
                "nama" => "KAB. KAUR"
            ],
            [
                "code" => 1705,
                "provinsi_code" => 17,
                "nama" => "KAB. SELUMA"
            ],
            [
                "code" => 1706,
                "provinsi_code" => 17,
                "nama" => "KAB. MUKO MUKO"
            ],
            [
                "code" => 1707,
                "provinsi_code" => 17,
                "nama" => "KAB. LEBONG"
            ],
            [
                "code" => 1708,
                "provinsi_code" => 17,
                "nama" => "KAB. KEPAHIANG"
            ],
            [
                "code" => 1709,
                "provinsi_code" => 17,
                "nama" => "KAB. BENGKULU TENGAH"
            ],
            [
                "code" => 1771,
                "provinsi_code" => 17,
                "nama" => "KOTA BENGKULU"
            ],
            [
                "code" => 1801,
                "provinsi_code" => 18,
                "nama" => "KAB. LAMPUNG SELATAN"
            ],
            [
                "code" => 1802,
                "provinsi_code" => 18,
                "nama" => "KAB. LAMPUNG TENGAH"
            ],
            [
                "code" => 1803,
                "provinsi_code" => 18,
                "nama" => "KAB. LAMPUNG UTARA"
            ],
            [
                "code" => 1804,
                "provinsi_code" => 18,
                "nama" => "KAB. LAMPUNG BARAT"
            ],
            [
                "code" => 1805,
                "provinsi_code" => 18,
                "nama" => "KAB. TULANG BAWANG"
            ],
            [
                "code" => 1806,
                "provinsi_code" => 18,
                "nama" => "KAB. TANGGAMUS"
            ],
            [
                "code" => 1807,
                "provinsi_code" => 18,
                "nama" => "KAB. LAMPUNG TIMUR"
            ],
            [
                "code" => 1808,
                "provinsi_code" => 18,
                "nama" => "KAB. WAY KANAN"
            ],
            [
                "code" => 1809,
                "provinsi_code" => 18,
                "nama" => "KAB. PESAWARAN"
            ],
            [
                "code" => 1810,
                "provinsi_code" => 18,
                "nama" => "KAB. PRINGSEWU"
            ],
            [
                "code" => 1811,
                "provinsi_code" => 18,
                "nama" => "KAB. MESUJI"
            ],
            [
                "code" => 1812,
                "provinsi_code" => 18,
                "nama" => "KAB. TULANG BAWANG BARAT"
            ],
            [
                "code" => 1813,
                "provinsi_code" => 18,
                "nama" => "KAB. PESISIR BARAT"
            ],
            [
                "code" => 1871,
                "provinsi_code" => 18,
                "nama" => "KOTA BANDAR LAMPUNG"
            ],
            [
                "code" => 1872,
                "provinsi_code" => 18,
                "nama" => "KOTA METRO"
            ],
            [
                "code" => 1901,
                "provinsi_code" => 19,
                "nama" => "KAB. BANGKA"
            ],
            [
                "code" => 1902,
                "provinsi_code" => 19,
                "nama" => "KAB. BELITUNG"
            ],
            [
                "code" => 1903,
                "provinsi_code" => 19,
                "nama" => "KAB. BANGKA SELATAN"
            ],
            [
                "code" => 1904,
                "provinsi_code" => 19,
                "nama" => "KAB. BANGKA TENGAH"
            ],
            [
                "code" => 1905,
                "provinsi_code" => 19,
                "nama" => "KAB. BANGKA BARAT"
            ],
            [
                "code" => 1906,
                "provinsi_code" => 19,
                "nama" => "KAB. BELITUNG TIMUR"
            ],
            [
                "code" => 1971,
                "provinsi_code" => 19,
                "nama" => "KOTA PANGKAL PINANG"
            ],
            [
                "code" => 2101,
                "provinsi_code" => 21,
                "nama" => "KAB. BINTAN"
            ],
            [
                "code" => 2102,
                "provinsi_code" => 21,
                "nama" => "KAB. KARIMUN"
            ],
            [
                "code" => 2103,
                "provinsi_code" => 21,
                "nama" => "KAB. NATUNA"
            ],
            [
                "code" => 2104,
                "provinsi_code" => 21,
                "nama" => "KAB. LINGGA"
            ],
            [
                "code" => 2105,
                "provinsi_code" => 21,
                "nama" => "KAB. KEPULAUAN ANAMBAS"
            ],
            [
                "code" => 2171,
                "provinsi_code" => 21,
                "nama" => "KOTA BATAM"
            ],
            [
                "code" => 2172,
                "provinsi_code" => 21,
                "nama" => "KOTA TANJUNG PINANG"
            ],
            [
                "code" => 3101,
                "provinsi_code" => 31,
                "nama" => "KAB. ADM. KEP. SERIBU"
            ],
            [
                "code" => 3171,
                "provinsi_code" => 31,
                "nama" => "KOTA ADM. JAKARTA PUSAT"
            ],
            [
                "code" => 3172,
                "provinsi_code" => 31,
                "nama" => "KOTA ADM. JAKARTA UTARA"
            ],
            [
                "code" => 3173,
                "provinsi_code" => 31,
                "nama" => "KOTA ADM. JAKARTA BARAT"
            ],
            [
                "code" => 3174,
                "provinsi_code" => 31,
                "nama" => "KOTA ADM. JAKARTA SELATAN"
            ],
            [
                "code" => 3175,
                "provinsi_code" => 31,
                "nama" => "KOTA ADM. JAKARTA TIMUR"
            ],
            [
                "code" => 3201,
                "provinsi_code" => 32,
                "nama" => "KAB. BOGOR"
            ],
            [
                "code" => 3202,
                "provinsi_code" => 32,
                "nama" => "KAB. SUKABUMI"
            ],
            [
                "code" => 3203,
                "provinsi_code" => 32,
                "nama" => "KAB. CIANJUR"
            ],
            [
                "code" => 3204,
                "provinsi_code" => 32,
                "nama" => "KAB. BANDUNG"
            ],
            [
                "code" => 3205,
                "provinsi_code" => 32,
                "nama" => "KAB. GARUT"
            ],
            [
                "code" => 3206,
                "provinsi_code" => 32,
                "nama" => "KAB. TASIKMALAYA"
            ],
            [
                "code" => 3207,
                "provinsi_code" => 32,
                "nama" => "KAB. CIAMIS"
            ],
            [
                "code" => 3208,
                "provinsi_code" => 32,
                "nama" => "KAB. KUNINGAN"
            ],
            [
                "code" => 3209,
                "provinsi_code" => 32,
                "nama" => "KAB. CIREBON"
            ],
            [
                "code" => 3210,
                "provinsi_code" => 32,
                "nama" => "KAB. MAJALENGKA"
            ],
            [
                "code" => 3211,
                "provinsi_code" => 32,
                "nama" => "KAB. SUMEDANG"
            ],
            [
                "code" => 3212,
                "provinsi_code" => 32,
                "nama" => "KAB. INDRAMAYU"
            ],
            [
                "code" => 3213,
                "provinsi_code" => 32,
                "nama" => "KAB. SUBANG"
            ],
            [
                "code" => 3214,
                "provinsi_code" => 32,
                "nama" => "KAB. PURWAKARTA"
            ],
            [
                "code" => 3215,
                "provinsi_code" => 32,
                "nama" => "KAB. KARAWANG"
            ],
            [
                "code" => 3216,
                "provinsi_code" => 32,
                "nama" => "KAB. BEKASI"
            ],
            [
                "code" => 3217,
                "provinsi_code" => 32,
                "nama" => "KAB. BANDUNG BARAT"
            ],
            [
                "code" => 3218,
                "provinsi_code" => 32,
                "nama" => "KAB. PANGANDARAN"
            ],
            [
                "code" => 3271,
                "provinsi_code" => 32,
                "nama" => "KOTA BOGOR"
            ],
            [
                "code" => 3272,
                "provinsi_code" => 32,
                "nama" => "KOTA SUKABUMI"
            ],
            [
                "code" => 3273,
                "provinsi_code" => 32,
                "nama" => "KOTA BANDUNG"
            ],
            [
                "code" => 3274,
                "provinsi_code" => 32,
                "nama" => "KOTA CIREBON"
            ],
            [
                "code" => 3275,
                "provinsi_code" => 32,
                "nama" => "KOTA BEKASI"
            ],
            [
                "code" => 3276,
                "provinsi_code" => 32,
                "nama" => "KOTA DEPOK"
            ],
            [
                "code" => 3277,
                "provinsi_code" => 32,
                "nama" => "KOTA CIMAHI"
            ],
            [
                "code" => 3278,
                "provinsi_code" => 32,
                "nama" => "KOTA TASIKMALAYA"
            ],
            [
                "code" => 3279,
                "provinsi_code" => 32,
                "nama" => "KOTA BANJAR"
            ],
            [
                "code" => 3301,
                "provinsi_code" => 33,
                "nama" => "KAB. CILACAP"
            ],
            [
                "code" => 3302,
                "provinsi_code" => 33,
                "nama" => "KAB. BANYUMAS"
            ],
            [
                "code" => 3303,
                "provinsi_code" => 33,
                "nama" => "KAB. PURBALINGGA"
            ],
            [
                "code" => 3304,
                "provinsi_code" => 33,
                "nama" => "KAB. BANJARNEGARA"
            ],
            [
                "code" => 3305,
                "provinsi_code" => 33,
                "nama" => "KAB. KEBUMEN"
            ],
            [
                "code" => 3306,
                "provinsi_code" => 33,
                "nama" => "KAB. PURWOREJO"
            ],
            [
                "code" => 3307,
                "provinsi_code" => 33,
                "nama" => "KAB. WONOSOBO"
            ],
            [
                "code" => 3308,
                "provinsi_code" => 33,
                "nama" => "KAB. MAGELANG"
            ],
            [
                "code" => 3309,
                "provinsi_code" => 33,
                "nama" => "KAB. BOYOLALI"
            ],
            [
                "code" => 3310,
                "provinsi_code" => 33,
                "nama" => "KAB. KLATEN"
            ],
            [
                "code" => 3311,
                "provinsi_code" => 33,
                "nama" => "KAB. SUKOHARJO"
            ],
            [
                "code" => 3312,
                "provinsi_code" => 33,
                "nama" => "KAB. WONOGIRI"
            ],
            [
                "code" => 3313,
                "provinsi_code" => 33,
                "nama" => "KAB. KARANGANYAR"
            ],
            [
                "code" => 3314,
                "provinsi_code" => 33,
                "nama" => "KAB. SRAGEN"
            ],
            [
                "code" => 3315,
                "provinsi_code" => 33,
                "nama" => "KAB. GROBOGAN"
            ],
            [
                "code" => 3316,
                "provinsi_code" => 33,
                "nama" => "KAB. BLORA"
            ],
            [
                "code" => 3317,
                "provinsi_code" => 33,
                "nama" => "KAB. REMBANG"
            ],
            [
                "code" => 3318,
                "provinsi_code" => 33,
                "nama" => "KAB. PATI"
            ],
            [
                "code" => 3319,
                "provinsi_code" => 33,
                "nama" => "KAB. KUDUS"
            ],
            [
                "code" => 3320,
                "provinsi_code" => 33,
                "nama" => "KAB. JEPARA"
            ],
            [
                "code" => 3321,
                "provinsi_code" => 33,
                "nama" => "KAB. DEMAK"
            ],
            [
                "code" => 3322,
                "provinsi_code" => 33,
                "nama" => "KAB. SEMARANG"
            ],
            [
                "code" => 3323,
                "provinsi_code" => 33,
                "nama" => "KAB. TEMANGGUNG"
            ],
            [
                "code" => 3324,
                "provinsi_code" => 33,
                "nama" => "KAB. KENDAL"
            ],
            [
                "code" => 3325,
                "provinsi_code" => 33,
                "nama" => "KAB. BATANG"
            ],
            [
                "code" => 3326,
                "provinsi_code" => 33,
                "nama" => "KAB. PEKALONGAN"
            ],
            [
                "code" => 3327,
                "provinsi_code" => 33,
                "nama" => "KAB. PEMALANG"
            ],
            [
                "code" => 3328,
                "provinsi_code" => 33,
                "nama" => "KAB. TEGAL"
            ],
            [
                "code" => 3329,
                "provinsi_code" => 33,
                "nama" => "KAB. BREBES"
            ],
            [
                "code" => 3371,
                "provinsi_code" => 33,
                "nama" => "KOTA MAGELANG"
            ],
            [
                "code" => 3372,
                "provinsi_code" => 33,
                "nama" => "KOTA SURAKARTA"
            ],
            [
                "code" => 3373,
                "provinsi_code" => 33,
                "nama" => "KOTA SALATIGA"
            ],
            [
                "code" => 3374,
                "provinsi_code" => 33,
                "nama" => "KOTA SEMARANG"
            ],
            [
                "code" => 3375,
                "provinsi_code" => 33,
                "nama" => "KOTA PEKALONGAN"
            ],
            [
                "code" => 3376,
                "provinsi_code" => 33,
                "nama" => "KOTA TEGAL"
            ],
            [
                "code" => 3401,
                "provinsi_code" => 34,
                "nama" => "KAB. KULON PROGO"
            ],
            [
                "code" => 3402,
                "provinsi_code" => 34,
                "nama" => "KAB. BANTUL"
            ],
            [
                "code" => 3403,
                "provinsi_code" => 34,
                "nama" => "KAB. GUNUNGKIDUL"
            ],
            [
                "code" => 3404,
                "provinsi_code" => 34,
                "nama" => "KAB. SLEMAN"
            ],
            [
                "code" => 3471,
                "provinsi_code" => 34,
                "nama" => "KOTA YOGYAKARTA"
            ],
            [
                "code" => 3501,
                "provinsi_code" => 35,
                "nama" => "KAB. PACITAN"
            ],
            [
                "code" => 3502,
                "provinsi_code" => 35,
                "nama" => "KAB. PONOROGO"
            ],
            [
                "code" => 3503,
                "provinsi_code" => 35,
                "nama" => "KAB. TRENGGALEK"
            ],
            [
                "code" => 3504,
                "provinsi_code" => 35,
                "nama" => "KAB. TULUNGAGUNG"
            ],
            [
                "code" => 3505,
                "provinsi_code" => 35,
                "nama" => "KAB. BLITAR"
            ],
            [
                "code" => 3506,
                "provinsi_code" => 35,
                "nama" => "KAB. KEDIRI"
            ],
            [
                "code" => 3507,
                "provinsi_code" => 35,
                "nama" => "KAB. MALANG"
            ],
            [
                "code" => 3508,
                "provinsi_code" => 35,
                "nama" => "KAB. LUMAJANG"
            ],
            [
                "code" => 3509,
                "provinsi_code" => 35,
                "nama" => "KAB. JEMBER"
            ],
            [
                "code" => 3510,
                "provinsi_code" => 35,
                "nama" => "KAB. BANYUWANGI"
            ],
            [
                "code" => 3511,
                "provinsi_code" => 35,
                "nama" => "KAB. BONDOWOSO"
            ],
            [
                "code" => 3512,
                "provinsi_code" => 35,
                "nama" => "KAB. SITUBONDO"
            ],
            [
                "code" => 3513,
                "provinsi_code" => 35,
                "nama" => "KAB. PROBOLINGGO"
            ],
            [
                "code" => 3514,
                "provinsi_code" => 35,
                "nama" => "KAB. PASURUAN"
            ],
            [
                "code" => 3515,
                "provinsi_code" => 35,
                "nama" => "KAB. SIDOARJO"
            ],
            [
                "code" => 3516,
                "provinsi_code" => 35,
                "nama" => "KAB. MOJOKERTO"
            ],
            [
                "code" => 3517,
                "provinsi_code" => 35,
                "nama" => "KAB. JOMBANG"
            ],
            [
                "code" => 3518,
                "provinsi_code" => 35,
                "nama" => "KAB. NGANJUK"
            ],
            [
                "code" => 3519,
                "provinsi_code" => 35,
                "nama" => "KAB. MADIUN"
            ],
            [
                "code" => 3520,
                "provinsi_code" => 35,
                "nama" => "KAB. MAGETAN"
            ],
            [
                "code" => 3521,
                "provinsi_code" => 35,
                "nama" => "KAB. NGAWI"
            ],
            [
                "code" => 3522,
                "provinsi_code" => 35,
                "nama" => "KAB. BOJONEGORO"
            ],
            [
                "code" => 3523,
                "provinsi_code" => 35,
                "nama" => "KAB. TUBAN"
            ],
            [
                "code" => 3524,
                "provinsi_code" => 35,
                "nama" => "KAB. LAMONGAN"
            ],
            [
                "code" => 3525,
                "provinsi_code" => 35,
                "nama" => "KAB. GRESIK"
            ],
            [
                "code" => 3526,
                "provinsi_code" => 35,
                "nama" => "KAB. BANGKALAN"
            ],
            [
                "code" => 3527,
                "provinsi_code" => 35,
                "nama" => "KAB. SAMPANG"
            ],
            [
                "code" => 3528,
                "provinsi_code" => 35,
                "nama" => "KAB. PAMEKASAN"
            ],
            [
                "code" => 3529,
                "provinsi_code" => 35,
                "nama" => "KAB. SUMENEP"
            ],
            [
                "code" => 3571,
                "provinsi_code" => 35,
                "nama" => "KOTA KEDIRI"
            ],
            [
                "code" => 3572,
                "provinsi_code" => 35,
                "nama" => "KOTA BLITAR"
            ],
            [
                "code" => 3573,
                "provinsi_code" => 35,
                "nama" => "KOTA MALANG"
            ],
            [
                "code" => 3574,
                "provinsi_code" => 35,
                "nama" => "KOTA PROBOLINGGO"
            ],
            [
                "code" => 3575,
                "provinsi_code" => 35,
                "nama" => "KOTA PASURUAN"
            ],
            [
                "code" => 3576,
                "provinsi_code" => 35,
                "nama" => "KOTA MOJOKERTO"
            ],
            [
                "code" => 3577,
                "provinsi_code" => 35,
                "nama" => "KOTA MADIUN"
            ],
            [
                "code" => 3578,
                "provinsi_code" => 35,
                "nama" => "KOTA SURABAYA"
            ],
            [
                "code" => 3579,
                "provinsi_code" => 35,
                "nama" => "KOTA BATU"
            ],
            [
                "code" => 3601,
                "provinsi_code" => 36,
                "nama" => "KAB. PANDEGLANG"
            ],
            [
                "code" => 3602,
                "provinsi_code" => 36,
                "nama" => "KAB. LEBAK"
            ],
            [
                "code" => 3603,
                "provinsi_code" => 36,
                "nama" => "KAB. TANGERANG"
            ],
            [
                "code" => 3604,
                "provinsi_code" => 36,
                "nama" => "KAB. SERANG"
            ],
            [
                "code" => 3671,
                "provinsi_code" => 36,
                "nama" => "KOTA TANGERANG"
            ],
            [
                "code" => 3672,
                "provinsi_code" => 36,
                "nama" => "KOTA CILEGON"
            ],
            [
                "code" => 3673,
                "provinsi_code" => 36,
                "nama" => "KOTA SERANG"
            ],
            [
                "code" => 3674,
                "provinsi_code" => 36,
                "nama" => "KOTA TANGERANG SELATAN"
            ],
            [
                "code" => 5101,
                "provinsi_code" => 51,
                "nama" => "KAB. JEMBRANA"
            ],
            [
                "code" => 5102,
                "provinsi_code" => 51,
                "nama" => "KAB. TABANAN"
            ],
            [
                "code" => 5103,
                "provinsi_code" => 51,
                "nama" => "KAB. BADUNG"
            ],
            [
                "code" => 5104,
                "provinsi_code" => 51,
                "nama" => "KAB. GIANYAR"
            ],
            [
                "code" => 5105,
                "provinsi_code" => 51,
                "nama" => "KAB. KLUNGKUNG"
            ],
            [
                "code" => 5106,
                "provinsi_code" => 51,
                "nama" => "KAB. BANGLI"
            ],
            [
                "code" => 5107,
                "provinsi_code" => 51,
                "nama" => "KAB. KARANGASEM"
            ],
            [
                "code" => 5108,
                "provinsi_code" => 51,
                "nama" => "KAB. BULELENG"
            ],
            [
                "code" => 5171,
                "provinsi_code" => 51,
                "nama" => "KOTA DENPASAR"
            ],
            [
                "code" => 5201,
                "provinsi_code" => 52,
                "nama" => "KAB. LOMBOK BARAT"
            ],
            [
                "code" => 5202,
                "provinsi_code" => 52,
                "nama" => "KAB. LOMBOK TENGAH"
            ],
            [
                "code" => 5203,
                "provinsi_code" => 52,
                "nama" => "KAB. LOMBOK TIMUR"
            ],
            [
                "code" => 5204,
                "provinsi_code" => 52,
                "nama" => "KAB. SUMBAWA"
            ],
            [
                "code" => 5205,
                "provinsi_code" => 52,
                "nama" => "KAB. DOMPU"
            ],
            [
                "code" => 5206,
                "provinsi_code" => 52,
                "nama" => "KAB. BIMA"
            ],
            [
                "code" => 5207,
                "provinsi_code" => 52,
                "nama" => "KAB. SUMBAWA BARAT"
            ],
            [
                "code" => 5208,
                "provinsi_code" => 52,
                "nama" => "KAB. LOMBOK UTARA"
            ],
            [
                "code" => 5271,
                "provinsi_code" => 52,
                "nama" => "KOTA MATARAM"
            ],
            [
                "code" => 5272,
                "provinsi_code" => 52,
                "nama" => "KOTA BIMA"
            ],
            [
                "code" => 5301,
                "provinsi_code" => 53,
                "nama" => "KAB. KUPANG"
            ],
            [
                "code" => 5302,
                "provinsi_code" => 53,
                "nama" => "KAB TIMOR TENGAH SELATAN"
            ],
            [
                "code" => 5303,
                "provinsi_code" => 53,
                "nama" => "KAB. TIMOR TENGAH UTARA"
            ],
            [
                "code" => 5304,
                "provinsi_code" => 53,
                "nama" => "KAB. BELU"
            ],
            [
                "code" => 5305,
                "provinsi_code" => 53,
                "nama" => "KAB. ALOR"
            ],
            [
                "code" => 5306,
                "provinsi_code" => 53,
                "nama" => "KAB. FLORES TIMUR"
            ],
            [
                "code" => 5307,
                "provinsi_code" => 53,
                "nama" => "KAB. SIKKA"
            ],
            [
                "code" => 5308,
                "provinsi_code" => 53,
                "nama" => "KAB. ENDE"
            ],
            [
                "code" => 5309,
                "provinsi_code" => 53,
                "nama" => "KAB. NGADA"
            ],
            [
                "code" => 5310,
                "provinsi_code" => 53,
                "nama" => "KAB. MANGGARAI"
            ],
            [
                "code" => 5311,
                "provinsi_code" => 53,
                "nama" => "KAB. SUMBA TIMUR"
            ],
            [
                "code" => 5312,
                "provinsi_code" => 53,
                "nama" => "KAB. SUMBA BARAT"
            ],
            [
                "code" => 5313,
                "provinsi_code" => 53,
                "nama" => "KAB. LEMBATA"
            ],
            [
                "code" => 5314,
                "provinsi_code" => 53,
                "nama" => "KAB. ROTE NDAO"
            ],
            [
                "code" => 5315,
                "provinsi_code" => 53,
                "nama" => "KAB. MANGGARAI BARAT"
            ],
            [
                "code" => 5316,
                "provinsi_code" => 53,
                "nama" => "KAB. NAGEKEO"
            ],
            [
                "code" => 5317,
                "provinsi_code" => 53,
                "nama" => "KAB. SUMBA TENGAH"
            ],
            [
                "code" => 5318,
                "provinsi_code" => 53,
                "nama" => "KAB. SUMBA BARAT DAYA"
            ],
            [
                "code" => 5319,
                "provinsi_code" => 53,
                "nama" => "KAB. MANGGARAI TIMUR"
            ],
            [
                "code" => 5320,
                "provinsi_code" => 53,
                "nama" => "KAB. SABU RAIJUA"
            ],
            [
                "code" => 5321,
                "provinsi_code" => 53,
                "nama" => "KAB. MALAKA"
            ],
            [
                "code" => 5371,
                "provinsi_code" => 53,
                "nama" => "KOTA KUPANG"
            ],
            [
                "code" => 6101,
                "provinsi_code" => 61,
                "nama" => "KAB. SAMBAS"
            ],
            [
                "code" => 6102,
                "provinsi_code" => 61,
                "nama" => "KAB. MEMPAWAH"
            ],
            [
                "code" => 6103,
                "provinsi_code" => 61,
                "nama" => "KAB. SANGGAU"
            ],
            [
                "code" => 6104,
                "provinsi_code" => 61,
                "nama" => "KAB. KETAPANG"
            ],
            [
                "code" => 6105,
                "provinsi_code" => 61,
                "nama" => "KAB. SINTANG"
            ],
            [
                "code" => 6106,
                "provinsi_code" => 61,
                "nama" => "KAB. KAPUAS HULU"
            ],
            [
                "code" => 6107,
                "provinsi_code" => 61,
                "nama" => "KAB. BENGKAYANG"
            ],
            [
                "code" => 6108,
                "provinsi_code" => 61,
                "nama" => "KAB. LANDAK"
            ],
            [
                "code" => 6109,
                "provinsi_code" => 61,
                "nama" => "KAB. SEKADAU"
            ],
            [
                "code" => 6110,
                "provinsi_code" => 61,
                "nama" => "KAB. MELAWI"
            ],
            [
                "code" => 6111,
                "provinsi_code" => 61,
                "nama" => "KAB. KAYONG UTARA"
            ],
            [
                "code" => 6112,
                "provinsi_code" => 61,
                "nama" => "KAB. KUBU RAYA"
            ],
            [
                "code" => 6171,
                "provinsi_code" => 61,
                "nama" => "KOTA PONTIANAK"
            ],
            [
                "code" => 6172,
                "provinsi_code" => 61,
                "nama" => "KOTA SINGKAWANG"
            ],
            [
                "code" => 6201,
                "provinsi_code" => 62,
                "nama" => "KAB. KOTAWARINGIN BARAT"
            ],
            [
                "code" => 6202,
                "provinsi_code" => 62,
                "nama" => "KAB. KOTAWARINGIN TIMUR"
            ],
            [
                "code" => 6203,
                "provinsi_code" => 62,
                "nama" => "KAB. KAPUAS"
            ],
            [
                "code" => 6204,
                "provinsi_code" => 62,
                "nama" => "KAB. BARITO SELATAN"
            ],
            [
                "code" => 6205,
                "provinsi_code" => 62,
                "nama" => "KAB. BARITO UTARA"
            ],
            [
                "code" => 6206,
                "provinsi_code" => 62,
                "nama" => "KAB. KATINGAN"
            ],
            [
                "code" => 6207,
                "provinsi_code" => 62,
                "nama" => "KAB. SERUYAN"
            ],
            [
                "code" => 6208,
                "provinsi_code" => 62,
                "nama" => "KAB. SUKAMARA"
            ],
            [
                "code" => 6209,
                "provinsi_code" => 62,
                "nama" => "KAB. LAMANDAU"
            ],
            [
                "code" => 6210,
                "provinsi_code" => 62,
                "nama" => "KAB. GUNUNG MAS"
            ],
            [
                "code" => 6211,
                "provinsi_code" => 62,
                "nama" => "KAB. PULANG PISAU"
            ],
            [
                "code" => 6212,
                "provinsi_code" => 62,
                "nama" => "KAB. MURUNG RAYA"
            ],
            [
                "code" => 6213,
                "provinsi_code" => 62,
                "nama" => "KAB. BARITO TIMUR"
            ],
            [
                "code" => 6271,
                "provinsi_code" => 62,
                "nama" => "KOTA PALANGKARAYA"
            ],
            [
                "code" => 6301,
                "provinsi_code" => 63,
                "nama" => "KAB. TANAH LAUT"
            ],
            [
                "code" => 6302,
                "provinsi_code" => 63,
                "nama" => "KAB. KOTABARU"
            ],
            [
                "code" => 6303,
                "provinsi_code" => 63,
                "nama" => "KAB. BANJAR"
            ],
            [
                "code" => 6304,
                "provinsi_code" => 63,
                "nama" => "KAB. BARITO KUALA"
            ],
            [
                "code" => 6305,
                "provinsi_code" => 63,
                "nama" => "KAB. TAPIN"
            ],
            [
                "code" => 6306,
                "provinsi_code" => 63,
                "nama" => "KAB. HULU SUNGAI SELATAN"
            ],
            [
                "code" => 6307,
                "provinsi_code" => 63,
                "nama" => "KAB. HULU SUNGAI TENGAH"
            ],
            [
                "code" => 6308,
                "provinsi_code" => 63,
                "nama" => "KAB. HULU SUNGAI UTARA"
            ],
            [
                "code" => 6309,
                "provinsi_code" => 63,
                "nama" => "KAB. TABALONG"
            ],
            [
                "code" => 6310,
                "provinsi_code" => 63,
                "nama" => "KAB. TANAH BUMBU"
            ],
            [
                "code" => 6311,
                "provinsi_code" => 63,
                "nama" => "KAB. BALANGAN"
            ],
            [
                "code" => 6371,
                "provinsi_code" => 63,
                "nama" => "KOTA BANJARMASIN"
            ],
            [
                "code" => 6372,
                "provinsi_code" => 63,
                "nama" => "KOTA BANJARBARU"
            ],
            [
                "code" => 6401,
                "provinsi_code" => 64,
                "nama" => "KAB. PASER"
            ],
            [
                "code" => 6402,
                "provinsi_code" => 64,
                "nama" => "KAB. KUTAI KARTANEGARA"
            ],
            [
                "code" => 6403,
                "provinsi_code" => 64,
                "nama" => "KAB. BERAU"
            ],
            [
                "code" => 6407,
                "provinsi_code" => 64,
                "nama" => "KAB. KUTAI BARAT"
            ],
            [
                "code" => 6408,
                "provinsi_code" => 64,
                "nama" => "KAB. KUTAI TIMUR"
            ],
            [
                "code" => 6409,
                "provinsi_code" => 64,
                "nama" => "KAB. PENAJAM PASER UTARA"
            ],
            [
                "code" => 6411,
                "provinsi_code" => 64,
                "nama" => "KAB. MAHAKAM ULU"
            ],
            [
                "code" => 6471,
                "provinsi_code" => 64,
                "nama" => "KOTA BALIKPAPAN"
            ],
            [
                "code" => 6472,
                "provinsi_code" => 64,
                "nama" => "KOTA SAMARINDA"
            ],
            [
                "code" => 6474,
                "provinsi_code" => 64,
                "nama" => "KOTA BONTANG"
            ],
            [
                "code" => 6501,
                "provinsi_code" => 65,
                "nama" => "KAB. BULUNGAN"
            ],
            [
                "code" => 6502,
                "provinsi_code" => 65,
                "nama" => "KAB. MALINAU"
            ],
            [
                "code" => 6503,
                "provinsi_code" => 65,
                "nama" => "KAB. NUNUKAN"
            ],
            [
                "code" => 6504,
                "provinsi_code" => 65,
                "nama" => "KAB. TANA TIDUNG"
            ],
            [
                "code" => 6571,
                "provinsi_code" => 65,
                "nama" => "KOTA TARAKAN"
            ],
            [
                "code" => 7101,
                "provinsi_code" => 71,
                "nama" => "KAB. BOLAANG MONGONDOW"
            ],
            [
                "code" => 7102,
                "provinsi_code" => 71,
                "nama" => "KAB. MINAHASA"
            ],
            [
                "code" => 7103,
                "provinsi_code" => 71,
                "nama" => "KAB. KEPULAUAN SANGIHE"
            ],
            [
                "code" => 7104,
                "provinsi_code" => 71,
                "nama" => "KAB. KEPULAUAN TALAUD"
            ],
            [
                "code" => 7105,
                "provinsi_code" => 71,
                "nama" => "KAB. MINAHASA SELATAN"
            ],
            [
                "code" => 7106,
                "provinsi_code" => 71,
                "nama" => "KAB. MINAHASA UTARA"
            ],
            [
                "code" => 7107,
                "provinsi_code" => 71,
                "nama" => "KAB. MINAHASA TENGGARA"
            ],
            [
                "code" => 7108,
                "provinsi_code" => 71,
                "nama" => "KAB. BOLAANG MONGONDOW UTARA"
            ],
            [
                "code" => 7109,
                "provinsi_code" => 71,
                "nama" => "KAB. KEP. SIAU TAGULANDANG BIARO"
            ],
            [
                "code" => 7110,
                "provinsi_code" => 71,
                "nama" => "KAB. BOLAANG MONGONDOW TIMUR"
            ],
            [
                "code" => 7111,
                "provinsi_code" => 71,
                "nama" => "KAB. BOLAANG MONGONDOW SELATAN"
            ],
            [
                "code" => 7171,
                "provinsi_code" => 71,
                "nama" => "KOTA MANADO"
            ],
            [
                "code" => 7172,
                "provinsi_code" => 71,
                "nama" => "KOTA BITUNG"
            ],
            [
                "code" => 7173,
                "provinsi_code" => 71,
                "nama" => "KOTA TOMOHON"
            ],
            [
                "code" => 7174,
                "provinsi_code" => 71,
                "nama" => "KOTA KOTAMOBAGU"
            ],
            [
                "code" => 7201,
                "provinsi_code" => 72,
                "nama" => "KAB. BANGGAI"
            ],
            [
                "code" => 7202,
                "provinsi_code" => 72,
                "nama" => "KAB. POSO"
            ],
            [
                "code" => 7203,
                "provinsi_code" => 72,
                "nama" => "KAB. DONGGALA"
            ],
            [
                "code" => 7204,
                "provinsi_code" => 72,
                "nama" => "KAB. TOLI TOLI"
            ],
            [
                "code" => 7205,
                "provinsi_code" => 72,
                "nama" => "KAB. BUOL"
            ],
            [
                "code" => 7206,
                "provinsi_code" => 72,
                "nama" => "KAB. MOROWALI"
            ],
            [
                "code" => 7207,
                "provinsi_code" => 72,
                "nama" => "KAB. BANGGAI KEPULAUAN"
            ],
            [
                "code" => 7208,
                "provinsi_code" => 72,
                "nama" => "KAB. PARIGI MOUTONG"
            ],
            [
                "code" => 7209,
                "provinsi_code" => 72,
                "nama" => "KAB. TOJO UNA UNA"
            ],
            [
                "code" => 7210,
                "provinsi_code" => 72,
                "nama" => "KAB. SIGI"
            ],
            [
                "code" => 7211,
                "provinsi_code" => 72,
                "nama" => "KAB. BANGGAI LAUT"
            ],
            [
                "code" => 7212,
                "provinsi_code" => 72,
                "nama" => "KAB. MOROWALI UTARA"
            ],
            [
                "code" => 7271,
                "provinsi_code" => 72,
                "nama" => "KOTA PALU"
            ],
            [
                "code" => 7301,
                "provinsi_code" => 73,
                "nama" => "KAB. KEPULAUAN SELAYAR"
            ],
            [
                "code" => 7302,
                "provinsi_code" => 73,
                "nama" => "KAB. BULUKUMBA"
            ],
            [
                "code" => 7303,
                "provinsi_code" => 73,
                "nama" => "KAB. BANTAENG"
            ],
            [
                "code" => 7304,
                "provinsi_code" => 73,
                "nama" => "KAB. JENEPONTO"
            ],
            [
                "code" => 7305,
                "provinsi_code" => 73,
                "nama" => "KAB. TAKALAR"
            ],
            [
                "code" => 7306,
                "provinsi_code" => 73,
                "nama" => "KAB. GOWA"
            ],
            [
                "code" => 7307,
                "provinsi_code" => 73,
                "nama" => "KAB. SINJAI"
            ],
            [
                "code" => 7308,
                "provinsi_code" => 73,
                "nama" => "KAB. BONE"
            ],
            [
                "code" => 7309,
                "provinsi_code" => 73,
                "nama" => "KAB. MAROS"
            ],
            [
                "code" => 7310,
                "provinsi_code" => 73,
                "nama" => "KAB. PANGKAJENE KEPULAUAN"
            ],
            [
                "code" => 7311,
                "provinsi_code" => 73,
                "nama" => "KAB. BARRU"
            ],
            [
                "code" => 7312,
                "provinsi_code" => 73,
                "nama" => "KAB. SOPPENG"
            ],
            [
                "code" => 7313,
                "provinsi_code" => 73,
                "nama" => "KAB. WAJO"
            ],
            [
                "code" => 7314,
                "provinsi_code" => 73,
                "nama" => "KAB. SIDENRENG RAPPANG"
            ],
            [
                "code" => 7315,
                "provinsi_code" => 73,
                "nama" => "KAB. PINRANG"
            ],
            [
                "code" => 7316,
                "provinsi_code" => 73,
                "nama" => "KAB. ENREKANG"
            ],
            [
                "code" => 7317,
                "provinsi_code" => 73,
                "nama" => "KAB. LUWU"
            ],
            [
                "code" => 7318,
                "provinsi_code" => 73,
                "nama" => "KAB. TANA TORAJA"
            ],
            [
                "code" => 7322,
                "provinsi_code" => 73,
                "nama" => "KAB. LUWU UTARA"
            ],
            [
                "code" => 7324,
                "provinsi_code" => 73,
                "nama" => "KAB. LUWU TIMUR"
            ],
            [
                "code" => 7326,
                "provinsi_code" => 73,
                "nama" => "KAB. TORAJA UTARA"
            ],
            [
                "code" => 7371,
                "provinsi_code" => 73,
                "nama" => "KOTA MAKASSAR"
            ],
            [
                "code" => 7372,
                "provinsi_code" => 73,
                "nama" => "KOTA PARE PARE"
            ],
            [
                "code" => 7373,
                "provinsi_code" => 73,
                "nama" => "KOTA PALOPO"
            ],
            [
                "code" => 7401,
                "provinsi_code" => 74,
                "nama" => "KAB. KOLAKA"
            ],
            [
                "code" => 7402,
                "provinsi_code" => 74,
                "nama" => "KAB. KONAWE"
            ],
            [
                "code" => 7403,
                "provinsi_code" => 74,
                "nama" => "KAB. MUNA"
            ],
            [
                "code" => 7404,
                "provinsi_code" => 74,
                "nama" => "KAB. BUTON"
            ],
            [
                "code" => 7405,
                "provinsi_code" => 74,
                "nama" => "KAB. KONAWE SELATAN"
            ],
            [
                "code" => 7406,
                "provinsi_code" => 74,
                "nama" => "KAB. BOMBANA"
            ],
            [
                "code" => 7407,
                "provinsi_code" => 74,
                "nama" => "KAB. WAKATOBI"
            ],
            [
                "code" => 7408,
                "provinsi_code" => 74,
                "nama" => "KAB. KOLAKA UTARA"
            ],
            [
                "code" => 7409,
                "provinsi_code" => 74,
                "nama" => "KAB. KONAWE UTARA"
            ],
            [
                "code" => 7410,
                "provinsi_code" => 74,
                "nama" => "KAB. BUTON UTARA"
            ],
            [
                "code" => 7411,
                "provinsi_code" => 74,
                "nama" => "KAB. KOLAKA TIMUR"
            ],
            [
                "code" => 7412,
                "provinsi_code" => 74,
                "nama" => "KAB. KONAWE KEPULAUAN"
            ],
            [
                "code" => 7413,
                "provinsi_code" => 74,
                "nama" => "KAB. MUNA BARAT"
            ],
            [
                "code" => 7414,
                "provinsi_code" => 74,
                "nama" => "KAB. BUTON TENGAH"
            ],
            [
                "code" => 7415,
                "provinsi_code" => 74,
                "nama" => "KAB. BUTON SELATAN"
            ],
            [
                "code" => 7471,
                "provinsi_code" => 74,
                "nama" => "KOTA KENDARI"
            ],
            [
                "code" => 7472,
                "provinsi_code" => 74,
                "nama" => "KOTA BAU BAU"
            ],
            [
                "code" => 7501,
                "provinsi_code" => 75,
                "nama" => "KAB. GORONTALO"
            ],
            [
                "code" => 7502,
                "provinsi_code" => 75,
                "nama" => "KAB. BOALEMO"
            ],
            [
                "code" => 7503,
                "provinsi_code" => 75,
                "nama" => "KAB. BONE BOLANGO"
            ],
            [
                "code" => 7504,
                "provinsi_code" => 75,
                "nama" => "KAB. POHUWATO"
            ],
            [
                "code" => 7505,
                "provinsi_code" => 75,
                "nama" => "KAB. GORONTALO UTARA"
            ],
            [
                "code" => 7571,
                "provinsi_code" => 75,
                "nama" => "KOTA GORONTALO"
            ],
            [
                "code" => 7601,
                "provinsi_code" => 76,
                "nama" => "KAB. PASANGKAYU"
            ],
            [
                "code" => 7602,
                "provinsi_code" => 76,
                "nama" => "KAB. MAMUJU"
            ],
            [
                "code" => 7603,
                "provinsi_code" => 76,
                "nama" => "KAB. MAMASA"
            ],
            [
                "code" => 7604,
                "provinsi_code" => 76,
                "nama" => "KAB. POLEWALI MANDAR"
            ],
            [
                "code" => 7605,
                "provinsi_code" => 76,
                "nama" => "KAB. MAJENE"
            ],
            [
                "code" => 7606,
                "provinsi_code" => 76,
                "nama" => "KAB. MAMUJU TENGAH"
            ],
            [
                "code" => 8101,
                "provinsi_code" => 81,
                "nama" => "KAB. MALUKU TENGAH"
            ],
            [
                "code" => 8102,
                "provinsi_code" => 81,
                "nama" => "KAB. MALUKU TENGGARA"
            ],
            [
                "code" => 8103,
                "provinsi_code" => 81,
                "nama" => "KAB. KEPULAUAN TANIMBAR"
            ],
            [
                "code" => 8104,
                "provinsi_code" => 81,
                "nama" => "KAB. BURU"
            ],
            [
                "code" => 8105,
                "provinsi_code" => 81,
                "nama" => "KAB. SERAM BAGIAN TIMUR"
            ],
            [
                "code" => 8106,
                "provinsi_code" => 81,
                "nama" => "KAB. SERAM BAGIAN BARAT"
            ],
            [
                "code" => 8107,
                "provinsi_code" => 81,
                "nama" => "KAB. KEPULAUAN ARU"
            ],
            [
                "code" => 8108,
                "provinsi_code" => 81,
                "nama" => "KAB. MALUKU BARAT DAYA"
            ],
            [
                "code" => 8109,
                "provinsi_code" => 81,
                "nama" => "KAB. BURU SELATAN"
            ],
            [
                "code" => 8171,
                "provinsi_code" => 81,
                "nama" => "KOTA AMBON"
            ],
            [
                "code" => 8172,
                "provinsi_code" => 81,
                "nama" => "KOTA TUAL"
            ],
            [
                "code" => 8201,
                "provinsi_code" => 82,
                "nama" => "KAB. HALMAHERA BARAT"
            ],
            [
                "code" => 8202,
                "provinsi_code" => 82,
                "nama" => "KAB. HALMAHERA TENGAH"
            ],
            [
                "code" => 8203,
                "provinsi_code" => 82,
                "nama" => "KAB. HALMAHERA UTARA"
            ],
            [
                "code" => 8204,
                "provinsi_code" => 82,
                "nama" => "KAB. HALMAHERA SELATAN"
            ],
            [
                "code" => 8205,
                "provinsi_code" => 82,
                "nama" => "KAB. KEPULAUAN SULA"
            ],
            [
                "code" => 8206,
                "provinsi_code" => 82,
                "nama" => "KAB. HALMAHERA TIMUR"
            ],
            [
                "code" => 8207,
                "provinsi_code" => 82,
                "nama" => "KAB. PULAU MOROTAI"
            ],
            [
                "code" => 8208,
                "provinsi_code" => 82,
                "nama" => "KAB. PULAU TALIABU"
            ],
            [
                "code" => 8271,
                "provinsi_code" => 82,
                "nama" => "KOTA TERNATE"
            ],
            [
                "code" => 8272,
                "provinsi_code" => 82,
                "nama" => "KOTA TIDORE KEPULAUAN"
            ],
            [
                "code" => 9103,
                "provinsi_code" => 91,
                "nama" => "KAB. JAYAPURA"
            ],
            [
                "code" => 9105,
                "provinsi_code" => 91,
                "nama" => "KAB. KEPULAUAN YAPEN"
            ],
            [
                "code" => 9106,
                "provinsi_code" => 91,
                "nama" => "KAB. BIAK NUMFOR"
            ],
            [
                "code" => 9110,
                "provinsi_code" => 91,
                "nama" => "KAB. SARMI"
            ],
            [
                "code" => 9111,
                "provinsi_code" => 91,
                "nama" => "KAB. KEEROM"
            ],
            [
                "code" => 9115,
                "provinsi_code" => 91,
                "nama" => "KAB. WAROPEN"
            ],
            [
                "code" => 9119,
                "provinsi_code" => 91,
                "nama" => "KAB. SUPIORI"
            ],
            [
                "code" => 9120,
                "provinsi_code" => 91,
                "nama" => "KAB. MAMBERAMO RAYA"
            ],
            [
                "code" => 9171,
                "provinsi_code" => 91,
                "nama" => "KOTA JAYAPURA"
            ],
            [
                "code" => 9201,
                "provinsi_code" => 92,
                "nama" => "KAB. SORONG"
            ],
            [
                "code" => 9202,
                "provinsi_code" => 92,
                "nama" => "KAB. MANOKWARI"
            ],
            [
                "code" => 9203,
                "provinsi_code" => 92,
                "nama" => "KAB. FAK FAK"
            ],
            [
                "code" => 9204,
                "provinsi_code" => 92,
                "nama" => "KAB. SORONG SELATAN"
            ],
            [
                "code" => 9205,
                "provinsi_code" => 92,
                "nama" => "KAB. RAJA AMPAT"
            ],
            [
                "code" => 9206,
                "provinsi_code" => 92,
                "nama" => "KAB. TELUK BINTUNI"
            ],
            [
                "code" => 9207,
                "provinsi_code" => 92,
                "nama" => "KAB. TELUK WONDAMA"
            ],
            [
                "code" => 9208,
                "provinsi_code" => 92,
                "nama" => "KAB. KAIMANA"
            ],
            [
                "code" => 9209,
                "provinsi_code" => 92,
                "nama" => "KAB. TAMBRAUW"
            ],
            [
                "code" => 9210,
                "provinsi_code" => 92,
                "nama" => "KAB. MAYBRAT"
            ],
            [
                "code" => 9211,
                "provinsi_code" => 92,
                "nama" => "KAB. MANOKWARI SELATAN"
            ],
            [
                "code" => 9212,
                "provinsi_code" => 92,
                "nama" => "KAB. PEGUNUNGAN ARFAK"
            ],
            [
                "code" => 9271,
                "provinsi_code" => 92,
                "nama" => "KOTA SORONG"
            ],
            [
                "code" => 9301,
                "provinsi_code" => 93,
                "nama" => "KAB. MERAUKE"
            ],
            [
                "code" => 9302,
                "provinsi_code" => 93,
                "nama" => "KAB. BOVEN DIGOEL"
            ],
            [
                "code" => 9303,
                "provinsi_code" => 93,
                "nama" => "KAB. MAPPI"
            ],
            [
                "code" => 9304,
                "provinsi_code" => 93,
                "nama" => "KAB. ASMAT"
            ],
            [
                "code" => 9401,
                "provinsi_code" => 94,
                "nama" => "KAB. NABIRE"
            ],
            [
                "code" => 9402,
                "provinsi_code" => 94,
                "nama" => "KAB. PUNCAK JAYA"
            ],
            [
                "code" => 9403,
                "provinsi_code" => 94,
                "nama" => "KAB. PANIAI"
            ],
            [
                "code" => 9404,
                "provinsi_code" => 94,
                "nama" => "KAB. MIMIKA"
            ],
            [
                "code" => 9405,
                "provinsi_code" => 94,
                "nama" => "KAB. PUNCAK"
            ],
            [
                "code" => 9406,
                "provinsi_code" => 94,
                "nama" => "KAB. DOGIYAI"
            ],
            [
                "code" => 9407,
                "provinsi_code" => 94,
                "nama" => "KAB. INTAN JAYA"
            ],
            [
                "code" => 9408,
                "provinsi_code" => 94,
                "nama" => "KAB. DEIYAI"
            ],
            [
                "code" => 9501,
                "provinsi_code" => 95,
                "nama" => "KAB. JAYAWIJAYA"
            ],
            [
                "code" => 9502,
                "provinsi_code" => 95,
                "nama" => "KAB. PEGUNUNGAN BINTANG"
            ],
            [
                "code" => 9503,
                "provinsi_code" => 95,
                "nama" => "KAB. YAHUKIMO"
            ],
            [
                "code" => 9504,
                "provinsi_code" => 95,
                "nama" => "KAB. TOLIKARA"
            ],
            [
                "code" => 9505,
                "provinsi_code" => 95,
                "nama" => "KAB. MAMBERAMO TENGAH"
            ],
            [
                "code" => 9506,
                "provinsi_code" => 95,
                "nama" => "KAB. YALIMO"
            ],
            [
                "code" => 9507,
                "provinsi_code" => 95,
                "nama" => "KAB. LANNY JAYA"
            ],
            [
                "code" => 9508,
                "provinsi_code" => 95,
                "nama" => "KAB. NDUGA"
            ]
        ];

        foreach ($regencies as $regency) {
            DB::table('kotas')->insert($regency);
        }
    }
}
