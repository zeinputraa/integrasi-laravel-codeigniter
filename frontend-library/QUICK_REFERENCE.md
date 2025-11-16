# Quick Reference Guide

## 📍 File Locations

```
/Users/kamumanis/Documents/Code/frontend-integrasi/library-frontend/

Key Files:
├── app/Controllers/BookController.php       ← Updated to use API
├── app/Service/BookApiService.php           ← NEW - API client
├── app/Config/Database.php                  ← Updated config
├── .env                                     ← Database & API config
├── API_ENDPOINTS_REQUIRED.md                ← Required endpoints
├── SETUP_CHECKLIST.md                       ← Setup guide
├── ARCHITECTURE.md                          ← System design
├── README_INTEGRATION.md                    ← Full integration guide
└── QUICK_REFERENCE.md                       ← This file
```

---

## 🚀 Quick Start (3 Commands)

### Terminal 1: Laravel Backend
```bash
cd /path/to/laravel/backend
php artisan serve --port=8000
```

### Terminal 2: CodeIgniter Frontend
```bash
cd /Users/kamumanis/Documents/Code/frontend-integrasi/library-frontend
php spark serve --port=8080
```

### Terminal 3: Access Application
```bash
open http://localhost:8080
```

---

## 📡 API Flow

```
Frontend Request    →    Backend Response
─────────────────────────────────────────

GET /books          →    GET /api/books
POST /books         →    POST /api/books
GET /books/{id}     →    GET /api/books/{id}
PUT /books/{id}     →    PUT /api/books/{id}
DELETE /books/{id}  →    DELETE /api/books/{id}
```

---

## ⚙️ Configuration

### .env Settings
```bash
# Frontend
app.baseURL = 'http://localhost:8080/'

# API (PENTING!)
api.baseURL = 'http://localhost:8000/api/'

# Database
database.default.database = library_management
database.default.username = root
database.default.password = root
```

### Test API Connection
```bash
# Should return list of books
curl -X GET "http://localhost:8000/api/books"

# Should return book with id=1
curl -X GET "http://localhost:8000/api/books/1"
```

---

## 🔄 How It Works (Simplified)

```
┌─────────────────────────────────────────────────────┐
│ User clicks "Daftar Buku" on http://localhost:8080 │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│ BookController::index()                             │
│ - Gets filters from URL                             │
│ - Calls: $this->apiService->getAllBooks($filters)  │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│ BookApiService::getAllBooks()                       │
│ - Makes HTTP GET to http://localhost:8000/api/books│
│ - Sends filters as query parameters                 │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
        [Internet/Network Request]
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│ Laravel API (http://localhost:8000)                 │
│ - Route: GET /api/books                             │
│ - BookController::index()                           │
│ - Queries database                                  │
│ - Returns JSON response                             │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│ MySQL Database                                      │
│ SELECT * FROM books WHERE ...                       │
│ - Filters applied                                   │
│ - Pagination handled                                │
└────────────────────┬────────────────────────────────┘
                     │
        Response travels back
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│ BookApiService receives JSON response               │
│ - Parses the data                                   │
│ - Returns formatted array                           │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│ BookController gets the array                       │
│ - Prepares view variables                           │
│ - Renders: view('books/index', $data)              │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│ Browser receives HTML                               │
│ - Displays books list to user                       │
│ - User sees the data!                               │
└─────────────────────────────────────────────────────┘
```

---

## 🐛 Common Issues & Solutions

### Issue: "Connection refused"
```bash
# Check if Laravel is running on port 8000
lsof -i :8000

# If not, start it:
php artisan serve --port=8000
```

### Issue: "CORS policy blocked"
```
Add to Laravel config/cors.php:
'allowed_origins' => ['http://localhost:8080']
```

### Issue: "Database not found"
```bash
mysql -u root -p
CREATE DATABASE library_management;
```

### Issue: "API returns 404"
```
Check:
1. Laravel endpoint exists
2. URL in .env is correct: http://localhost:8000/api/
3. Route is defined in Laravel
```

---

## 📝 Code Examples

### Creating a New API Endpoint (Step by Step)

**Step 1: Add Route in Laravel** (routes/api.php)
```php
Route::get('/books/featured', [BookController::class, 'featured']);
```

**Step 2: Add Method in Laravel Controller** (app/Http/Controllers/BookController.php)
```php
public function featured() {
    $books = Book::where('featured', true)->limit(5)->get();
    return response()->json(['data' => $books]);
}
```

