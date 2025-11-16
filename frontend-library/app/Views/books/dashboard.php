<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Page Header -->
    <div class="col-12">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 mb-0">Dashboard Perpustakaan</h1>
                    <p class="text-muted mb-0">Selamat datang di sistem manajemen perpustakaan digital</p>
                </div>
                <div class="col-auto">
                    <span class="text-muted"><?= date('l, d F Y') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="stat-number text-primary"><?= $stats['total_books'] ?? 0 ?></h2>
                    <p class="text-muted mb-0">Total Buku</p>
                </div>
                <div class="col-auto">
                    <i class="fas fa-book stat-icon text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="stat-number text-success"><?= $stats['available_books'] ?? 0 ?></h2>
                    <p class="text-muted mb-0">Buku Tersedia</p>
                </div>
                <div class="col-auto">
                    <i class="fas fa-check-circle stat-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="stat-number text-warning"><?= $stats['borrowed_books'] ?? 0 ?></h2>
                    <p class="text-muted mb-0">Buku Dipinjam</p>
                </div>
                <div class="col-auto">
                    <i class="fas fa-exchange-alt stat-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="stat-number text-info"><?= count($stats['categories'] ?? []) ?></h2>
                    <p class="text-muted mb-0">Kategori</p>
                </div>
                <div class="col-auto">
                    <i class="fas fa-tags stat-icon text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Books -->
    <div class="col-lg-8 mb-4">
        <div class="table-custom">
            <div class="p-4 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2 text-primary"></i>
                    Buku Terbaru
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentBooks)): ?>
                            <?php foreach ($recentBooks as $book): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-book text-primary me-3"></i>
                                            <div>
                                                <strong><?= esc($book['judul']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= esc($book['penerbit']) ?> (<?= $book['tahun_terbit'] ?>)</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($book['pengarang']) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark"><?= esc($book['kategori']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge-status <?= $book['status'] == 'Tersedia' ? 'badge-available' : 'badge-borrowed' ?>">
                                            <?= $book['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('/books/edit/' . $book['id']) ?>" class="action-btn btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-book-open fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data buku</p>
                                    <a href="<?= base_url('/books/create') ?>" class="btn btn-primary-custom btn-sm">
                                        <i class="fas fa-plus me-2"></i>Tambah Buku Pertama
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Categories Overview -->
    <div class="col-lg-4 mb-4">
        <div class="table-custom">
            <div class="p-4 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2 text-success"></i>
                    Kategori Buku
                </h5>
            </div>
            <div class="p-4">
                <?php if (!empty($stats['categories'])): ?>
                    <?php foreach ($stats['categories'] as $category): ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium"><?= $category['kategori'] ?></span>
                                <span class="text-muted"><?= $category['count'] ?> buku</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <?php 
                                $percentage = ($category['count'] / $stats['total_books']) * 100;
                                $colors = [
                                    'Fiksi' => 'bg-primary',
                                    'Non-Fiksi' => 'bg-success', 
                                    'Komik' => 'bg-warning',
                                    'all' => 'bg-info'
                                ];
                                ?>
                                <div class="progress-bar <?= $colors[$category['kategori']] ?? 'bg-secondary' ?>" 
                                     style="width: <?= $percentage ?>%">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-tags fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data kategori</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="table-custom mt-4">
            <div class="p-4 border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="p-4">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/books/create') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-2"></i>Tambah Buku Baru
                    </a>
                    <a href="<?= base_url('/books') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Lihat Semua Buku
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>