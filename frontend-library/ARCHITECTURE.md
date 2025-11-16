# Arsitektur CodeIgniter + Laravel Integration

## Overview Sistem

```
┌──────────────────────────────────────────────────────────────────┐
│                      USER BROWSER                                │
│              http://localhost:8080                               │
└──────────────────────────┬───────────────────────────────────────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────────────┐
│           CODEIGNITER FRONTEND (Port 8080)                       │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ Routes                                                  │   │
│  │ - GET    /books              → index()                 │   │
│  │ - GET    /books/create       → create()                │   │
│  │ - POST   /books              → store()                 │   │
│  │ - GET    /books/{id}/edit    → edit()                  │   │
│  │ - PUT    /books/{id}         → update()                │   │
│  │ - DELETE /books/{id}         → delete()                │   │
│  │ - PUT    /books/{id}/status  → updateStatus()          │   │
│  │ - GET    /dashboard          → dashboard()             │   │
│  │ - GET    /statistics         → statistics()            │   │
│  └─────────────────────────────────────────────────────────┘   │
│                           │                                      │
│  ┌─────────────────────────▼─────────────────────────────────┐  │
│  │ BookController                                           │  │
│  │ - Validates input dari user                              │  │
│  │ - Calls BookApiService untuk business logic             │  │
│  │ - Returns views dengan data dari API                    │  │
│  └─────────────────────────┬─────────────────────────────────┘  │
│                           │                                      │
│  ┌─────────────────────────▼─────────────────────────────────┐  │
│  │ BookApiService (NEW)                                    │  │
│  │ - getAllBooks($filters)                                 │  │
│  │ - getBookById($id)                                      │  │
│  │ - createBook($data)                                     │  │
│  │ - updateBook($id, $data)                                │  │
│  │ - deleteBook($id)                                       │  │
│  │ - updateBookStatus($id, $status)                        │  │
│  │ - getStatistics()                                       │  │
│  │                                                          │  │
│  │ Uses: GuzzleHttp\Client untuk HTTP requests            │  │
│  └─────────────────────────┬─────────────────────────────────┘  │
│                           │                                      │
│  ┌─────────────────────────▼─────────────────────────────────┐  │
│  │ Views (Blade-like)                                       │  │
│  │ - books/index.php       → Daftar buku                   │  │
│  │ - books/create.php      → Form tambah buku              │  │
│  │ - books/edit.php        → Form edit buku                │  │
│  │ - books/dashboard.php   → Dashboard dengan statistik    │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                  │
│ Configuration:                                                   │
│ - .env: api.baseURL = 'http://localhost:8000/api/'            │
│ - Database: library_management (optional for fallback)         │
└──────────────────────────┬───────────────────────────────────────┘
                           │
                    HTTP/REST API
                (JSON format request/response)
                           │
                           ▼
┌──────────────────────────────────────────────────────────────────┐
│             LARAVEL BACKEND API (Port 8000)                      │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ API Routes (app/routes/api.php)                         │   │
│  │ - GET    /books              → index()                  │   │
│  │ - GET    /books/{id}         → show()                   │   │
│  │ - POST   /books              → store()                  │   │
│  │ - PUT    /books/{id}         → update()                 │   │
│  │ - DELETE /books/{id}         → destroy()                │   │
│  │ - PUT    /books/{id}/status  → updateStatus()           │   │
│  │ - GET    /books/statistics   → statistics()             │   │
│  └─────────────────────────────────────────────────────────┘   │
│                           │                                      │
│  ┌─────────────────────────▼─────────────────────────────────┐  │
│  │ BookController                                           │  │
│  │ - Validates request                                      │  │
│  │ - Calls BookService/BookModel untuk business logic      │  │
│  │ - Returns JSON response                                 │  │
│  └─────────────────────────┬─────────────────────────────────┘  │
│                           │                                      │
│  ┌─────────────────────────▼─────────────────────────────────┐  │
│  │ BookModel / Repository                                  │  │
│  │ - Query builder untuk database operations              │  │
│  │ - Validation rules                                      │  │
│  │ - Relationships                                         │  │
│  │ - Scopes untuk filtering                                │  │
│  └─────────────────────────┬─────────────────────────────────┘  │
│                           │                                      │
│  ┌─────────────────────────▼─────────────────────────────────┐  │
│  │ Request/Response Middleware                             │  │
│  │ - CORS handling                                         │  │
│  │ - Authentication (optional)                             │  │
│  │ - Rate limiting (optional)                              │  │
│  └─────────────────────────┬─────────────────────────────────┘  │
│                           │                                      │
└──────────────────────────┬───────────────────────────────────────┘
                           │
                    SQL Queries
                           │
                           ▼
┌──────────────────────────────────────────────────────────────────┐
│              MYSQL DATABASE (Port 3306)                          │
│                                                                  │
│  Database: library_management                                   │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ books table                                             │   │
│  ├─────────────────────────────────────────────────────────┤   │
│  │ id              INT PRIMARY KEY AUTO_INCREMENT          │   │
│  │ judul           VARCHAR(255) NOT NULL                   │   │
│  │ pengarang       VARCHAR(255) NOT NULL                   │   │
│  │ penerbit        VARCHAR(255) NOT NULL                   │   │
│  │ tahun_terbit    YEAR NOT NULL                           │   │
│  │ jumlah_halaman  INT                                     │   │
│  │ kategori        VARCHAR(100)                            │   │
│  │ isbn            VARCHAR(20) UNIQUE                      │   │
│  │ status          VARCHAR(20) DEFAULT 'Tersedia'          │   │
│  │ created_at      DATETIME DEFAULT CURRENT_TIMESTAMP      │   │
│  │ updated_at      DATETIME ON UPDATE CURRENT_TIMESTAMP    │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## Data Flow Example: Get All Books

```
1. User clicks "Daftar Buku" link
   └─> Browser: GET http://localhost:8080/books

