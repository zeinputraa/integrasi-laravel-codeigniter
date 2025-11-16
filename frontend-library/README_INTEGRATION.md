# Library Management Frontend + Backend Integration

Sistem Manajemen Perpustakaan dengan CodeIgniter 4 Frontend dan Laravel Backend API.

## 📋 Ringkasan Perubahan

Anda telah berhasil mengkonversi CodeIgniter frontend dari menggunakan database lokal menjadi menggunakan **Laravel API Backend**.

### Apa yang Diubah:

#### 1. **BookController.php** (Updated)
```
SEBELUM: BookModel -> Database queries
SESUDAH: BookApiService -> HTTP requests ke Laravel API
```

- Mengganti `use App\Models\BookModel` dengan `use App\Services\BookApiService`
- Mengganti `$this->bookModel` dengan `$this->apiService`
- Semua method sekarang membuat HTTP requests ke backend

#### 2. **BookApiService.php** (NEW)
File baru yang menangani semua komunikasi dengan Laravel API:
- `getAllBooks($filters)` - GET /api/books
- `getBookById($id)` - GET /api/books/{id}
- `createBook($data)` - POST /api/books
- `updateBook($id, $data)` - PUT /api/books/{id}
- `deleteBook($id)` - DELETE /api/books/{id}
- `updateBookStatus($id, $status)` - PUT /api/books/{id}/status
- `getStatistics()` - GET /api/books/statistics

#### 3. **.env** (Updated)
```properties
# Database tetap untuk reference/fallback
database.default.database = library_management

# API endpoint (yang sebenarnya digunakan)
api.baseURL = 'http://localhost:8000/api/'
```

#### 4. **Database.php** (Updated)
- Konfigurasi database updated ke `library_management`
- Constructor menambahkan dukungan environment variables

---

## 🚀 Quick Start

### Prasyarat
- PHP 8.0+
- MySQL 5.7+
- Laravel 8.0+ (Backend)
- CodeIgniter 4 (Frontend)
- Composer

### 1. Setup Database

```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE library_management;
EXIT;
```

### 2. Setup Backend Laravel

```bash
# Clone/navigate ke laravel project
cd /path/to/laravel/backend

# Install dependencies
composer install

# Copy .env
cp .env.example .env

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate

# Start server
php artisan serve --port=8000
```

### 3. Setup Frontend CodeIgniter

```bash
# Navigate ke codeigniter project
cd /Users/kamumanis/Documents/Code/frontend-integrasi/library-frontend

# Install dependencies (jika menggunakan composer)
composer install

# Start server
php spark serve --port=8080
```

### 4. Akses Aplikasi

- Frontend: http://localhost:8080
- Backend API: http://localhost:8000/api
- Database: localhost:3306

---

## 📚 Dokumentasi

### 1. **API_ENDPOINTS_REQUIRED.md**
Dokumentasi lengkap semua endpoint Laravel yang diperlukan:
- Request/Response format
- Query parameters
- Error handling
- cURL examples

### 2. **SETUP_CHECKLIST.md**
Checklist setup lengkap dan troubleshooting:
- Langkah-langkah setup
- Verifikasi koneksi
- Solusi error umum
- Testing application flow

### 3. **ARCHITECTURE.md**
Dokumentasi arsitektur sistem:
- System overview diagram
- Data flow examples
- Error handling flow
- File structure
- CORS configuration

---

## 🔌 API Integration

### Bagaimana Berfungsi

```
User Request (Browser)
        ↓
CodeIgniter Frontend (Port 8080)
        ↓
BookController validates & processes
        ↓
BookApiService makes HTTP request
        ↓
Laravel Backend API (Port 8000)
        ↓
Laravel validates & processes
        ↓
MySQL Database (library_management)
        ↓
JSON Response kembali ke Frontend
        ↓
Frontend renders response
        ↓
User sees result
```

### Contoh Request Flow: Get All Books

```
1. GET http://localhost:8080/books
   └─> BookController::index()
   └─> $this->apiService->getAllBooks($filters)

2. HTTP GET http://localhost:8000/api/books?page=1&per_page=10
   └─> Laravel BookController::index()
   └─> Query database

3. JSON Response:
   {
     "data": [...books...],
     "meta": {"total": 50, "per_page": 10, ...}
   }

4. CodeIgniter renders view dengan data
```

---

## ⚙️ Konfigurasi

### .env Settings

```properties
# Frontend Base URL
app.baseURL = 'http://localhost:8080/'

# Laravel API Base URL (PENTING!)
api.baseURL = 'http://localhost:8000/api/'

# Database (untuk reference/fallback)
database.default.hostname = localhost
database.default.database = library_management
database.default.username = root
database.default.password = root
```

### BookApiService Configuration

File: `app/Services/BookApiService.php`

```php
$this->baseUrl = getenv('api.baseURL') ?: 'http://localhost:8000/api/';
$this->client = new Client([
    'base_uri' => $this->baseUrl,
    'timeout'  => 10,
    'verify'   => false, // Production: true
]);
```

---

## 🐛 Troubleshooting

### Error: "Failed to connect to API"

**Penyebab:** Laravel backend tidak berjalan

**Solusi:**
```bash
# Terminal 1: Jalankan Laravel
cd /path/to/laravel
php artisan serve --port=8000

# Terminal 2: Jalankan CodeIgniter
cd /path/to/codeigniter
php spark serve --port=8080
```

