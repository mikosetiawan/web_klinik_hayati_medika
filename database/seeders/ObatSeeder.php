<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_obat' => 'Paracetamol', 'deskripsi' => 'Obat pereda demam dan nyeri', 'dosis' => '500 mg', 'harga' => 5000],
            ['nama_obat' => 'Amoxicillin', 'deskripsi' => 'Antibiotik untuk infeksi bakteri', 'dosis' => '250 mg', 'harga' => 10000],
            ['nama_obat' => 'Ibuprofen', 'deskripsi' => 'Obat anti inflamasi non steroid', 'dosis' => '400 mg', 'harga' => 8000],
            ['nama_obat' => 'Cetirizine', 'deskripsi' => 'Obat alergi', 'dosis' => '10 mg', 'harga' => 7000],
            ['nama_obat' => 'Omeprazole', 'deskripsi' => 'Obat untuk asam lambung', 'dosis' => '20 mg', 'harga' => 9000],
            ['nama_obat' => 'Metformin', 'deskripsi' => 'Obat untuk diabetes', 'dosis' => '500 mg', 'harga' => 11000],
            ['nama_obat' => 'Salbutamol', 'deskripsi' => 'Obat untuk asma', 'dosis' => '2 mg', 'harga' => 9500],
            ['nama_obat' => 'Loperamide', 'deskripsi' => 'Obat diare', 'dosis' => '2 mg', 'harga' => 4000],
            ['nama_obat' => 'Amlodipine', 'deskripsi' => 'Obat tekanan darah tinggi', 'dosis' => '5 mg', 'harga' => 7500],
            ['nama_obat' => 'Simvastatin', 'deskripsi' => 'Obat penurun kolesterol', 'dosis' => '10 mg', 'harga' => 8500],
        ];

        foreach ($data as $item) {
            Obat::create($item);
        }
    }
}
