<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Database\Seeders\JurusanSeeder;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([JurusanSeeder::class]);
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
