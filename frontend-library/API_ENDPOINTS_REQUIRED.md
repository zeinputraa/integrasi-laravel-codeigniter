# API Endpoints Required untuk Laravel Backend

CodeIgniter Frontend Anda sekarang terkonfigurasi untuk menggunakan API Laravel. Berikut adalah endpoint yang HARUS ada di backend Laravel Anda:

## Base URL
```
http://localhost:8000/api/
```

## Required Endpoints

### 1. GET /api/books
**Deskripsi:** Ambil daftar semua buku dengan filter dan pagination

**Query Parameters:**
- `search` (optional): Cari berdasarkan judul, pengarang, atau ISBN
- `kategori` (optional): Filter berdasarkan kategori
- `status` (optional): Filter berdasarkan status (Tersedia/Dipinjam)
- `per_page` (optional, default: 10): Jumlah data per halaman
- `page` (optional, default: 1): Halaman ke berapa

**Response Example:**
```json
{
  "data": [
    {
      "id": 1,
      "judul": "Clean Code",
      "pengarang": "Robert C. Martin",
      "penerbit": "Prentice Hall",
      "tahun_terbit": 2008,
      "jumlah_halaman": 464,
      "kategori": "Non-Fiksi",
      "isbn": "9780132350884",
      "status": "Tersedia",
      "created_at": "2024-11-16T10:00:00Z",
      "updated_at": "2024-11-16T10:00:00Z"
    }
  ],
  "meta": {
    "total": 50,
    "per_page": 10,
    "current_page": 1,
    "total_pages": 5
  }
}
```

---

### 2. GET /api/books/:id
**Deskripsi:** Ambil detail buku berdasarkan ID

**URL Parameters:**
- `id` (required): ID buku

**Response Example:**
```json
{
  "data": {
    "id": 1,
    "judul": "Clean Code",
    "pengarang": "Robert C. Martin",
    "penerbit": "Prentice Hall",
    "tahun_terbit": 2008,
    "jumlah_halaman": 464,
    "kategori": "Non-Fiksi",
    "isbn": "9780132350884",
    "status": "Tersedia",
    "created_at": "2024-11-16T10:00:00Z",
    "updated_at": "2024-11-16T10:00:00Z"
  }
}
```

---

### 3. POST /api/books
**Deskripsi:** Buat buku baru

**Request Body:**
```json
{
  "judul": "Clean Code",
  "pengarang": "Robert C. Martin",
  "penerbit": "Prentice Hall",
  "tahun_terbit": 2008,
  "jumlah_halaman": 464,
  "kategori": "Non-Fiksi",
  "isbn": "9780132350884",
  "status": "Tersedia"
}
```

**Response Example:**
```json
{
  "success": true,
  "message": "Buku berhasil ditambahkan",
  "data": {
    "id": 1,
    "judul": "Clean Code",
    "pengarang": "Robert C. Martin",
    "penerbit": "Prentice Hall",
    "tahun_terbit": 2008,
    "jumlah_halaman": 464,
    "kategori": "Non-Fiksi",
    "isbn": "9780132350884",
    "status": "Tersedia",
    "created_at": "2024-11-16T10:00:00Z",
    "updated_at": "2024-11-16T10:00:00Z"
  }
}
```

---

### 4. PUT /api/books/:id
**Deskripsi:** Update data buku

**URL Parameters:**
- `id` (required): ID buku

**Request Body:**
```json
{
  "judul": "Clean Code (Updated)",
  "pengarang": "Robert C. Martin",
  "penerbit": "Prentice Hall",
  "tahun_terbit": 2008,
  "jumlah_halaman": 464,
  "kategori": "Non-Fiksi",
  "isbn": "9780132350884",
  "status": "Tersedia"
}
```

**Response Example:**
```json
{
  "success": true,
  "message": "Buku berhasil diupdate",
  "data": {
    "id": 1,
    "judul": "Clean Code (Updated)",
    ...
  }
}
```

---

### 5. DELETE /api/books/:id
**Deskripsi:** Hapus buku

**URL Parameters:**
- `id` (required): ID buku

**Response Example:**
```json
{
  "success": true,
  "message": "Buku berhasil dihapus"
}
```

---

### 6. PUT /api/books/:id/status
**Deskripsi:** Update status buku (Tersedia/Dipinjam)

**URL Parameters:**
- `id` (required): ID buku

**Request Body:**
```json
{
  "status": "Dipinjam"
}
```

**Response Example:**
```json
{
  "success": true,
  "message": "Status buku berhasil diupdate"
}
```

---

### 7. GET /api/books/statistics
**Deskripsi:** Ambil statistik dashboard

**Response Example:**
```json
{
  "success": true,
  "data": {
    "total_books": 50,
    "available_books": 35,
    "borrowed_books": 15,
    "books_by_category": [
      {
        "kategori": "Fiksi",
        "count": 20
      },
      {
        "kategori": "Non-Fiksi",
        "count": 30
      }
    ],
    "recent_books": [
      {
        "id": 50,
        "judul": "Book Title",
        ...
      }
    ]
  }
}
```

---

## Error Response Format

Semua endpoint harus mengembalikan error dalam format ini:

```json
{
  "success": false,
  "message": "Deskripsi error",
  "errors": {}
}
```

---

## Validasi

Validasi data buku yang harus dilakukan di backend:
- `judul`: Required, min 3 chars, max 255
- `pengarang`: Required, min 3 chars, max 255
- `penerbit`: Required, min 3 chars, max 255
- `tahun_terbit`: Required, numeric (4 digit)
- `jumlah_halaman`: Required, numeric
- `kategori`: Required, in list: Fiksi, Non-Fiksi, Komik
- `isbn`: Required, min 10, max 20, unique
- `status`: Required, in list: Tersedia, Dipinjam

---

## CORS Configuration

Pastikan Laravel Anda mengizinkan CORS dari frontend CodeIgniter:
- Frontend: `http://localhost:8080`
- Backend: `http://localhost:8000`

Di Laravel, tambahkan ke `config/cors.php`:
```php
'allowed_origins' => ['http://localhost:8080'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => false,
```

---

## Testing dengan cURL

```bash
# GET all books
curl -X GET "http://localhost:8000/api/books?per_page=10&page=1"

# GET single book
curl -X GET "http://localhost:8000/api/books/1"

# POST create book
curl -X POST "http://localhost:8000/api/books" \
  -H "Content-Type: application/json" \
  -d '{
    "judul": "Clean Code",
    "pengarang": "Robert C. Martin",
    "penerbit": "Prentice Hall",
    "tahun_terbit": 2008,
    "jumlah_halaman": 464,
    "kategori": "Non-Fiksi",
    "isbn": "9780132350884",
    "status": "Tersedia"
  }'

# PUT update book
curl -X PUT "http://localhost:8000/api/books/1" \
  -H "Content-Type: application/json" \
  -d '{"status": "Dipinjam"}'

# DELETE book
curl -X DELETE "http://localhost:8000/api/books/1"

# GET statistics
curl -X GET "http://localhost:8000/api/books/statistics"
```
