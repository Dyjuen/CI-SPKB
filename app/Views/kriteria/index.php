<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Daftar Kriteria<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold mb-0">Manajemen Kriteria & Bobot</h1>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="fas fa-plus me-2"></i>Tambah Kriteria
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted fw-semi-bold">#</th>
                        <th class="py-3 text-muted fw-semi-bold">Nama Kriteria</th>
                        <th class="py-3 text-muted fw-semi-bold">Bobot</th>
                        <th class="py-3 text-muted fw-semi-bold">Tipe</th>
                        <th class="pe-4 py-3 text-end text-muted fw-semi-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kriteria)) : ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                                Belum ada kriteria yang ditambahkan.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($kriteria as $index => $k) : ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $index + 1 ?></td>
                                <td class="fw-medium"><?= esc($k->nama_kriteria) ?></td>
                                <td>
                                    <span class="badge bg-indigo-subtle text-indigo px-2 py-1 rounded">
                                        <?= number_format($k->bobot, 2) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($k->tipe === 'B') : ?>
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded">Benefit</span>
                                    <?php else : ?>
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded">Cost</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-pill px-2" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li>
                                                <button class="dropdown-item py-2 edit-btn" data-id="<?= $k->id ?>">
                                                    <i class="fas fa-edit me-2 text-primary"></i>Edit
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item py-2 text-danger delete-btn" data-id="<?= $k->id ?>" data-name="<?= esc($k->nama_kriteria) ?>">
                                                    <i class="fas fa-trash-alt me-2"></i>Hapus
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Kriteria Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('kriteria') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_kriteria" class="form-label small fw-bold text-muted">Nama Kriteria</label>
                        <input type="text" class="form-control" name="nama_kriteria" id="nama_kriteria" placeholder="Contoh: Indeks Prestasi Kumulatif" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bobot" class="form-label small fw-bold text-muted">Bobot (0.00 - 1.00)</label>
                            <input type="number" step="0.01" min="0" max="1" class="form-control" name="bobot" id="bobot" placeholder="0.40" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipe" class="form-label small fw-bold text-muted">Tipe</label>
                            <select class="form-select" name="tipe" id="tipe" required>
                                <option value="" disabled selected>Pilih Tipe</option>
                                <option value="B">Benefit</option>
                                <option value="C">Cost</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Kriteria</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Kriteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body p-4">
                    <div id="editLoading" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="editFields">
                        <div class="mb-3">
                            <label for="edit_nama_kriteria" class="form-label small fw-bold text-muted">Nama Kriteria</label>
                            <input type="text" class="form-control" name="nama_kriteria" id="edit_nama_kriteria" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_bobot" class="form-label small fw-bold text-muted">Bobot (0.00 - 1.00)</label>
                                <input type="number" step="0.01" min="0" max="1" class="form-control" name="bobot" id="edit_bobot" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_tipe" class="form-label small fw-bold text-muted">Tipe</label>
                                <select class="form-select" name="tipe" id="edit_tipe" required>
                                    <option value="B">Benefit</option>
                                    <option value="C">Cost</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Kriteria</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3">
                    <i class="fas fa-exclamation-circle fa-4x"></i>
                </div>
                <h5 class="fw-bold mb-2">Hapus Kriteria?</h5>
                <p class="text-muted small mb-0">Apakah Anda yakin ingin menghapus kriteria <strong id="deleteTargetName"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <form id="deleteForm" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .text-indigo { color: #4f46e5; }
    .bg-indigo-subtle { background-color: #e0e7ff; }
    .bg-success-subtle { background-color: #d1fae5; }
    .bg-warning-subtle { background-color: #fef3c7; }
    .fw-semi-bold { font-weight: 600; }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const editForm = document.getElementById('editForm');
        const deleteForm = document.getElementById('deleteForm');

        // Edit Button Handler
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');
                editModal.show();
                
                // Show loading, hide fields
                document.getElementById('editFields').classList.add('d-none');
                document.getElementById('editLoading').classList.remove('d-none');
                
                try {
                    const response = await fetch(`<?= base_url('kriteria') ?>/${id}/json`);
                    const data = await response.json();
                    
                    if (response.ok) {
                        document.getElementById('edit_nama_kriteria').value = data.nama_kriteria;
                        document.getElementById('edit_bobot').value = data.bobot;
                        document.getElementById('edit_tipe').value = data.tipe;
                        editForm.action = `<?= base_url('kriteria') ?>/${id}`;
                        
                        // Hide loading, show fields
                        document.getElementById('editFields').classList.remove('d-none');
                        document.getElementById('editLoading').classList.add('d-none');
                    } else {
                        alert('Gagal mengambil data kriteria');
                        editModal.hide();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data');
                    editModal.hide();
                }
            });
        });

        // Delete Button Handler
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                document.getElementById('deleteTargetName').innerText = name;
                deleteForm.action = `<?= base_url('kriteria') ?>/delete/${id}`; // Note: spoofing handles the method
                // Alternatively, if route is $routes->delete('(:num)', '...'), 
                // we should use $routes->post('delete/(:num)', '...') or similar if form doesn't support DELETE directly.
                // The plan says $routes->delete('(:num)', ...) so we need CSRF + _method=DELETE
                deleteForm.action = `<?= base_url('kriteria') ?>/${id}`;
                
                deleteModal.show();
            });
        });
    });
</script>
<?= $this->endSection() ?>
