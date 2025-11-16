<?php

namespace App\Controllers;

use App\Services\BookApiService;

class BookController extends BaseController
{
    protected $apiService;
    protected $validation;

    public function __construct()
    {
        $this->apiService = new BookApiService();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Display all books
     */
    public function index()
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'kategori' => $this->request->getGet('kategori'),
            'status' => $this->request->getGet('status'),
            'per_page' => $this->request->getGet('per_page') ?? 10,
            'page' => $this->request->getGet('page') ?? 1
        ];

        $response = $this->apiService->getAllBooks($filters);

        if (!$response['success']) {
            return redirect()->back()->with('error', $response['message'] ?? 'Gagal mengambil data buku');
        }

        $data = [
            'title' => 'Manajemen Buku',
            'books' => $response['data'],
            'pagination' => $response['meta'] ?? null,
            'filters' => $filters
        ];

        return view('books/index', $data);
    }

    /**
     * Show create book form
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Buku Baru',
            'validation' => $this->validation
        ];

        return view('books/create', $data);
    }

    /**
     * Store new book
     */
    public function store()
    {
        $rules = [
            'judul' => 'required|min_length[3]|max_length[255]',
            'pengarang' => 'required|min_length[3]|max_length[255]',
            'penerbit' => 'required|min_length[3]|max_length[255]',
            'tahun_terbit' => 'required|exact_length[4]|numeric',
            'jumlah_halaman' => 'required|numeric',
            'kategori' => 'required|in_list[Fiksi,Non-Fiksi,Komik,all]',
            'isbn' => 'required|min_length[10]|max_length[20]',
            'status' => 'required|in_list[Tersedia,Dipinjam]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bookData = $this->request->getPost();
        $response = $this->apiService->createBook($bookData);

        if ($response['success']) {
            return redirect()->to('/books')->with('success', 'Buku berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', $response['message'] ?? 'Gagal menambahkan buku');
        }
    }

    /**
     * Show edit book form
     */
    public function edit($id)
    {
        $response = $this->apiService->getBookById($id);

        if (!$response['success']) {
            return redirect()->to('/books')->with('error', $response['message'] ?? 'Buku tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Buku',
            'book' => $response['data'],
            'validation' => $this->validation
        ];

        return view('books/edit', $data);
    }

    /**
     * Update book
     */
    public function update($id)
    {
        $rules = [
            'judul' => 'required|min_length[3]|max_length[255]',
            'pengarang' => 'required|min_length[3]|max_length[255]',
            'penerbit' => 'required|min_length[3]|max_length[255]',
            'tahun_terbit' => 'required|exact_length[4]|numeric',
            'jumlah_halaman' => 'required|numeric',
            'kategori' => 'required|in_list[Fiksi,Non-Fiksi,Komik,all]',
            'isbn' => 'required|min_length[10]|max_length[20]',
            'status' => 'required|in_list[Tersedia,Dipinjam]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bookData = $this->request->getPost();
        $response = $this->apiService->updateBook($id, $bookData);

        if ($response['success']) {
            return redirect()->to('/books')->with('success', 'Buku berhasil diupdate!');
        } else {
            return redirect()->back()->withInput()->with('error', $response['message'] ?? 'Gagal mengupdate buku');
        }
    }

    /**
     * Delete book
     */
    public function delete($id)
    {
        $response = $this->apiService->deleteBook($id);

        if ($response['success']) {
            return redirect()->to('/books')->with('success', 'Buku berhasil dihapus!');
        } else {
            return redirect()->to('/books')->with('error', $response['message'] ?? 'Gagal menghapus buku');
        }
    }

    /**
     * Update book status
     */
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $response = $this->apiService->updateBookStatus($id, $status);

        if ($response['success']) {
            return redirect()->back()->with('success', 'Status buku berhasil diupdate!');
        } else {
            return redirect()->back()->with('error', $response['message'] ?? 'Gagal mengupdate status buku');
        }
    }

    /**
     * Show dashboard with statistics
     */
    public function dashboard()
    {
        $statsResponse = $this->apiService->getStatistics();
        $booksResponse = $this->apiService->getAllBooks(['per_page' => 5]);

        $data = [
            'title' => 'Dashboard Perpustakaan',
            'stats' => $statsResponse['success'] ? $statsResponse['data'] : null,
            'recentBooks' => $booksResponse['success'] ? $booksResponse['data'] : [],
            'statsError' => !$statsResponse['success'] ? $statsResponse['message'] : null
        ];

        return view('books/dashboard', $data);
    }

    public function statistics()
    {
        $response = $this->apiService->getStatistics();
        
        return $this->response->setJSON($response);
    }
}