2. CodeIgniter Router
   └─> Route: GET /books
   └─> Controller: BookController::index()

3. BookController::index()
   ├─> Get filters from request
   ├─> Call: $this->apiService->getAllBooks($filters)
   └─> Return view with data

4. BookApiService::getAllBooks()
   ├─> Build query parameters from filters
   ├─> Make HTTP request:
   │   └─> GET http://localhost:8000/api/books?search=...&kategori=...
   ├─> Wait for response
   ├─> Return: ['success' => true, 'data' => [...], 'meta' => [...]]

5. Laravel API Route
   └─> Route: GET /api/books
   └─> Controller: BookController::index()

6. Laravel BookController::index()
   ├─> Get query parameters
   ├─> Build query from parameters
   ├─> Query database:
   │   └─> SELECT * FROM books WHERE ... LIMIT ... OFFSET ...
   ├─> Get total count for pagination
   ├─> Return JSON response:
   │   └─> {
   │       "data": [...books...],
   │       "meta": {
   │         "total": 50,
   │         "per_page": 10,
   │         "current_page": 1,
   │         "total_pages": 5
   │       }
   │     }

7. Laravel sends response back to CodeIgniter

8. CodeIgniter BookApiService receives response
   ├─> Parse JSON
   ├─> Return formatted array

9. CodeIgniter BookController receives data
   ├─> Prepare view data
   ├─> Render: view('books/index', $data)
   └─> Return HTML to browser

10. Browser renders page with books list
```

---

## Data Flow Example: Create Book

```
1. User submits form di http://localhost:8080/books/create
   └─> Browser: POST http://localhost:8080/books
   └─> Payload: {judul, pengarang, penerbit, ...}

2. CodeIgniter Router
   └─> Route: POST /books
   └─> Controller: BookController::store()

3. BookController::store()
   ├─> Validate input using CodeIgniter validator
   ├─> If validation fails:
   │   └─> Redirect back dengan error messages
   ├─> If valid:
   │   ├─> Call: $this->apiService->createBook($bookData)
   │   └─> Handle response

4. BookApiService::createBook()
   ├─> Make HTTP request:
   │   └─> POST http://localhost:8000/api/books
   │   └─> Payload (JSON): {judul, pengarang, ...}
   ├─> Wait for response
   ├─> Return: ['success' => true, 'message' => '...', 'id' => 1]

5. Laravel API Route
   └─> Route: POST /api/books
   └─> Controller: BookController::store()

6. Laravel BookController::store()
   ├─> Validate input using Laravel validator
   ├─> If validation fails:
   │   └─> Return: {"success": false, "message": "Validation error"}
   ├─> If valid:
   │   ├─> Create new Book model
   │   ├─> Save to database:
   │   │   └─> INSERT INTO books (judul, pengarang, ...) VALUES (...)
   │   ├─> Return JSON response:
   │   │   └─> {
   │   │       "success": true,
   │   │       "message": "Buku berhasil ditambahkan",
   │   │       "data": {...book...}
   │   │     }

7. Laravel sends response back to CodeIgniter

8. CodeIgniter BookApiService receives response
   ├─> Parse JSON
   ├─> Return formatted array

9. CodeIgniter BookController receives data
   ├─> If success:
   │   └─> Redirect to /books with success message
   ├─> If error:
   │   └─> Redirect back dengan error message

10. Browser navigates to books list (or form dengan error)
```

---

## Error Handling Flow

```
┌─────────────────────────────┐
│ Exception/Error occurs      │
└────────────┬────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────┐
│ BookApiService catches RequestException            │
│ - Network error                                    │
│ - Timeout                                          │
│ - API server down                                  │
│ - HTTP error (4xx, 5xx)                            │
└────────────┬────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────┐
│ handleException() method                           │
│ - Get response status code (if available)          │
│ - Parse error message from response body           │
│ - Return standardized error array:                 │
│   {                                                 │
│     "success": false,                              │
│     "message": "error description",                │
│     "status_code": 500,                            │
│     "data": null                                   │
│   }                                                 │
└────────────┬────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────┐
│ BookController handles response                    │
│ - Check: if (!$response['success'])                │
│ - Display error message to user                    │
│ - Log error (optional)                             │
└─────────────────────────────────────────────────────┘
```

---

## File Structure

```
library-frontend/
├── app/
│   ├── Config/
│   │   ├── Database.php (UPDATED)
│   │   └── Api.php
│   ├── Controllers/
│   │   └── BookController.php (UPDATED)
│   ├── Models/
│   │   └── BookModel.php (Optional fallback)
│   ├── Service/
│   │   ├── ApiService.php (Generic API client)
│   │   └── BookApiService.php (NEW - Book API operations)
│   └── Views/
│       └── books/
│           ├── index.php
│           ├── create.php
│           ├── edit.php
│           └── dashboard.php
├── .env (UPDATED)
├── API_ENDPOINTS_REQUIRED.md (NEW)
└── SETUP_CHECKLIST.md (NEW)
```

---

## Environment Variables

```properties
# .env file

# Frontend settings
app.baseURL = 'http://localhost:8080/'

# API Configuration
api.baseURL = 'http://localhost:8000/api/'

# Database (optional, for fallback/testing)
database.default.hostname = localhost
database.default.database = library_management
database.default.username = root
database.default.password = root
```

---

## Port Configuration

```
CodeIgniter Frontend:  http://localhost:8080
Laravel Backend API:   http://localhost:8000
MySQL Database:        localhost:3306
```

---

## CORS Headers (Required in Laravel)

```
Access-Control-Allow-Origin: http://localhost:8080
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Accept
Access-Control-Max-Age: 3600
```
