<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Clear table first
        DB::table('books')->truncate();

        $books = [
            [
                'judul' => 'Laskar Pelangi',
                'pengarang' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'jumlah_halaman' => 529,
                'kategori' => 'Fiksi',
                'isbn' => '979-3062-79-7',
                'status' => 'Tersedia',
            ],
            [
                'judul' => 'Filosofi Teras',
                'pengarang' => 'Henry Manampiring',
                'penerbit' => 'Kompas',
                'tahun_terbit' => 2018,
                'jumlah_halaman' => 346,
                'kategori' => 'Non-Fiksi',
                'isbn' => '978-602-412-518-9',
                'status' => 'Dipinjam',
            ],
            [
                'judul' => 'Dilan 1990',
                'pengarang' => 'Pidi Baiq',
                'penerbit' => 'Pastel Books',
                'tahun_terbit' => 2014,
                'jumlah_halaman' => 332,
                'kategori' => 'Fiksi',
                'isbn' => '978-602-7888-71-5',
                'status' => 'Tersedia',
            ],
            [
                'judul' => 'Si Juki',
                'pengarang' => 'Faza Meonk',
                'penerbit' => 'Bukune',
                'tahun_terbit' => 2013,
                'jumlah_halaman' => 128,
                'kategori' => 'Komik',
                'isbn' => '978-602-220-551-6',
                'status' => 'Dipinjam',
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}