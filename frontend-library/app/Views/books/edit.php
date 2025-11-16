<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 mb-0">Edit Buku</h1>
                    <p class="text-muted mb-0">Perbarui informasi buku <?= esc($book['judul']) ?></p>
                </div>
                <div class="col-auto">
                    <a href="<?= base_url('/books') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="form-custom">
            <form method="post" action="<?= base_url('/books/update/' . $book['id']) ?>" id="bookForm">
                <div class="row">
                    <!-- Judul Buku -->
                    <div class="col-md-12 mb-4">
                        <label for="judul" class="form-label fw-semibold">
                            <i class="fas fa-heading me-2 text-primary"></i>Judul Buku
                        </label>
                        <input type="text" class="form-control form-control-custom <?= session('errors.judul') ? 'is-invalid' : '' ?>" 
                               id="judul" name="judul" value="<?= old('judul', $book['judul']) ?>" 
                               placeholder="Masukkan judul buku" required>
                        <?php if (session('errors.judul')): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <?= session('errors.judul') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pengarang & Penerbit -->
                    <div class="col-md-6 mb-4">
                        <label for="pengarang" class="form-label fw-semibold">
                            <i class="fas fa-user me-2 text-success"></i>Pengarang
                        </label>
                        <input type="text" class="form-control form-control-custom <?= session('errors.pengarang') ? 'is-invalid' : '' ?>" 
                               id="pengarang" name="pengarang" value="<?= old('pengarang', $book['pengarang']) ?>" 
                               placeholder="Nama pengarang" required>
                        <?php if (session('errors.pengarang')): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <?= session('errors.pengarang') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="penerbit" class="form-label fw-semibold">
                            <i class="fas fa-building me-2 text-warning"></i>Penerbit
                        </label>
                        <input type="text" class="form-control form-control-custom <?= session('errors.penerbit') ? 'is-invalid' : '' ?>" 
                               id="penerbit" name="penerbit" value="<?= old('penerbit', $book['penerbit']) ?>" 
                               placeholder="Nama penerbit" required>
                        <?php if (session('errors.penerbit')): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <?= session('errors.penerbit') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tahun Terbit & Jumlah Halaman -->
                    <div class="col-md-6 mb-4">
                        <label for="tahun_terbit" class="form-label fw-semibold">
                            <i class="fas fa-calendar me-2 text-info"></i>Tahun Terbit
                        </label>
                        <input type="number" class="form-control form-control-custom <?= session('errors.tahun_terbit') ? 'is-invalid' : '' ?>" 
                               id="tahun_terbit" name="tahun_terbit" value="<?= old('tahun_terbit', $book['tahun_terbit']) ?>" 
                               min="1900" max="<?= date('Y') + 1 ?>" 
                               placeholder="Tahun terbit" required>
                        <?php if (session('errors.tahun_terbit')): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <?= session('errors.tahun_terbit') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="jumlah_halaman" class="form-label fw-semibold">
                            <i class="fas fa-file-alt me-2 text-secondary"></i>Jumlah Halaman
                        </label>
                        <input type="number" class="form-control form-control-custom <?= session('errors.jumlah_halaman') ? 'is-invalid' : '' ?>" 
                               id="jumlah_halaman" name="jumlah_halaman" value="<?= old('jumlah_halaman', $book['jumlah_halaman']) ?>" 
                               min="1" placeholder="Jumlah halaman" required>
                        <?php if (session('errors.jumlah_halaman')): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <?= session('errors.jumlah_halaman') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Kategori & Status -->
                    <div class="col-md-6 mb-4">
                        <label for="kategori" class="form-label fw-semibold">
                            <i class="fas fa-tags me-2 text-primary"></i>Kategori
                        </label>
                        <select class="form-select form-control-custom <?= session('errors.kategori') ? 'is-invalid' : '' ?>" 
                                id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Fiksi" <?= (old('kategori', $book['kategori']) == 'Fiksi') ? 'selected' : '' ?>>Fiksi</option>
                            <option value="Non-Fiksi" <?= (old('kategori', $book['kategori']) == 'Non-Fiksi') ? 'selected' : '' ?>>Non-Fiksi</option>
                            <option value="Komik" <?= (old('kategori', $book['kategori']) == 'Komik') ? 'selected' : '' ?>>Komik</option>
                        </select>
                        <?php if (session('errors.kategori')): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <?= session('errors.kategori') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="status" class="form-label fw-semibold">
                            <i class="fas fa-exchange-alt me-2 text-warning"></i>Status
                        </label>
                        <select class="form-select form-control-custom <?= session('errors.status') ? 'is-invalid' : '' ?>" 
                                id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="Tersedia" <?= (old('status', $book['status']) == 'Tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="Dipinjam" <?= (old('status', $book['status']) == 'Dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                        </select>
                        <?php if (session('errors.status')): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <?= session('errors.status') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- ISBN -->
                    <div class="col-12 mb-4">
                        <label for="isbn" class="form-label fw-semibold">
                            <i class="fas fa-barcode me-2 text-success"></i>ISBN
                        </label>
                        <input type="text" class="form-control form-control-custom <?= session('errors.isbn') ? 'is-invalid' : '' ?>" 
                               id="isbn" name="isbn" value="<?= old('isbn', $book['isbn']) ?>" 
                               placeholder="Nomor ISBN (10-13 digit)" required>
                        <div class="form-text">Masukkan nomor ISBN buku (contoh: 978-602-220-551-6)</div>
                        <?php if (session('errors.isbn')): ?>
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                <?= session('errors.isbn') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Book Info -->
                    <div class="col-12 mb-4">
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="fw-semibold">Informasi Sistem:</small>
                                    <div class="mt-1">
                                        <i class="fas fa-calendar me-2 text-muted"></i>
                                        <small>Dibuat: <?= date('d M Y', strtotime($book['created_at'])) ?></small>
                                    </div>
                                    <div>
                                        <i class="fas fa-sync me-2 text-muted"></i>
                                        <small>Diupdate: <?= date('d M Y', strtotime($book['updated_at'])) ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <small class="fw-semibold">ID Buku:</small>
                                    <div class="mt-1">
                                        <code>#<?= $book['id'] ?></code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <div>
                                <a href="<?= base_url('/books') ?>" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <a href="<?= base_url('/books/delete/' . $book['id']) ?>" 
                                   class="btn btn-outline-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    <i class="fas fa-trash me-2"></i>Hapus
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-save me-2"></i>Update Buku
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Actions -->
        <div class="form-custom mt-4">
            <h6 class="fw-semibold mb-3">
                <i class="fas fa-bolt me-2 text-warning"></i>Aksi Cepat
            </h6>
            <div class="row g-2">
                <div class="col-md-4">
                    <form method="post" action="<?= base_url('/books/update-status/' . $book['id']) ?>">
                        <input type="hidden" name="status" value="Tersedia">
                        <button type="submit" class="btn btn-success w-100 btn-sm">
                            <i class="fas fa-check me-1"></i>Set Tersedia
                        </button>
                    </form>
                </div>
                <div class="col-md-4">
                    <form method="post" action="<?= base_url('/books/update-status/' . $book['id']) ?>">
                        <input type="hidden" name="status" value="Dipinjam">
                        <button type="submit" class="btn btn-warning w-100 btn-sm">
                            <i class="fas fa-exchange-alt me-1"></i>Set Dipinjam
                        </button>
                    </form>
                </div>
                <div class="col-md-4">
                    <a href="<?= base_url('/books/create') ?>" class="btn btn-primary w-100 btn-sm">
                        <i class="fas fa-plus me-1"></i>Tambah Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookForm');
    const tahunTerbit = document.getElementById('tahun_terbit');
    
    // Set max year for publication year
    const currentYear = new Date().getFullYear();
    tahunTerbit.max = currentYear + 1;
    
    // Real-time validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate publication year
        if (tahunTerbit.value < 1900 || tahunTerbit.value > currentYear + 1) {
            showError(tahunTerbit, 'Tahun terbit harus antara 1900 dan ' + (currentYear + 1));
            isValid = false;
        }
        
        // Validate page count
        const jumlahHalaman = document.getElementById('jumlah_halaman');
        if (jumlahHalaman.value < 1) {
            showError(jumlahHalaman, 'Jumlah halaman minimal 1');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    function showError(field, message) {
        field.classList.add('is-invalid');
        let feedback = field.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.insertBefore(feedback, field.nextSibling);
        }
        feedback.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + message;
    }
});
</script>
<?= $this->endSection() ?>