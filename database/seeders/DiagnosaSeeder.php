<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnosa;

class DiagnosaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_diagnosa' => 'D001', 'nama_diagnosa' => 'Demam Berdarah', 'deskripsi' => 'Infeksi virus yang disebarkan oleh nyamuk', 'harga' => 150000],
            ['kode_diagnosa' => 'D002', 'nama_diagnosa' => 'Tipes', 'deskripsi' => 'Infeksi bakteri salmonella', 'harga' => 130000],
            ['kode_diagnosa' => 'D003', 'nama_diagnosa' => 'Asma', 'deskripsi' => 'Penyakit pernapasan kronis', 'harga' => 140000],
            ['kode_diagnosa' => 'D004', 'nama_diagnosa' => 'Maag', 'deskripsi' => 'Gangguan lambung akibat asam', 'harga' => 80000],
            ['kode_diagnosa' => 'D005', 'nama_diagnosa' => 'Diabetes Mellitus', 'deskripsi' => 'Penyakit kronis yang memengaruhi gula darah', 'harga' => 200000],
            ['kode_diagnosa' => 'D006', 'nama_diagnosa' => 'Hipertensi', 'deskripsi' => 'Tekanan darah tinggi', 'harga' => 120000],
            ['kode_diagnosa' => 'D007', 'nama_diagnosa' => 'ISPA', 'deskripsi' => 'Infeksi saluran pernapasan atas', 'harga' => 90000],
            ['kode_diagnosa' => 'D008', 'nama_diagnosa' => 'Kolesterol Tinggi', 'deskripsi' => 'Kadar kolesterol darah tinggi', 'harga' => 110000],
            ['kode_diagnosa' => 'D009', 'nama_diagnosa' => 'Migrain', 'deskripsi' => 'Sakit kepala kronis sebelah', 'harga' => 85000],
            ['kode_diagnosa' => 'D010', 'nama_diagnosa' => 'Infeksi Saluran Kemih', 'deskripsi' => 'Infeksi bakteri pada saluran kemih', 'harga' => 95000],
        ];

        foreach ($data as $item) {
            Diagnosa::create($item);
        }
    }
}
