<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 mb-0">Manajemen Buku</h1>
                    <p class="text-muted mb-0">Kelola koleksi buku perpustakaan</p>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('/books/create') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-2"></i>Tambah Buku
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="form-custom">
            <form method="get" action="<?= base_url('/books') ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="search-box position-relative">
                            <input type="text" name="search" class="form-control form-control-custom" 
                                   placeholder="Cari judul atau pengarang..." value="<?= esc($filters['search'] ?? '') ?>">
                            <i class="fas fa-search position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="kategori" class="form-select form-control-custom">
                            <option value="">Semua Kategori</option>
                            <option value="Fiksi" <?= ($filters['kategori'] ?? '') == 'Fiksi' ? 'selected' : '' ?>>Fiksi</option>
                            <option value="Non-Fiksi" <?= ($filters['kategori'] ?? '') == 'Non-Fiksi' ? 'selected' : '' ?>>Non-Fiksi</option>
                            <option value="Komik" <?= ($filters['kategori'] ?? '') == 'Komik' ? 'selected' : '' ?>>Komik</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select form-control-custom">
                            <option value="">Semua Status</option>
                            <option value="Tersedia" <?= ($filters['status'] ?? '') == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="Dipinjam" <?= ($filters['status'] ?? '') == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="per_page" class="form-select form-control-custom">
                            <option value="10" <?= ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10 per halaman</option>
                            <option value="25" <?= ($filters['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25 per halaman</option>
                            <option value="50" <?= ($filters['per_page'] ?? 10) == 50 ? 'selected' : '' ?>>50 per halaman</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary-custom w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Books Table -->
<div class="row">
    <div class="col-12">
        <div class="table-custom">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($books)): ?>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-book text-primary me-3"></i>
                                            <div>
                                                <strong><?= esc($book['judul']) ?></strong>
                                                <br>
                                                <small class="text-muted">ISBN: <?= esc($book['isbn']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($book['pengarang']) ?></td>
                                    <td><?= esc($book['penerbit']) ?></td>
                                    <td><?= $book['tahun_terbit'] ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-tag me-1"></i><?= esc($book['kategori']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="post" action="<?= base_url('/books/update-status/' . $book['id']) ?>" class="d-inline">
                                            <button type="submit" class="badge-status <?= $book['status'] == 'Tersedia' ? 'badge-available' : 'badge-borrowed' ?> border-0">
                                                <?= $book['status'] ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="<?= base_url('/books/edit/' . $book['id']) ?>" class="action-btn btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="get" action="<?= base_url('/books/delete/' . $book['id']) ?>" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                                <button type="submit" class="action-btn btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data buku</h5>
                                    <p class="text-muted mb-3">Belum ada buku yang terdaftar dalam sistem</p>
                                    <a href="<?= base_url('/books/create') ?>" class="btn btn-primary-custom">
                                        <i class="fas fa-plus me-2"></i>Tambah Buku Pertama
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if (isset($pagination) && $pagination['last_page'] > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan <?= count($books) ?> dari <?= $pagination['total'] ?> buku
                </div>
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                            <li class="page-item <?= $pagination['current_page'] == $i ? 'active' : '' ?>">
                                <a class="page-link" href="<?= base_url('/books?page=' . $i . '&' . http_build_query($filters)) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>