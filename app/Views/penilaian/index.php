<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="<?= csrf_hash() ?>">

<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="fas fa-edit me-2"></i>Input Nilai Mahasiswa
        </h5>
        <p class="text-muted small mb-0">Inputkan nilai untuk setiap kriteria per mahasiswa. Semua kriteria wajib diisi.</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tablePenilaian">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Mahasiswa</th>
                        <?php foreach ($kriteriaList as $krt) : ?>
                            <th class="text-center">
                                <?= esc($krt->nama_kriteria) ?>
                                <br>
                                <span class="badge bg-secondary small fw-normal"><?= $krt->tipe == 'B' ? 'Benefit' : 'Cost' ?></span>
                            </th>
                        <?php endforeach; ?>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mahasiswaList)) : ?>
                        <tr>
                            <td colspan="<?= count($kriteriaList) + 3 ?>" class="text-center py-4 text-muted">
                                Tidak ada data mahasiswa. Silakan <a href="<?= base_url('mahasiswa') ?>">tambah mahasiswa</a> terlebih dahulu.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($mahasiswaList as $index => $mhs) : ?>
                        <tr data-mhs-id="<?= $mhs->id ?>">
                            <td><?= $index + 1 ?></td>
                            <td>
                                <div class="fw-bold text-nowrap"><?= esc($mhs->nama) ?></div>
                                <div class="text-muted small"><?= esc($mhs->nim) ?></div>
                            </td>
                            
                            <?php foreach ($kriteriaList as $krt) : ?>
                                <td>
                                    <?php 
                                        $val = $scores[$mhs->id][$krt->id] ?? '';
                                    ?>
                                    <input type="number" step="any" 
                                           data-krt-id="<?= $krt->id ?>"
                                           value="<?= esc($val) ?>" 
                                           class="form-control form-control-sm text-center input-nilai"
                                           placeholder="0"
                                           required>
                                    <div class="invalid-feedback small"></div>
                                </td>
                            <?php endforeach; ?>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary btn-sm px-3 btn-simpan">
                                    <i class="fas fa-save me-1"></i>Simpan
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <!-- Message goes here -->
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastEl = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastEl);
        const toastBody = toastEl.querySelector('.toast-body');

        function showToast(message, type = 'success') {
            toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');
            if (type === 'success') {
                toastEl.classList.add('bg-success', 'text-white');
            } else {
                toastEl.classList.add('bg-danger', 'text-white');
            }
            toastBody.textContent = message;
            toast.show();
        }

        document.querySelectorAll('.btn-simpan').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const mhsId = row.dataset.mhsId;
                const inputs = row.querySelectorAll('.input-nilai');
                const btnOriginalContent = this.innerHTML;

                // Prepare data
                const data = {
                    nilai: {}
                };
                let hasEmpty = false;

                inputs.forEach(input => {
                    const krtId = input.dataset.krtId;
                    const val = input.value;
                    
                    input.classList.remove('is-invalid');
                    if (val === '') {
                        hasEmpty = true;
                        input.classList.add('is-invalid');
                        input.nextElementSibling.textContent = 'Harus diisi';
                    }
                    data.nilai[krtId] = val;
                });

                if (hasEmpty) {
                    showToast('Harap isi semua kriteria', 'danger');
                    return;
                }

                // Loading state
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                // Send AJAX
                fetch(`<?= base_url('penilaian') ?>/${mhsId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        showToast(res.message || 'Nilai berhasil disimpan');
                        // Update CSRF token if returned (recommended if security settings are strict)
                        if (res.csrf) {
                            document.querySelector('meta[name="csrf-token"]').content = res.csrf;
                        }
                    } else {
                        showToast(res.message || 'Gagal menyimpan nilai', 'danger');
                        if (res.errors) {
                            Object.keys(res.errors).forEach(key => {
                                // key format is "nilai.ID"
                                const krtId = key.split('.')[1];
                                const input = row.querySelector(`.input-nilai[data-krt-id="${krtId}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    input.nextElementSibling.textContent = res.errors[key];
                                }
                            });
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Terjadi kesalahan sistem', 'danger');
                })
                .finally(() => {
                    this.disabled = false;
                    this.innerHTML = btnOriginalContent;
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>