**Step 3: Add Method in BookApiService** (app/Service/BookApiService.php)
```php
public function getFeaturedBooks() {
    try {
        $response = $this->client->get('books/featured');
        $data = json_decode($response->getBody(), true);
        return ['success' => true, 'data' => $data['data'] ?? $data];
    } catch (RequestException $e) {
        return $this->handleException($e);
    }
}
```

**Step 4: Use in BookController** (app/Controllers/BookController.php)
```php
public function featured() {
    $response = $this->apiService->getFeaturedBooks();
    return view('books/featured', ['books' => $response['data']]);
}
```

**Step 5: Add Route in CodeIgniter** (app/Config/Routes.php)
```php
$routes->get('books/featured', 'BookController::featured');
```

---

## 🔐 Security Quick Tips

✅ **DO:**
- Validate all inputs before sending to API
- Use HTTPS in production
- Set environment variables for credentials
- Implement authentication/authorization
- Log all API calls for debugging

❌ **DON'T:**
- Never hardcode credentials in code
- Don't disable SSL verification in production
- Don't expose API keys in frontend
- Don't skip validation
- Don't ignore error responses

---

## 📊 Testing

### Manual Testing with cURL

```bash
# Get all books
curl -X GET "http://localhost:8000/api/books"

# Get single book
curl -X GET "http://localhost:8000/api/books/1"

# Create book
curl -X POST "http://localhost:8000/api/books" \
  -H "Content-Type: application/json" \
  -d '{
    "judul": "Test Book",
    "pengarang": "Author",
    "penerbit": "Publisher",
    "tahun_terbit": 2024,
    "jumlah_halaman": 100,
    "kategori": "Fiksi",
    "isbn": "1234567890",
    "status": "Tersedia"
  }'

# Update book
curl -X PUT "http://localhost:8000/api/books/1" \
  -H "Content-Type: application/json" \
  -d '{"status": "Dipinjam"}'

# Delete book
curl -X DELETE "http://localhost:8000/api/books/1"

# Get statistics
curl -X GET "http://localhost:8000/api/books/statistics"
```

---

## 📚 Related Documents

- **API_ENDPOINTS_REQUIRED.md** - Detailed endpoint documentation
- **SETUP_CHECKLIST.md** - Complete setup and troubleshooting
- **ARCHITECTURE.md** - System design and data flows
- **README_INTEGRATION.md** - Full integration guide

---

## ❓ FAQ

**Q: Apakah saya harus membuat database table di CodeIgniter?**  
A: Tidak. Database tables hanya ada di Laravel. CodeIgniter hanya berkomunikasi via API.

**Q: Bagaimana kalau Laravel offline?**  
A: Frontend akan error karena tidak bisa connect. Implementasi fallback/retry logic sesuai kebutuhan.

**Q: Bisakah saya menggunakan langsung database dari CodeIgniter?**  
A: Bisa, tapi tidak direkomendasikan. Gunakan API untuk konsistensi data antara system.

**Q: Berapa latency network request?**  
A: Biasanya < 100ms untuk localhost. Di production tergantung server distance dan koneksi.

**Q: Apakah saya perlu authentication?**  
A: Tergantung kebutuhan security. Bisa implement JWT token atau API key.

---

## 🚀 Next Steps

1. ✅ Verifikasi Laravel backend memiliki semua endpoint (lihat API_ENDPOINTS_REQUIRED.md)
2. ✅ Test API calls menggunakan cURL
3. ✅ Jalankan kedua server (Laravel & CodeIgniter)
4. ✅ Akses http://localhost:8080 dan test fitur
5. ✅ Check error logs jika ada issue

---

## 📞 Emergency Checklist

Jika semuanya tidak berfungsi:

- [ ] Apakah MySQL running? `mysql -u root -p`
- [ ] Apakah Laravel running? `php artisan serve --port=8000`
- [ ] Apakah CodeIgniter running? `php spark serve --port=8080`
- [ ] Apakah database `library_management` ada?
- [ ] Apakah Laravel memiliki endpoint `/api/books`?
- [ ] Apakah .env memiliki `api.baseURL = 'http://localhost:8000/api/'`?
- [ ] Apakah port tidak conflict? (lsof -i :PORT)
- [ ] Apakah CORS sudah dikonfigurasi di Laravel?

---

## 💡 Pro Tips

1. **Debug Mode**: Check browser DevTools Network tab untuk melihat API requests
2. **Logging**: Add logging di BookApiService untuk track API calls
3. **Response Caching**: Cache API responses di CodeIgniter untuk performa
4. **Error Handling**: Implement retry logic untuk network failures
5. **Testing**: Buat unit tests untuk API client
6. **Documentation**: Update documentation ketika menambah endpoint baru

---

**Last Updated:** November 16, 2025  
**Status:** Ready for Integration ✅
