<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource with filters
     */
    public function index(Request $request)
    {
        try {
            $query = Book::query();

            // Filter by kategori
            if ($request->has('kategori') && $request->kategori != 'all') {
                $query->where('kategori', $request->kategori);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Search by judul atau pengarang
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('pengarang', 'like', "%{$search}%");
                });
            }

            // Sort by field
            $sortField = $request->get('sort_field', 'id');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortField, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $books = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $books->items(),
                'meta' => [
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage(),
                    'per_page' => $books->perPage(),
                    'total' => $books->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah_halaman' => 'required|integer|min:1',
            'kategori' => 'required|in:Fiksi,Non-Fiksi,Komik,all',
            'isbn' => 'required|string|unique:books,isbn',
            'status' => 'sometimes|in:Tersedia,Dipinjam'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $book = Book::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $book
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan buku',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => $book
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'sometimes|string|max:255',
            'pengarang' => 'sometimes|string|max:255',
            'penerbit' => 'sometimes|string|max:255',
            'tahun_terbit' => 'sometimes|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah_halaman' => 'sometimes|integer|min:1',
            'kategori' => 'sometimes|in:Fiksi,Non-Fiksi,Komik,all',
            'isbn' => 'sometimes|string|unique:books,isbn,' . $id,
            'status' => 'sometimes|in:Tersedia,Dipinjam'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $book->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil diupdate',
                'data' => $book
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate buku',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }

            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus buku',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update book status
     */
    public function updateStatus(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Tersedia,Dipinjam'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $book->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status buku berhasil diupdate',
                'data' => $book
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status buku',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get books by category
     */
    public function getByCategory($category)
    {
        try {
            $books = Book::where('kategori', $category)->get();

            return response()->json([
                'success' => true,
                'data' => $books,
                'meta' => [
                    'total' => $books->count(),
                    'category' => $category
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get books statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            // Check if books table exists
            if (!\Schema::hasTable('books')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tabel books tidak ditemukan. Jalankan migration terlebih dahulu.'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $totalBooks = Book::count();
            $availableBooks = Book::where('status', 'Tersedia')->count();
            $borrowedBooks = Book::where('status', 'Dipinjam')->count();
            
            // Get categories count with safe query
            $categories = Book::select('kategori')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('kategori')
                ->get()
                ->map(function($item) {
                    return [
                        'kategori' => $item->kategori,
                        'count' => $item->count
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'total_books' => $totalBooks,
                    'available_books' => $availableBooks,
                    'borrowed_books' => $borrowedBooks,
                    'categories' => $categories
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Statistics Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}