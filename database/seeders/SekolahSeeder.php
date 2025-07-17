<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $response = Http::get("https://api-sekolah-indonesia.vercel.app/sekolah/SMK?page=1&perPage=1000");
        $data = $response->json();
        foreach ($data["dataSekolah"] as $item) {
            Sekolah::create([
                "nama_sekolah" => $item["sekolah"],
                "npsn" => $item["npsn"],
                "provinsi" => $item["propinsi"],
                "alamat" => $item["kabupaten_kota"]." ".$item["kecamatan"]." ".$item["alamat_jalan"]
            ]);
         }
    }
}
