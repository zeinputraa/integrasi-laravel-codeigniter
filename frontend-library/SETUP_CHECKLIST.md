# Checklist Setup CodeIgniter Frontend + Laravel Backend

## ✅ Yang Sudah Dilakukan

### 1. CodeIgniter Configuration
- [x] Database configuration updated untuk `library_management`
- [x] BookApiService dibuat untuk API calls ke Laravel
- [x] BookController updated untuk menggunakan API
- [x] `.env` dikonfigurasi dengan API endpoint: `http://localhost:8000/api/`

### 2. Backend Laravel Endpoints
Pastikan Laravel backend Anda memiliki endpoints ini:

```
Frontend (CodeIgniter)          →    Backend (Laravel)
Port 8080                             Port 8000

GET  /books              →      GET  /api/books
GET  /books/{id}         →      GET  /api/books/{id}
POST /books              →      POST /api/books
PUT  /books/{id}         →      PUT  /api/books/{id}
DELETE /books/{id}       →      DELETE /api/books/{id}
PUT  /books/{id}/status  →      PUT  /api/books/{id}/status
GET  /dashboard          →      GET  /api/books/statistics
```

---

## 🔧 Langkah Setup

### Step 1: Pastikan MySQL Database Ada
```bash
mysql -u root -p
```

```sql
CREATE DATABASE IF NOT EXISTS library_management;
```

### Step 2: Jalankan Laravel Backend
```bash
cd /path/to/laravel/backend
php artisan serve --port=8000
```

### Step 3: Jalankan CodeIgniter Frontend
```bash
cd /Users/kamumanis/Documents/Code/frontend-integrasi/library-frontend
php spark serve --port=8080
```

### Step 4: Verifikasi API Connection
Buka browser: `http://localhost:8080/books`

Jika melihat data buku → ✅ Setup berhasil!
Jika error → Cek error messages (lihat bagian Troubleshooting)

---

## 🐛 Troubleshooting

### Error: "curl error: Failed to connect"
**Penyebab:** Laravel backend tidak berjalan

**Solusi:**
```bash
# Terminal 1: Jalankan Laravel
cd /path/to/laravel/backend
php artisan serve --port=8000

# Terminal 2: Jalankan CodeIgniter
php spark serve --port=8080
```

---

### Error: "CORS policy"
**Penyebab:** Laravel belum mengizinkan request dari CodeIgniter

**Solusi di Laravel:**
1. Install CORS: `composer require fruitcake/laravel-cors`
2. Update `config/cors.php`:
```php
'allowed_origins' => ['http://localhost:8080'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
'allowed_headers' => ['*'],
'supports_credentials' => false,
```
3. Register di `config/app.php` aliases:
```php
'CORS' => Fruitcake\Cors\HandleCors::class,
```

---

### Error: "Terjadi kesalahan saat menghubungi API"
**Penyebab:** Endpoint API tidak sesuai

**Solusi:**
1. Cek file: `API_ENDPOINTS_REQUIRED.md` untuk endpoint format
2. Pastikan Laravel endpoints match dengan yang di requirement
3. Test dengan cURL:
```bash
curl -X GET "http://localhost:8000/api/books"
```

---

### Error: "Database connection error"
**Penyebab:** MySQL database belum dibuat atau credentials salah

**Solusi:**
```bash
# Verify database exists
mysql -u root -p -e "SHOW DATABASES LIKE 'library_management';"

# Create database if doesn't exist
mysql -u root -p -e "CREATE DATABASE library_management;"

# Check credentials in .env
cat /Users/kamumanis/Documents/Code/frontend-integrasi/library-frontend/.env | grep database
```

---

## 📋 File yang Sudah Diubah

1. **`.env`**
   - Database name: `library_management`
   - API base URL: `http://localhost:8000/api/`

2. **`app/Config/Database.php`**
   - Updated database connection settings
   - Added environment variable support

3. **`app/Service/BookApiService.php`** (NEW)
   - HTTP client untuk Laravel API
   - Methods: getAllBooks, getBookById, createBook, updateBook, deleteBook, updateBookStatus, getStatistics

4. **`app/Controllers/BookController.php`**
   - Replaced BookModel dengan BookApiService
   - Semua method sekarang menggunakan API calls

5. **`API_ENDPOINTS_REQUIRED.md`** (NEW)
   - Dokumentasi lengkap endpoint yang diperlukan

---

## 🚀 Testing Application Flow

### Test Case 1: View All Books
1. Buka: `http://localhost:8080/books`
2. CodeIgniter memanggil: `BookApiService->getAllBooks()`
3. Sends request: `GET http://localhost:8000/api/books`
4. Laravel mengembalikan list buku dari database
5. CodeIgniter menampilkan di view

### Test Case 2: Create Book
1. Klik "Tambah Buku" di `http://localhost:8080/books`
2. Fill form dan submit
3. CodeIgniter memanggil: `BookApiService->createBook()`
4. Sends request: `POST http://localhost:8000/api/books` dengan data
5. Laravel validate dan insert ke database
6. Return ke list dengan success message

### Test Case 3: Dashboard
1. Buka: `http://localhost:8080/dashboard`
2. CodeIgniter memanggil: `BookApiService->getStatistics()`
3. Sends request: `GET http://localhost:8000/api/books/statistics`
4. Laravel menghitung statistik dari database
5. CodeIgniter menampilkan di dashboard

---

## 📞 Support

Jika ada error, cek:
1. Apakah kedua server (Laravel & CodeIgniter) berjalan?
2. Apakah MySQL database `library_management` ada?
3. Apakah Laravel memiliki semua endpoint yang diperlukan?
4. Apakah CORS dikonfigurasi dengan benar?

File: `API_ENDPOINTS_REQUIRED.md` berisi dokumentasi lengkap semua endpoint yang diperlukan.
