<?= $this->extend('layouts/Layout') ?>

<?= $this->section('content') ?>

<!-- HEADER AKSI -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <p style="font-size:.875rem;color:var(--muted);margin:0">
      Hasil Perhitungan SAW — Simple Additive Weighting
    </p>
  </div>
  <div class="d-flex gap-2">
    <form action="<?= base_url('hasil/hitung') ?>" method="post" class="m-0">
      <?= csrf_field() ?>
      <button type="submit" class="btn-gold" onclick="return confirm('Jalankan ulang perhitungan SAW?')">
        <i class="bi bi-calculator"></i> Hitung Ulang
      </button>
    </form>
    <a href="<?= base_url('hasil/cetak') ?>" class="btn-navy" target="_blank">
      <i class="bi bi-printer"></i> Cetak
    </a>
  </div>
</div>


<!-- ═══ TAB NAVIGATION ═══ -->
<ul class="nav nav-tabs mb-0" id="hasilTab" style="border-bottom:2px solid var(--border)">
  <li class="nav-item">
    <a class="nav-link active" data-bs-toggle="tab" href="#tabRanking"
       style="font-size:.875rem;font-weight:500;color:var(--navy)">
      <i class="bi bi-trophy me-1"></i>Ranking Akhir
    </a>
  </li>
  <!-- Note: Normalisasi and Preferensi are hidden if data is not provided by controller -->
  <?php if(isset($normalisasi)): ?>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#tabNormalisasi"
       style="font-size:.875rem;font-weight:500;color:var(--navy)">
      <i class="bi bi-grid-3x3 me-1"></i>Matriks Normalisasi
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-bs-toggle="tab" href="#tabPreferensi"
       style="font-size:.875rem;font-weight:500;color:var(--navy)">
      <i class="bi bi-bar-chart me-1"></i>Nilai Preferensi
    </a>
  </li>
  <?php endif; ?>
</ul>

<div class="tab-content">

  <!-- ═══ TAB 1: RANKING ═══ -->
  <div class="tab-pane fade show active" id="tabRanking">
    <div class="card-glass" style="border-top-left-radius:0;border-top-right-radius:0">
      <div class="card-body p-0">

        <!-- SUMMARY BADGE -->
        <div class="d-flex gap-3 p-4 border-bottom" style="border-color:var(--border)!important">
          <div style="text-align:center;padding:12px 24px;background:rgba(26,122,74,.08);border-radius:10px;border:1px solid rgba(26,122,74,.2)">
            <div style="font-size:1.6rem;font-weight:700;color:var(--success)"><?= $batas_lulus ?? 5 ?></div>
            <div style="font-size:.75rem;color:var(--muted)">Kuota Lulus</div>
          </div>
          <div style="text-align:center;padding:12px 24px;background:rgba(192,57,43,.07);border-radius:10px;border:1px solid rgba(192,57,43,.2)">
            <div style="font-size:1.6rem;font-weight:700;color:var(--danger)"><?= max(0, count($hasil ?? []) - ($batas_lulus ?? 5)) ?></div>
            <div style="font-size:.75rem;color:var(--muted)">Tidak Lulus</div>
          </div>
          <div style="text-align:center;padding:12px 24px;background:rgba(15,31,61,.06);border-radius:10px;border:1px solid rgba(15,31,61,.12)">
            <div style="font-size:1.6rem;font-weight:700;color:var(--navy)"><?= count($hasil ?? []) ?></div>
            <div style="font-size:.75rem;color:var(--muted)">Total Peserta</div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th style="width:60px">Rank</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Program Studi</th>
                <th class="text-end">Nilai Preferensi</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($hasil)): ?>
                <?php foreach($hasil as $i => $h): ?>
                <?php $lulus = ($h->ranking) <= ($batas_lulus ?? 5); ?>
                <tr style="<?= $lulus ? 'background:rgba(26,122,74,.04)' : '' ?>">
                  <td>
                    <?php if($h->ranking == 1): ?>
                      <span style="font-size:1.3rem">🥇</span>
                    <?php elseif($h->ranking == 2): ?>
                      <span style="font-size:1.3rem">🥈</span>
                    <?php elseif($h->ranking == 3): ?>
                      <span style="font-size:1.3rem">🥉</span>
                    <?php else: ?>
                      <span class="badge-rank">#<?= $h->ranking ?></span>
                    <?php endif; ?>
                  </td>
                  <td style="font-family:monospace;font-size:.84rem"><?= $h->nim ?></td>
                  <td style="font-weight:500"><?= $h->nama ?></td>
                  <td style="font-size:.83rem;color:var(--muted)"><?= $h->prodi ?></td>
                  <td class="text-end">
                    <strong style="font-size:1rem;color:var(--navy)"><?= number_format($h->nilai_preferensi ?? 0, 4) ?></strong>
                  </td>
                  <td class="text-center">
                    <?php if($lulus): ?>
                      <span class="badge-lulus"><i class="bi bi-check-circle me-1"></i>Lulus</span>
                    <?php else: ?>
                      <span class="badge-tidak"><i class="bi bi-x-circle me-1"></i>Tidak Lulus</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-5" style="color:var(--muted)">
                    <i class="bi bi-calculator" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                    Belum ada hasil perhitungan. Klik <strong>Hitung Ulang</strong> untuk memproses.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php if(isset($normalisasi)): ?>
  <!-- ═══ TAB 2: NORMALISASI ═══ -->
  <div class="tab-pane fade" id="tabNormalisasi">
    <!-- Content omitted for brevity in this specific task unless calculation logic is fully moved -->
  </div>
  <?php endif; ?>

</div><!-- end tab-content -->

<style>
  .nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    border-radius: 0;
    padding: 12px 20px;
    color: var(--muted) !important;
    font-size: .875rem;
    font-weight: 500;
  }
  .nav-tabs .nav-link.active {
    color: var(--navy) !important;
    border-bottom-color: var(--gold);
    background: none;
  }
  .nav-tabs .nav-link:hover { color: var(--navy) !important; }

  @media print {
    #sidebar, .topbar, .d-flex.justify-content-between, .nav-tabs { display: none !important; }
    #main { margin-left: 0; }
    .tab-pane { display: block !important; opacity: 1 !important; }
  }
</style>

<?= $this->endSection() ?>
