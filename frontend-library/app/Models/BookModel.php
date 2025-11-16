<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['judul', 'pengarang', 'penerbit', 'tahun_terbit', 'jumlah_halaman', 'kategori', 'isbn', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all books with filters and pagination
     */
    public function getAllBooks($filters = [])
    {
        try {
            $query = $this;

            // Apply search filter
            if (!empty($filters['search'])) {
                $query = $query->like('judul', $filters['search'])
                               ->orLike('pengarang', $filters['search'])
                               ->orLike('isbn', $filters['search']);
            }

            // Apply category filter
            if (!empty($filters['kategori']) && $filters['kategori'] !== 'all') {
                $query = $query->where('kategori', $filters['kategori']);
            }

            // Apply status filter
            if (!empty($filters['status'])) {
                $query = $query->where('status', $filters['status']);
            }

            // Get per_page value or default to 10
            $perPage = $filters['per_page'] ?? 10;

            // Get paginated results
            $page = $filters['page'] ?? 1;
            $offset = ($page - 1) * $perPage;

            $total = $query->countAllResults(false);
            $books = $query->limit($perPage, $offset)->findAll();

            return [
                'success' => true,
                'data' => $books,
                'meta' => [
                    'total' => $total,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'total_pages' => ceil($total / $perPage)
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data buku: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get book by ID
     */
    public function getBookById($id)
    {
        try {
            $book = $this->find($id);

            if (!$book) {
                return [
                    'success' => false,
                    'message' => 'Buku tidak ditemukan',
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'data' => $book
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data buku: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Create new book
     */
    public function createBook($data)
    {
        try {
            $this->insert($data);

            return [
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'id' => $this->insertID()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan buku: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update book
     */
    public function updateBook($id, $data)
    {
        try {
            $this->update($id, $data);

            return [
                'success' => true,
                'message' => 'Buku berhasil diupdate'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate buku: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete book
     */
    public function deleteBook($id)
    {
        try {
            $this->delete($id);

            return [
                'success' => true,
                'message' => 'Buku berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus buku: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update book status
     */
    public function updateBookStatus($id, $status)
    {
        try {
            $this->update($id, ['status' => $status]);

            return [
                'success' => true,
                'message' => 'Status buku berhasil diupdate'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate status buku: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        try {
            $db = \Config\Database::connect();

            $totalBooks = $this->countAll();
            $availableBooks = $this->where('status', 'Tersedia')->countAllResults();
            $borrowedBooks = $this->where('status', 'Dipinjam')->countAllResults();

            // Get books by category
            $booksByCategory = $db->table('books')
                ->select('kategori, COUNT(*) as count')
                ->groupBy('kategori')
                ->get()
                ->getResultArray();

            // Get most recent books
            $recentBooks = $this->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll();

            return [
                'success' => true,
                'data' => [
                    'total_books' => $totalBooks,
                    'available_books' => $availableBooks,
                    'borrowed_books' => $borrowedBooks,
                    'books_by_category' => $booksByCategory,
                    'recent_books' => $recentBooks
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}