### Error: "CORS policy: cross-origin request blocked"

**Penyebab:** Laravel tidak mengizinkan request dari CodeIgniter

**Solusi di Laravel:**

1. Install package CORS:
```bash
composer require fruitcake/laravel-cors
```

2. Update `config/cors.php`:
```php
'allowed_origins' => ['http://localhost:8080'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
'allowed_headers' => ['*'],
'supports_credentials' => false,
```

3. Register middleware di `app/Http/Kernel.php`:
```php
protected $middleware = [
    \Fruitcake\Cors\HandleCors::class,
    // ... other middleware
];
```

### Error: "Connection refused"

**Penyebab:** Port tidak tersedia atau service belum berjalan

**Solusi:**
```bash
# Check port availability
lsof -i :8000  # Laravel
lsof -i :8080  # CodeIgniter
lsof -i :3306  # MySQL

# Gunakan port berbeda jika sudah terpakai
php artisan serve --port=8001
php spark serve --port=8081
```

### Error: "Database library_management not found"

**Penyebab:** Database belum dibuat

**Solusi:**
```bash
mysql -u root -p

# Di MySQL prompt:
CREATE DATABASE library_management;

# Verify:
SHOW DATABASES LIKE 'library_management';
```

---

## 📝 Membuat Endpoint Baru

Jika Anda ingin menambah endpoint baru di Laravel:

### Step 1: Buat endpoint di Laravel Backend

```php
// routes/api.php
Route::get('/books/search', [BookController::class, 'search']);

// controllers/BookController.php
public function search(Request $request) {
    $query = $request->get('q');
    $books = Book::where('judul', 'like', "%$query%")
                  ->orWhere('pengarang', 'like', "%$query%")
                  ->get();
    return response()->json(['data' => $books]);
}
```

### Step 2: Add method di BookApiService

```php
// app/Services/BookApiService.php
public function searchBooks($query) {
    try {
        $response = $this->client->get('books/search', [
            'query' => ['q' => $query]
        ]);
        $data = json_decode($response->getBody(), true);
        return ['success' => true, 'data' => $data['data'] ?? $data];
    } catch (RequestException $e) {
        return $this->handleException($e);
    }
}
```

### Step 3: Gunakan di Controller

```php
// app/Controllers/BookController.php
public function search() {
    $query = $this->request->getGet('q');
    $response = $this->apiService->searchBooks($query);
    return $this->response->setJSON($response);
}
```

---

## 🔐 Security Considerations

### Production Checklist

- [ ] Set `CI_ENVIRONMENT = production` in `.env`
- [ ] Set `app.forceGlobalSecureRequests = true` in `.env`
- [ ] Enable HTTPS for API calls
- [ ] Set `'verify' => true` in BookApiService
- [ ] Implement authentication (API tokens/JWT)
- [ ] Add rate limiting
- [ ] Add input validation & sanitization
- [ ] Secure database credentials
- [ ] Use environment variables for secrets
- [ ] Add error logging
- [ ] Disable debug mode
- [ ] Set up CORS properly for production domain
- [ ] Add request/response logging
- [ ] Implement caching strategy

---

## 📦 Dependencies

### CodeIgniter Frontend
- `codeigniter4/framework` - Framework
- `guzzlehttp/guzzle` - HTTP client (sudah installed)

### Laravel Backend
- `laravel/framework` - Framework
- `laravel/tinker` - REPL
- `fruitcake/laravel-cors` - CORS support (rekomendasi)

---

## 🤝 Support & Issues

### Reporting Issues

Jika menemukan masalah:

1. Check `SETUP_CHECKLIST.md` untuk troubleshooting umum
2. Check `API_ENDPOINTS_REQUIRED.md` untuk format endpoint
3. Check `ARCHITECTURE.md` untuk data flow
4. Verify:
   - Kedua server (Laravel & CodeIgniter) berjalan
   - MySQL database dan tables ada
   - Network connectivity OK
   - Ports tidak conflict

### Testing

Gunakan cURL untuk test endpoint:

```bash
# Test Laravel API
curl -X GET "http://localhost:8000/api/books"

# Test dengan filter
curl -X GET "http://localhost:8000/api/books?per_page=5&page=1"

# Test create book
curl -X POST "http://localhost:8000/api/books" \
  -H "Content-Type: application/json" \
  -d '{"judul":"Test", "pengarang":"Author", ...}'
```

---

## 📞 Files Generated

Dokumentasi tambahan yang dibuat:

1. **API_ENDPOINTS_REQUIRED.md** - Detail semua endpoint
2. **SETUP_CHECKLIST.md** - Setup guide & troubleshooting
3. **ARCHITECTURE.md** - System architecture & diagrams
4. **README.md** - File ini

---

## ✅ Summary

✓ CodeIgniter frontend dikonfigurasi untuk menggunakan Laravel API  
✓ BookApiService dibuat untuk HTTP komunikasi  
✓ BookController updated untuk menggunakan API  
✓ Database configuration sudah update  
✓ Dokumentasi lengkap disediakan  

**Next Step:** Pastikan Laravel backend memiliki semua endpoint yang diperlukan (lihat `API_ENDPOINTS_REQUIRED.md`)

Selamat! 🚀 Sistem Anda siap diintegrasikan!
