# Sistem Manajemen Perpustakaan

## 📋 Overview

Sistem manajemen perpustakaan dengan arsitektur terpisah antara frontend (CodeIgniter) dan backend (Laravel API).

### 🏗️ Arsitektur Sistem
```
Frontend (CodeIgniter - Port 8080) 
    → API Calls → 
Backend (Laravel API - Port 8000) 
    → Database (MySQL - Port 3306)
```

### 🛠️ Teknologi
- **Frontend**: CodeIgniter 4, Bootstrap 5, jQuery
- **Backend**: Laravel 10/11, Eloquent ORM  
- **Database**: MySQL
- **HTTP Client**: GuzzleHttp

## 🚀 Quick Start

### Backend Setup (Laravel)
```bash
cd library-backend
composer install
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env
php artisan migrate
php artisan serve --port=8000
```

### Frontend Setup (CodeIgniter)
```bash
cd library-frontend
composer install
cp env .env

# Konfigurasi API URL di .env
php -S localhost:8080 -t public/
```

### Environment Configuration
**.env Frontend:**
```properties
app.baseURL = 'http://localhost:8080/'
api.baseURL = 'http://localhost:8000/api/'
```

**.env Backend:**
```properties
DB_DATABASE=library_management
DB_USERNAME=root
DB_PASSWORD=root
```

## 📊 Struktur Database

### Table: books
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary Key |
| judul | VARCHAR(255) | Judul buku |
| pengarang | VARCHAR(255) | Penulis |
| penerbit | VARCHAR(255) | Penerbit |
| tahun_terbit | YEAR | Tahun terbit |
| jumlah_halaman | INT | Jumlah halaman |
| kategori | VARCHAR(100) | Kategori |
| isbn | VARCHAR(20) | ISBN |
| status | ENUM | Status buku |

**Status Values:** `Tersedia`, `Dipinjam`, `Rusak`, `Hilang`

## 🔌 API Endpoints

### Books Management
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/books` | Get books dengan filter & pagination |
| `POST` | `/api/books` | Create new book |
| `GET` | `/api/books/{id}` | Get specific book |
| `PUT` | `/api/books/{id}` | Update book |
| `DELETE` | `/api/books/{id}` | Delete book |
| `PUT` | `/api/books/{id}/status` | Update status |
| `GET` | `/api/books/statistics/overview` | Get statistics |

### Parameters Filter
```http
GET /api/books?search=programming&kategori=Technology&status=Tersedia&per_page=10
```

### Response Format
**Success:**
```json
{
    "success": true,
    "data": {...},
    "meta": {...},
    "message": "Success message"
}
```

**Error:**
```json
{
    "success": false,
    "message": "Error message",
    "errors": {...}
}
```

## 🔄 Alur Kerja

### 1. Menampilkan Daftar Buku
```
User → GET /books → BookController → BookApiService → 
HTTP GET /api/books → Laravel Controller → Database → 
JSON Response → Render View
```

### 2. Menambah Buku Baru
```
Form Submit → POST /books → Validation → 
BookApiService → HTTP POST /api/books → 
Laravel Validation → Database Insert → 
Success Response → Redirect dengan Flash Message
```

### 3. Error Handling
```
API Error → BookApiService → Format Error → 
BookController → Tampilkan Pesan Error ke User
```

## 📁 Struktur Project

### Backend (Laravel)
```
library-backend/
├── app/
│   ├── Models/Book.php
│   ├── Http/Controllers/BookController.php
│   └── Http/Middleware/Cors.php
├── database/migrations/
├── routes/api.php
└── config/cors.php
```

### Frontend (CodeIgniter)
```
library-frontend/
├── app/
│   ├── Controllers/BookController.php
│   ├── Services/
│   │   ├── ApiService.php
│   │   └── BookApiService.php
│   └── Views/books/
├── public/assets/
└── .env
```

## ✨ Fitur Utama

### Backend Features
- ✅ RESTful API dengan Laravel
- ✅ CRUD operations untuk books
- ✅ Filtering & pagination
- ✅ Validation & error handling
- ✅ CORS configuration
- ✅ Statistics endpoint

### Frontend Features
- ✅ CodeIgniter MVC structure
- ✅ API integration dengan GuzzleHTTP
- ✅ Form validation
- ✅ Bootstrap UI
- ✅ Flash messages
- ✅ Responsive design

## 🎯 Contoh Penggunaan

### Get All Books dengan Filter
```php
// Frontend Controller
$filters = [
    'search' => 'programming',
    'kategori' => 'Technology', 
    'per_page' => 10
];
$response = $this->bookApiService->getAllBooks($filters);
```

### Create New Book
```php
$bookData = [
    'judul' => 'Belajar PHP',
    'pengarang' => 'John Doe',
    'penerbit' => 'Tech Publisher',
    'tahun_terbit' => 2024,
    'kategori' => 'Programming'
];
$response = $this->bookApiService->createBook($bookData);
```

### Update Book Status
```php
$response = $this->bookApiService->updateBookStatus(1, 'Dipinjam');
```

## ⚠️ Error Handling

Sistem menangani berbagai jenis error:
- Network timeout
- API server down
- Validation errors
- Database errors
- HTTP errors (4xx, 5xx)

## 🔧 Configuration

### CORS Headers (Laravel)
```php
// config/cors.php
'allowed_origins' => ['http://localhost:8080'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

### API Service (CodeIgniter)
```php
// Timeout configuration
'timeout' => 30.0,
'connect_timeout' => 10
```

## 📞 Port Configuration
- **Frontend**: http://localhost:8080
- **Backend API**: http://localhost:8000  
- **MySQL Database**: localhost:3306

---

**Sistem siap untuk development dan production deployment dengan arsitektur yang scalable dan maintainable.**
