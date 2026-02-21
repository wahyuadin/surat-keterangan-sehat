<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::insert([
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. ALFA MART SEJAHTERA',
                'alamat' => 'JL BARU TELUK JAMBE KARAWANG'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. INDOMARCO ADI PRIMA',
                'alamat' => 'JL BEKASI RAYA CAKUNG'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. TRANS RETAIL INDONESIA',
                'alamat' => 'JL GATOT SUBROTO JAKARTA'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. HERO SUPERMARKET TBK',
                'alamat' => 'JL JEND SUDIRMAN JAKARTA'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. LOTTE MART INDONESIA',
                'alamat' => 'JL KUNINGAN JAKARTA'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. RAMAYANA LESTARI SENTOSA TBK',
                'alamat' => 'JL THAMRIN JAKARTA'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. MATAHARI DEPARTMENT STORE TBK',
                'alamat' => 'JL KEMANG RAYA JAKARTA'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. ACE HARDWARE INDONESIA TBK',
                'alamat' => 'JL PONDOK INDAH JAKARTA'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. INFORMA INDONESIA',
                'alamat' => 'JL SERPONG TANGERANG'
            ],
            [
                'created_by' => 1,
                'nama_perusahaan' => 'PT. TOYS KINGDOM INDONESIA',
                'alamat' => 'JL CASABLANCA JAKARTA'
            ],
        ]);

        Branch::insert([
            [
                'nama_branch' => 'MEDAN',
                'create_by' => 1,
                'alamat' => 'Jl. Medan Binjai KM. 11,5 Dusun I Desa Puji Mulyo Kec. Sunggal Kab.  Deli Serdang Sumatera Utara 20351'
            ],
            [
                'nama_branch' => 'PEKANBARU',
                'create_by' => 1,
                'alamat' => 'Jl. H. Imam Munandar No. 12 Kec. Bukit Raya, Pekanbaru'
            ],
            [
                'nama_branch' => 'CIREBON',
                'create_by' => 1,
                'alamat' => 'Jl. DR. Sudarsono No 282  Kelurahan Kesambi, Kecamatan Kesambi  Cirebon, Jawa Barat 45134'
            ],
            [
                'nama_branch' => 'PALEMBANG',
                'create_by' => 1,
                'alamat' => 'Jl. Basuki Rachmat No. 1676 Palembang'
            ],
            [
                'nama_branch' => 'DKI JAKARTA',
                'create_by' => 1,
                'alamat' => 'Jl. Tangkas Baru No.1 Jakarta Selatan'
            ],
            [
                'nama_branch' => 'BEKASI',
                'create_by' => 1,
                'alamat' => 'Perum Puri Esperanza  Jl. Kemakmuran RT 003 RW 05 kav 25 kelurahan  Margajaya kecamatan bekasi selatan'
            ],
            [
                'nama_branch' => 'CIMAHI',
                'create_by' => 1,
                'alamat' => 'Jl. Jend. Amir Machmud No. 477 Cibabat, Cimahi'
            ],
            [
                'nama_branch' => 'SURABAYA',
                'create_by' => 1,
                'alamat' => 'Jl. Kalijaten Sepanjang Town House C1 No. 3 Sidoarjo'
            ],
            [
                'nama_branch' => 'SEMARANG',
                'create_by' => 1,
                'alamat' => 'Jl. Jend. Sudirman No. 187 - 189  Ruko Sliwangi, Semarang'
            ],
            [
                'nama_branch' => 'MALANG',
                'create_by' => 1,
                'alamat' => 'Jl. Ciliwung No. 17 Kel.Purwantoro, Blimbing, Malang'
            ]
        ]);

        Clinic::insert(
            [
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '201',
                    "nama_klinik" => "Klinik nayaka Husada 01 Sunggal - Medan",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Medan Binjai KM. 11,5 Dusun I Desa Puji Mulyo Kec. Sunggal Kab.  Deli Serdang Sumatera Utara 20351",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1107',
                    "nama_klinik" => "Klinik Nayaka Husada 07 Cibitung",
                    "branch_id" => 1,
                    "alamat" =>
                    "JL. H. Bosih Rt 03 / rw 012 kelurahan wanasari kecamatan Cibitung Kabupaten Bekasi ( Tutup )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '304',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Batu Aji",
                    "branch_id" => 1,
                    "alamat" => "Komp. Ruko Cipta Karya Blok A No. 15 A Batu Aji, Batam",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3001',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Blimbing",
                    "branch_id" => 1,
                    "alamat" => "Jl. Tenaga 2 C  Kel. Blimbing Kec. Blimbing Kota Malang",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1108',
                    "nama_klinik" => "Klinik Pratama Nayaka Husada Cinangoh",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Tuparev No. 344 B & C, Cinangoh Kel. Karawang Wetan, Karawang Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '301',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Harapan Raya - Pekanbaru",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Imam Munandar No. 12 Harapan Raya, Kec. Bukit Raya Tangkerang Selatan Pekanbaru, Riau",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1301',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Cimahi",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Jend. Amir Machmud No. 477,  Rt. 01/06 Kel. Karangmekar, Cimahi Tengah, Cimahi Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2101',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Kalibanteng",
                    "branch_id" => 1,
                    "alamat" =>
                    "Ruko Siliwangi Plaza Blok D2 Jl. Jend. Sudirman No. 187 - 189 Semarang Jawa Tengah",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1901',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Krembangan",
                    "branch_id" => 1,
                    "alamat" => "Jl. Cendrawasih No. 28 B, Surabaya, Jawa Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '701',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Angkatan 45",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Angkatan 45 No. 99A Rt.42 Lorok Pakjo, Kec. Ilir Barat I, Palembang, Sumatera Selatan",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1101',
                    "nama_klinik" => "Klinik Pratama Nayaka Husada Margajaya",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Ir. H. Juanda Kav. 143 Ruko No. 9A - 10 Ruko Margajaya Bekasi, Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '401',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Plumbon",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Cirebon Bandung KM.11 Blok Kavling RT.006/RW.004 Ds. Karangmulya Kec. Plumbon Kab. Cirebon",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '302',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Balik Alam - Duri I",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Dewi Sartika No. 69 Rt. 02/08 Kel. Balik Alam, Kec. Mandau-Duri Kab. Bengkalis",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '702',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Basuki Rachmat",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Basuki Rahmat No. 1676, Rt.25 Kel. Pahlawan Kec. Kemuning, Palembang, Sumatera Selatan",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2102',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Desa Butuh",
                    "branch_id" => 1,
                    "alamat" =>
                    "Desa Butuh Kecamatan Mojosongo Kabupaten Boyolali Rt.001 Rw. 02, Boyolali, Jawa Tengah",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1102',
                    "nama_klinik" => "Klinik Pratama Nayaka Husada Mekar Mukti",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Industri Jl. Jababeka Raya, Mekarmukti, Kec. Cikarang Utara, Kabupaten Bekasi, Jawa Barat 17530",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1902',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Kebraun",
                    "branch_id" => 1,
                    "alamat" => "Jl. Raya Mastrip No. 396 Kebraon, Surabaya, Jawa Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1302',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Laksanamekar",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Batujajar Km. 1 Ruko R-6 Rt.10/03 Laksanamekar, Padalarang KBB",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3002',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Pakis",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Pakis Kembar No.174 A RT 05 RW 04 Kel. Pakis Kec. Pakis",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '402',
                    "nama_klinik" => "KLINIK NAYAKA HUSADA 02 SIDAKAYA",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Kompeni No. 25 Pipa Timur, Sidanegara, Cilacap, Jawa Tengah",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '305',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Tiban",
                    "branch_id" => 1,
                    "alamat" => "Komp. Pertokoan Cipta Puri Blok CC No. 17 Tiban, Batam",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1903',
                    "nama_klinik" => "Klinik Nayaka Husada 03 Asem Rowokali",
                    "branch_id" => 1,
                    "alamat" => "Jl. Asemrowo Kali No. 1 Surabaya, Jawa Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2103',
                    "nama_klinik" => "Klinik Nayaka Husada 03 Semarang",
                    "branch_id" => 1,
                    "alamat" => "Jl. Raya Kaligawe Km. 56 Semarang, Jawa Tengah",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1303',
                    "nama_klinik" => "Klinik Nayaka Husada 03 Luwigajah",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Baros Rt.001/004 Kel. Luwigajah Kec. Cimahi Selatan, Kota Cimahi Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '703',
                    "nama_klinik" => "Klinik Nayaka Husada 03 Sukabangun",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Sukabangun II A2 Rt. 006/02 Kel. Sukajaya, Kec. Sukarame, Palembang Sumatera Selatan",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1103',
                    "nama_klinik" => "Klinik Pratama Nayaka Husada 03 Sukaresmi",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Cibarusah Rt. 002/01No.2 desa Sukaresmi - Cikarang, Bekasi Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3003',
                    "nama_klinik" => "Klinik Nayaka Husada 03 Sukorejo",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Surabaya - Malang Km.52 Rt 03 Rw 05 Kel. Suwayuwo Kec. Sukorejo",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '303',
                    "nama_klinik" =>
                    "Klinik Nayaka Husada 03 Tambusai Batang Duri - Duri II",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Hang Tuah No. 387  Rt. 04/02 Kel. Tambusai Batang Dui, Kec. Mandau-Duri Kab. Bengkalis",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1904',
                    "nama_klinik" => "Klinik Nayaka Husada 04 Bungah",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Bungah - Sidayu Dsn. Nongkokerep, Kec. Bungah, Kab. Gresik, Jawa Timur ( TUTUP )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2104',
                    "nama_klinik" => "Klinik Nayaka Husada 04 Desa Batu",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Semarang - Demak Km. 17 Desa Wonokerto  Rt.01/03, Karang Tengah, Demak, Jawa Tengah",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '404',
                    "nama_klinik" => "Klinik Nayaka Husada 04 Kasokandel",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Cirebon - Bandung Ds. Kasokandel, Kab. Majalengka, Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1304',
                    "nama_klinik" => "Klinik Nayaka Husada 04 Ketapang",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Terusan Kopo Ketapang Km. 12,5 Ketapang, Kab. Bandung, Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3004',
                    "nama_klinik" => "Klinik Nayaka Husada 04 Semampir",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Letjen. S Parman Ruko No. 2 Rt.001/02 Kel. Semampir, Kec. Kraksaan,",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '306',
                    "nama_klinik" => "Klinik Nayaka Husada 04 Nagoya",
                    "branch_id" => 1,
                    "alamat" =>
                    "Komp. Pertokoan Tanjung Pantun Blok U No. 6 Jodoh Nagoya, Batam ( TUTUP )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '11',
                    "nama_klinik" => "nayaka 99",
                    "branch_id" => 1,
                    "alamat" => "bekasi",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '307',
                    "nama_klinik" => "Klinik Nayaka Husada 05 Baloi Permai",
                    "branch_id" => 1,
                    "alamat" =>
                    "Komplek pertokoan  Glory View II blok A no: 3A Kelurahan Baloi permai , Kec batam Kota  ( TUTUP )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2105',
                    "nama_klinik" => "Klinik Nayaka Husada 05 Demak",
                    "branch_id" => 1,
                    "alamat" => "Jl. Amarta No. 1 Mangunjiwan Demak",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1105',
                    "nama_klinik" => "Klinik Pratama Nayaka Husada 05 Cibarusah Kota",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Cibarusah Kp. Malaka Rt.002/006 Ds. Cibarusah Kota Kec. Cibarusah, Bekasi Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3005',
                    "nama_klinik" => "Klinik Nayaka Husada 05 Jabung",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Jabung Kemiri Rt 03 Rw 02 Kel.Jabung Kec. Jabung Kab. Malang, Jawa Timur ( TUTUP )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1305',
                    "nama_klinik" => "Klinik Nayaka Husada 05 Palasari",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Muhammad Toha Rt.02/06 Kp. Palasari, Pasawahan Kec. Dayeuh Kolot Kab. Bandung, Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1905',
                    "nama_klinik" => "Klinik Nayaka Husada 05 Wonorejo",
                    "branch_id" => 1,
                    "alamat" => "Jl. Kartini No. 130 - 132 Surabaya",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '405',
                    "nama_klinik" => "Klinik Nayaka Husada 05 Sumber Jaya",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Cirebon - Bandung Bongas Kidul - Majalengka (sebelah kantor kecamtan sumber jaya)",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '406',
                    "nama_klinik" => "Klinik Nayaka Husada 06 Balamoa",
                    "branch_id" => 1,
                    "alamat" => "Jl.  Pasar Raya Balamoa Kec. Tangkas Kab. Tegal",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1306',
                    "nama_klinik" => "Klinik Nayaka Husada 06 Citapen",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Kamp. Rancakuda Rt. 001/03 Ds. Citapen Kec. Cihampelas Kab. Bandung Barat Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1006',
                    "nama_klinik" => "Klinik Nayaka Husada 06 Jonggol",
                    "branch_id" => 1,
                    "alamat" => "Jl. Perum. Citra Indah Blok R3 /II Ruko Bukit Menteng",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '706',
                    "nama_klinik" => "Klinik Nayaka Husada 06 Makrayu",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Sultan Mansyur No. 42 Rt. 16 Kel. 32 Ilir, Kec. Ilir Barat II palembang  ( TUTUP)",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3006',
                    "nama_klinik" => "Klinik Nayaka Husada 06 Pandaan",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl Kali Tengah No 10 Desa Karang Jati Kecamatan Pandaan Kab. Pasuruan",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1106',
                    "nama_klinik" => "Klinik Nayaka Husada 06 Tambelang",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Kap. Wates Rt 002/ 003desa Suka Maju Kec. Tambelang Kab. Bekasi ( TUTUP )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1906',
                    "nama_klinik" => "Klinik Nayaka Husada 06 Ujungpangkah",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Daendles Desa Bolo, Kec. Ujungpangkah, Kab. Gresik, Jawa Timur ( TUTUP )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2106',
                    "nama_klinik" => "Klinik Nayaka Husada 06 Ungaran",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Jend. Sudirman No. 28 Mijen-Ungaran, Kabupaten Semarang, Jawa Tengah",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1907',
                    "nama_klinik" => "Klinik Nayaka Husada 07 Balongsari",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Darmo Indah Timur Blok G No. 64 C, Balongsari, Surabaya, Jawa Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2107',
                    "nama_klinik" => "Klinik Nayaka Husada 07 Karang Anyar",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Solo-Sragen Km.10 Ds. Dawung Rt.002/001, Kel.Kemiri, Kebakramat, Karanganyar, Jateng ( TUTUP)",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1309',
                    "nama_klinik" => "Klinik Nayaka Husada 09 Kertamulya",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Cihaliwung No. 181/10A Rt. 03/10 Ds. Kartamulya, Kec. Padalarang Kab. Bandung Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1310',
                    "nama_klinik" => "Klinik Nayaka Husada 10 Gadobangkong",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl Raya Gado Bangkong No 164 Rt 4/ Rw 4 Desa Gado Bangkong Kec. Ngamprah Kab. Bandung Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1915',
                    "nama_klinik" => "Klinik Nayaka Husada 15 Krikilan Gresik",
                    "branch_id" => 1,
                    "alamat" => "Jl. Raya Krikilan No. 259, Driyorejo, Gresik Jawa Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1924',
                    "nama_klinik" => "Klinik Nayaka Husada 24 Kartini Gresik",
                    "branch_id" => 1,
                    "alamat" => "JL. R.A. KARTINI 236/ C-9 GD. KARTINI BUILDING GRESIK",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1942',
                    "nama_klinik" => "Klinik Nayaka Husada 42 Rungkut",
                    "branch_id" => 1,
                    "alamat" => "Jl. Kali Rungkut No. 52 Surabaya, Jawa Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1961',
                    "nama_klinik" => "Klinik Nayaka Husada 61 Sepanjang",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Kalijaten Sepanjang Town House C-3 Sidoarjo, Jawa Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1004',
                    "nama_klinik" => "Klinik Nayaka Husada 04 Kampung Melayu",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Ruko 100 A3  Jl. Raya Salembaran Bisken Kampung Melayu Teluk Naga Tangerang",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1001',
                    "nama_klinik" => "Klinik Nayaka Husada 01 lubang buaya",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Pondok Gede Ruko Molek No. 2B Kel. Lubang Biaya Kec. Cipayung Jakarta Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1002',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Karet Semanggi",
                    "branch_id" => 1,
                    "alamat" =>
                    "Gd. BPJS Ketenagakerjaan Ground Floor Jl. Jend. Gatot Subroto Kav. 14 No.79 Jakarta Selatan",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1003',
                    "nama_klinik" => "Klinik  Nayaka Husada 03 Cakung",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Radjiman No.26 Rt.08/10 Kel. Jatinegara Kec. Cakung Jakarta Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1007',
                    "nama_klinik" => "Klinik  Nayaka Husada 07 Cengkareng",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Daan Mogot Raya Km. 14 No. 6 F Rt.001/02 Kel. Cengkareng Barat, Kec. Cengkareng Kotip Jakbar",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1008',
                    "nama_klinik" => "Klinik Nayaka Husada 08 Sepatan",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Mauk Km. 11 No 88 A Rt 01 Rw 01 Kp Duku Kel. Sepatan Kec. Sepatan Tangerang  ( TUTUP)",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1009',
                    "nama_klinik" => "Klinik Nayaka Husada 09 Klender",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Bekasi Timur Rt. 005/01 Kel. Jatinegara Kaum, Pulogadung, Jakarta Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '308',
                    "nama_klinik" => "IHC Pulau Bulan - Btm",
                    "branch_id" => 1,
                    "alamat" => "Pulau Bulan Batam ( TUTUP )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '707',
                    "nama_klinik" => "Klinik Nayaka Husada 07 Pangkalan lampam",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Pangkalan Lampam Desa Pangkalan Lampam Kec Pangkalan Lampam  Kab. Ogan Komering Ilir",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2111',
                    "nama_klinik" => "IHC GLORY",
                    "branch_id" => 1,
                    "alamat" => "TUTUP",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1011',
                    "nama_klinik" => "IHC Yamaha Indonesia",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Rawa Gelam I/5 Kawasan Industri PuloGadung, Jakarta Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1012',
                    "nama_klinik" => "IHC Yamaha Musik Indonesia",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Pulo Buaran Raya No.1 kawasan Indutr iPuloGadung , Jakarta Timur",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1013',
                    "nama_klinik" => "IHC Sneider",
                    "branch_id" => 1,
                    "alamat" => "Jakarta ( TUTUP )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1104',
                    "nama_klinik" => "Klinik Pratama Nayaka Husada  Teluk Pucung",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Perjuangan No. 99 RT. 003/001 Kel. Teluk Pucung Kec. Bekasi Utara, Bekasi Jawa Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1111',
                    "nama_klinik" => "IHC Cipta Mortar Utama",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Sumbawa No.1, Gandasari, Kec. Cikarang Bar., Bekasi, Jawa Barat 17530",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1112',
                    "nama_klinik" => "IHC PT Sinar Sosro KPB Tambun",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Diponegoro No.59, Jatimulya, Kec. Tambun Sel., Bekasi, Jawa Barat 17510",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1113',
                    "nama_klinik" => "IHC HMS Karawang (BKS)",
                    "branch_id" => 1,
                    "alamat" =>
                    "Karawang International Industrial City, Jalan Permata Dua Lot B3, BB4B, BB7, BB8A, Puseurjaya, Kec. Telukjambe Tim., Kabupaten Karawang, Jawa Barat 41361",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1311',
                    "nama_klinik" => "IHC Chitose Cimahi",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Industri III No. 5 Leuwigajah  Cimahi, Jawa Barat - Indonesia 40533",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1312',
                    "nama_klinik" => "IHC Leuwilitex",
                    "branch_id" => 1,
                    "alamat" => "Cimahi",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1313',
                    "nama_klinik" => "IHC Kencana",
                    "branch_id" => 1,
                    "alamat" => "non aktif",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1911',
                    "nama_klinik" => "IHC Rungkut I",
                    "branch_id" => 1,
                    "alamat" => "Jl. Rungkut Industri Raya No. 18  Surabaya 60293,",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1912',
                    "nama_klinik" => "IHC Rungkut II",
                    "branch_id" => 1,
                    "alamat" => "Jl. Kali Rungkut 11, Surabaya",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1913',
                    "nama_klinik" => "IHC Taman Sampurna",
                    "branch_id" => 1,
                    "alamat" => "Jl. Taman Sampoerna No. 6 Surabaya ( Tutup )",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3007',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Cilinaya NTB",
                    "branch_id" => 1,
                    "alamat" =>
                    "Komplek Ruko Mataram Mall Blok B / 20 Jl. Cilinaya Cakranegara, Nusa Tenggara Barat",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3008',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Mataram",
                    "branch_id" => 1,
                    "alamat" => "Jl. AA Gede Ngurah No 67 Abiantubuh Kec Sandubaya Mataram",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3011',
                    "nama_klinik" => "IHC Malang",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Letjend S. Parman No.44, Purwantoro, Kec. Blimbing, Kota Malang, Jawa Timur 65126",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3012',
                    "nama_klinik" => "IHC Sukorejo",
                    "branch_id" => 1,
                    "alamat" => "Raya Surabaya Malang Km 51.4  Kec. Sukorejo, Pasuruan",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '3013',
                    "nama_klinik" => "IHC Probolinggo",
                    "branch_id" => 1,
                    "alamat" => "Jl. PB. Sudirman No. 17 Krakaan Kab. Probolinggo",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1114',
                    "nama_klinik" => "IHC HUNG A INDONESIA",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jalan Raya Inti II Blok C5, Cibatu, Cikarang Selatan, Cibatu, Cikarang Sel., Bekasi, Jawa Barat 17530",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2112',
                    "nama_klinik" => "IHC PT. LUCKY TEXTILE SEMARANG",
                    "branch_id" => 1,
                    "alamat" =>
                    "8 blok a 07 10, Jalan Coaster, Tj. Mas, Kec. Semarang Utara, Kota Semarang, Jawa Tengah 50174",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1010',
                    "nama_klinik" => "Instalasi Farmasi KNH 02 Semanggi",
                    "branch_id" => 1,
                    "alamat" => "KARET SEMANGGI",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1314',
                    "nama_klinik" => "IHC Ateja Multi",
                    "branch_id" => 1,
                    "alamat" => "cimahi",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1315',
                    "nama_klinik" => "IHC Ateja Batujajar",
                    "branch_id" => 1,
                    "alamat" => "batujajar cimahi",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1914',
                    "nama_klinik" => "IHC HMS Karawang (SBY) Nonaktif",
                    "branch_id" => 1,
                    "alamat" =>
                    "Karawang International Industrial City, Jalan Permata Dua Lot B3, BB4B, BB7, BB8A, Puseurjaya, Kec. Telukjambe Tim., Kabupaten Karawang, Jawa Barat 41361",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '309',
                    "nama_klinik" => "Klinik Nayaka Husada 01 sunggal - Medan-Pku",
                    "branch_id" => 1,
                    "alamat" => "Medan Sunggal",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '411',
                    "nama_klinik" => "IHC PT. SHOETOWN FOOTWEAR",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Kasokandel KM 45  Desa Kasokandel  Kabupaten Majalengka",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1014',
                    "nama_klinik" => "Klinik Plaza BP Jamsostek",
                    "branch_id" => 1,
                    "alamat" => "Plaza Jamsostek",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '708',
                    "nama_klinik" => "Klinik Nayaka Husada Way Kandis - Tanjung Senang",
                    "branch_id" => 1,
                    "alamat" => "Jl. Ratu Dibalau No. 68 E, Tanjung Senang  Bandar Lampung",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1115',
                    "nama_klinik" => "IHC PT. Softex Indonesia",
                    "branch_id" => 1,
                    "alamat" => "Bekasi",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2113',
                    "nama_klinik" => "IHC CAHAYA GUNUNG FOOD",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. sudimoro - pengging, Dusun I, Sudimoro, kec. Teras, Kab. Boyolali- jateng",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1116',
                    "nama_klinik" => "IHC PT. SINAR SOSRO KPB CIBITUMG",
                    "branch_id" => 1,
                    "alamat" => "Cibitung",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1015',
                    "nama_klinik" => "Instalasi Farmasi Klinik Plaza BP Jamsostek",
                    "branch_id" => 1,
                    "alamat" => "BP PLaza Jamsostek - Jakarta",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1016',
                    "nama_klinik" => "Klinik Nayaka Husada Kedung Halang",
                    "branch_id" => 1,
                    "alamat" => "Kedung Halang - Bogor",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2114',
                    "nama_klinik" => "IHC CIPTA MORTAR UTAMA",
                    "branch_id" => 1,
                    "alamat" =>
                    "Kawasan Industri Wijaya Kusuma Jl. Tugu VII Wijaya No. 8  Kelurahan Randugarut, Kec. Tugu Kota Semarang",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '709',
                    "nama_klinik" => "Klinik Nayaka Husada 01 Batu Aji - plb",
                    "branch_id" => 1,
                    "alamat" => "Komp. Ruko Cipta Karya Blok A No. 15 A Batu Aji, Batam",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '710',
                    "nama_klinik" => "Klinik Nayaka Husada 02 Tiban - plb",
                    "branch_id" => 1,
                    "alamat" => "Komp. Pertokoan Cipta Puri Blok CC No. 17 Tiban, Batam",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2115',
                    "nama_klinik" => "IHC PT Lucky Textile Semarang II",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Kawasan Industri Wijaya Kusuma No.141, Randu Garut, Kec. Tugu, Kota Semarang, Jawa Tengah 50181, Indonesia",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1117',
                    "nama_klinik" => "IHC PT CMU Jawilan",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Raya Cikande Rangkasbitung, Cemplang, Kec. Jawilan, Kabupaten Serang, Banten 42177",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1118',
                    "nama_klinik" => "IHC. PT. Sayap Mas Utama (SMU) 1",
                    "branch_id" => 1,
                    "alamat" => "Jl. Tipar cakung Kav. F 5 - 7, Jakarta Timur (Plant 1)",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1119',
                    "nama_klinik" => "IHC PT. Sayap Mas Utama (SMU) 2",
                    "branch_id" => 1,
                    "alamat" => "Jl. Tipar cakung Kav. F 5 - 7, Jakarta Timur (plant 2)",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1120',
                    "nama_klinik" => "IHC PT. UNIPLASTINDO INTERBUANA",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Imam Bonjol KM 44 Kelurahan Telaga Asih, Kec. Cikarang Barat, Kabupaten Bekasi, Jawa Barat.",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1121',
                    "nama_klinik" => "IHC PT. HON CHUAN INDONESIA",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Kenari Raya blok G2 No. 1 & 17, Kawasan Industri Delta Silicon V, Lippo Cikarang, Bekasi 17530",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1122',
                    "nama_klinik" => "IHC PT. Sharp Electronics Indonesia",
                    "branch_id" => 1,
                    "alamat" =>
                    "Jl. Harapan Raya Lot LL 1 & 2, Kawasan Industri KIIC, Desa Sirnabaya � Teluk Jambe Timur, Karawang � Jawa Barat,�Indonesia",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '2116',
                    "nama_klinik" => "IHC PT. SITOY LEATHER PRODUCT INDONESIA",
                    "branch_id" => 1,
                    "alamat" =>
                    "KAWASAN INDUSTRI KENDAL. JL. MANDURA NO.2 KENDAL�JAWA�TENGAH",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '1964',
                    "nama_klinik" => "DPP Bojonegoro",
                    "branch_id" => 1,
                    "alamat" => "Ds Karang dayu rt.001/003 Kec. Baureno Kab.�Bojonegoro",
                ],
                [
                    "created_by" => 1,
                    "kota" => "bandung",
                    "kode" => '712',
                    "nama_klinik" => "Klinik Nayaka Husada Kabil - Batu Besar",
                    "branch_id" => 1,
                    "alamat" => "Batam",
                ],
            ]
        );
    }
}
