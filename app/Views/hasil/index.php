<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
    <?= $title ?? 'Hasil SAW' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-4 align-items-center">
    <div class="col">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-chart-line text-primary me-2"></i>
            Hasil Perhitungan SAW
        </h2>
        <p class="text-muted mb-0">Ranking kandidat beasiswa berdasarkan kriteria yang telah ditentukan.</p>
    </div>
    <div class="col-auto">
        <form action="<?= base_url('hitung') ?>" method="POST" id="formHitung">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-primary shadow-sm px-4">
                <i class="fas fa-calculator me-2"></i> Hitung Ulang SAW
            </button>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary border-0">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase fs-xs fw-bold" style="width: 80px;">Rank</th>
                        <th class="py-3 text-uppercase fs-xs fw-bold">Mahasiswa</th>
                        <th class="py-3 text-uppercase fs-xs fw-bold">NIM</th>
                        <th class="py-3 text-uppercase fs-xs fw-bold">Prodi</th>
                        <th class="pe-4 py-3 text-uppercase fs-xs fw-bold text-end">Nilai Preferensi (V)</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (!empty($hasil)) : ?>
                        <?php foreach ($hasil as $h) : ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center justify-content-center bg-<?= $h->ranking <= 3 ? 'warning-subtle' : 'light' ?> rounded-circle fw-bold text-<?= $h->ranking <= 3 ? 'warning' : 'secondary' ?>" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                        <?= $h->ranking ?>
                                    </div>
                                </td>
                                <td class="fw-semibold text-dark"><?= esc($h->nama) ?></td>
                                <td class="text-secondary"><?= esc($h->nim) ?></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info fw-medium border border-info-subtle px-2 py-1">
                                        <?= esc($h->prodi) ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <span class="fw-bold text-primary fs-5">
                                        <?= number_format($h->nilai_preferensi, 4) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-database fa-3x mb-3 d-block opacity-25"></i>
                                <p class="mb-0">Belum ada data hasil perhitungan.</p>
                                <small>Silakan klik tombol "Hitung SAW" untuk memulai kalkulasi.</small>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .fs-xs { font-size: 0.75rem; letter-spacing: 0.05em; }
    .bg-warning-subtle { background-color: #fef3c7 !important; color: #92400e !important; }
    .bg-info-subtle { background-color: #e0f2fe !important; color: #0369a1 !important; border-color: #bae6fd !important; }
    .table thead th { border-bottom: none; }
    .table tbody tr { transition: all 0.2s; }
    .table tbody tr:hover { background-color: #f8fafc; }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Optional: Add loading state to button when calculating
    document.getElementById('formHitung').addEventListener('submit', function() {
        const btn = this.querySelector('button');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghitung...';
    });
</script>
<?= $this->endSection() ?>
