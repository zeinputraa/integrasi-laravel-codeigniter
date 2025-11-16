# Arsitektur CodeIgniter + Laravel Integration

## Overview Sistem

**USER BROWSER**  
Mengakses: `http://localhost:8080`

**CODEIGNITER FRONTEND** (Port 8080)  
- **Routes**: Menangani routing untuk buku dan dashboard
- **BookController**: Validasi input, memanggil BookApiService, return views
- **BookApiService**: Service layer untuk komunikasi dengan API Laravel menggunakan GuzzleHTTP
- **Views**: Template untuk menampilkan data (index, create, edit, dashboard)

**LARAVEL BACKEND API** (Port 8000)  
- **API Routes**: Endpoint REST API untuk operasi CRUD buku
- **BookController**: Validasi request, business logic, return JSON response
- **BookModel/Repository**: Handle database operations, validation, relationships
- **Middleware**: CORS handling, authentication, rate limiting

**MYSQL DATABASE** (Port 3306)  
Database: `library_management` dengan tabel `books` yang berisi field lengkap untuk manajemen buku.

---

## Alur Data: Get All Books

1. **User** klik "Daftar Buku" → Browser: GET `http://localhost:8080/books`

2. **CodeIgniter Router** arahkan ke `BookController::index()`

3. **BookController** panggil `$this->apiService->getAllBooks($filters)`

4. **BookApiService** buat HTTP request: GET `http://localhost:8000/api/books?search=...&kategori=...`

5. **Laravel API Route** tangani request dan panggil `BookController::index()`

6. **Laravel BookController** query database dan return JSON response dengan data buku dan metadata pagination

7. **Laravel** kirim response JSON kembali ke CodeIgniter

8. **BookApiService** parse JSON response dan return formatted array

9. **BookController** render view `books/index` dengan data yang diterima

10. **Browser** render halaman dengan daftar buku

---

## Alur Data: Create Book

1. **User** submit form di `http://localhost:8080/books/create` → Browser: POST `http://localhost:8080/books`

2. **CodeIgniter Router** arahkan ke `BookController::store()`

3. **BookController** validasi input, jika valid panggil `$this->apiService->createBook($bookData)`

4. **BookApiService** buat HTTP request: POST `http://localhost:8000/api/books` dengan payload JSON data buku

5. **Laravel API Route** tangani request dan panggil `BookController::store()`

6. **Laravel BookController** validasi input, jika valid buat record baru di database

7. **Laravel** return JSON response dengan status success dan data buku yang dibuat

8. **BookApiService** terima dan parse response

9. **BookController** redirect ke `/books` dengan success message (atau kembali ke form dengan error message)

10. **Browser** navigasi ke books list atau tampilkan error di form

---

## Error Handling Flow

**Exception/Error terjadi** di BookApiService (network error, timeout, API server down, HTTP error)

**BookApiService** tangani exception dengan method `handleException()`:
- Ambil response status code (jika tersedia)
- Parse error message dari response body
- Return standardized error array:
```php
{
    "success": false,
    "message": "error description", 
    "status_code": 500,
    "data": null
}
```

**BookController** handle response:
- Check: `if (!$response['success'])`
- Tampilkan error message ke user
- Log error (optional)

---

## Struktur File

```
library-frontend/
├── app/
│   ├── Config/
│   │   ├── Database.php
│   │   └── Api.php
│   ├── Controllers/
│   │   └── BookController.php
│   ├── Service/
│   │   ├── ApiService.php
│   │   └── BookApiService.php
│   └── Views/
│       └── books/
│           ├── index.php
│           ├── create.php
│           ├── edit.php
│           └── dashboard.php
├── .env
├── API_ENDPOINTS_REQUIRED.md
└── SETUP_CHECKLIST.md
```

---

## Environment Variables

**.env file**:
```properties
# Frontend settings
app.baseURL = 'http://localhost:8080/'

# API Configuration  
api.baseURL = 'http://localhost:8000/api/'

# Database (optional, untuk fallback/testing)
database.default.hostname = localhost
database.default.database = library_management  
database.default.username = root
database.default.password = root
```

---

## Port Configuration

- **CodeIgniter Frontend**: `http://localhost:8080`
- **Laravel Backend API**: `http://localhost:8000` 
- **MySQL Database**: `localhost:3306`

---

## CORS Headers (Required in Laravel)

```
Access-Control-Allow-Origin: http://localhost:8080
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS  
Access-Control-Allow-Headers: Content-Type, Accept
Access-Control-Max-Age: 3600
```
