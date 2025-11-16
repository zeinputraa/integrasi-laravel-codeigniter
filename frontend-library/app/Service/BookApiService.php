<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BookApiService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = getenv('api.baseURL') ?: 'http://localhost:8000/api/';
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 10,
            'verify'   => false, // For development only, disable SSL verification
        ]);
    }

    /**
     * Get all books with filters and pagination
     */
    public function getAllBooks($filters = [])
    {
        try {
            $query = [];
            
            if (!empty($filters['search'])) {
                $query['search'] = $filters['search'];
            }
            
            if (!empty($filters['kategori']) && $filters['kategori'] !== 'all') {
                $query['kategori'] = $filters['kategori'];
            }
            
            if (!empty($filters['status'])) {
                $query['status'] = $filters['status'];
            }
            
            if (!empty($filters['per_page'])) {
                $query['per_page'] = $filters['per_page'];
            }
            
            if (!empty($filters['page'])) {
                $query['page'] = $filters['page'];
            }

            $response = $this->client->get('books', [
                'query' => $query
            ]);

            $data = json_decode($response->getBody(), true);

            // Handle different API response formats
            return [
                'success' => true,
                'data' => $data['data'] ?? $data,
                'meta' => $data['meta'] ?? null
            ];

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get book by ID
     */
    public function getBookById($id)
    {
        try {
            $response = $this->client->get("books/{$id}");
            $data = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'data' => $data['data'] ?? $data
            ];

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Create new book
     */
    public function createBook($bookData)
    {
        try {
            $response = $this->client->post('books', [
                'json' => $bookData
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'message' => $data['message'] ?? 'Buku berhasil ditambahkan',
                'id' => $data['data']['id'] ?? null
            ];

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update book
     */
    public function updateBook($id, $bookData)
    {
        try {
            $response = $this->client->put("books/{$id}", [
                'json' => $bookData
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'message' => $data['message'] ?? 'Buku berhasil diupdate'
            ];

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Delete book
     */
    public function deleteBook($id)
    {
        try {
            $response = $this->client->delete("books/{$id}");
            $data = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'message' => $data['message'] ?? 'Buku berhasil dihapus'
            ];

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update book status
     */
    public function updateBookStatus($id, $status)
    {
        try {
            $response = $this->client->put("books/{$id}/status", [
                'json' => ['status' => $status]
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'message' => $data['message'] ?? 'Status buku berhasil diupdate'
            ];

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        try {
            $response = $this->client->get('books/statistics');
            $data = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'data' => $data['data'] ?? $data
            ];

        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API exceptions
     */
    private function handleException(RequestException $e)
    {
        $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : null;
        $body = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : null;

        return [
            'success' => false,
            'message' => $body['message'] ?? $e->getMessage() ?? 'Terjadi kesalahan saat menghubungi API',
            'status_code' => $statusCode,
            'data' => null
        ];
    }
}
