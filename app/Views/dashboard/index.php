<?= $this->extend('layouts/Layout') ?>

<?= $this->section('content') ?>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon navy"><i class="bi bi-people-fill"></i></div>
      <div>
        <h3><?= $total_mahasiswa ?? 0 ?></h3>
        <p>Total Mahasiswa</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon gold"><i class="bi bi-sliders"></i></div>
      <div>
        <h3><?= $total_kriteria ?? 0 ?></h3>
        <p>Total Kriteria</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
      <div>
        <h3><?= $total_lulus ?? 0 ?></h3>
        <p>Mahasiswa Lulus</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div>
      <div>
        <h3><?= $total_tidak_lulus ?? 0 ?></h3>
        <p>Tidak Lulus</p>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- TOP RANKING -->
  <div class="col-lg-7">
    <div class="card-glass">
      <div class="card-header">
        <h5><i class="bi bi-trophy-fill me-2" style="color:var(--gold)"></i>Top 5 Ranking Sementara</h5>
        <a href="<?= base_url('hasil') ?>" style="font-size:.8rem;color:var(--gold);text-decoration:none;">
          Lihat semua <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Rank</th>
              <th>Nama Mahasiswa</th>
              <th>NIM</th>
              <th class="text-end">Nilai Preferensi</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($top_ranking)): ?>
              <?php foreach($top_ranking as $i => $r): ?>
              <tr>
                <td><span class="badge-rank">#<?= $i+1 ?></span></td>
                <td style="font-weight:500"><?= $r['nama'] ?></td>
                <td style="color:var(--muted);font-size:.82rem"><?= $r['nim'] ?></td>
                <td class="text-end" style="font-weight:600;color:var(--navy)"><?= number_format($r['nilai_preferensi'],4) ?></td>
                <td class="text-center">
                  <span class="<?= ($i+1) <= ($total_lulus ?? 5) ? 'badge-lulus' : 'badge-tidak' ?>">
                    <?= ($i+1) <= ($total_lulus ?? 5) ? 'Lulus' : 'Tidak Lulus' ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center py-5" style="color:var(--muted)">
                  <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                  Belum ada data hasil perhitungan
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- QUICK ACTIONS + INFO -->
  <div class="col-lg-5">
    <div class="card-glass mb-4">
      <div class="card-header"><h5><i class="bi bi-lightning-fill me-2" style="color:var(--gold)"></i>Aksi Cepat</h5></div>
      <div class="card-body d-flex flex-column gap-2">
        <a href="<?= base_url('mahasiswa/tambah') ?>" class="btn-gold w-100 justify-content-center">
          <i class="bi bi-person-plus-fill"></i> Tambah Mahasiswa Baru
        </a>
        <a href="<?= base_url('penilaian') ?>" class="btn-navy w-100 justify-content-center">
          <i class="bi bi-pencil-square"></i> Input Penilaian
        </a>
        <form action="<?= base_url('hasil/hitung') ?>" method="post" class="m-0 p-0 w-100">
          <?= csrf_field() ?>
          <button type="submit" class="btn-navy w-100 justify-content-center" style="background:var(--success)">
            <i class="bi bi-calculator"></i> Hitung Ulang SAW
          </button>
        </form>
      </div>
    </div>

    <div class="card-glass">
      <div class="card-header"><h5><i class="bi bi-info-circle me-2" style="color:var(--gold)"></i>Kriteria Aktif</h5></div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead>
            <tr><th>Kriteria</th><th class="text-end">Bobot</th><th class="text-center">Tipe</th></tr>
          </thead>
          <tbody>
            <?php if(!empty($kriteria_list)): ?>
              <?php foreach($kriteria_list as $k): ?>
              <tr>
                <td style="font-size:.85rem"><?= $k['nama_kriteria'] ?></td>
                <td class="text-end" style="font-weight:600;font-size:.85rem"><?= number_format($k['bobot'] * 100, 0) ?>%</td>
                <td class="text-center">
                  <span style="font-size:.75rem;padding:3px 10px;border-radius:20px;background:<?= $k['tipe']=='B' ? 'rgba(26,122,74,.12)' : 'rgba(192,57,43,.1)' ?>;color:<?= $k['tipe']=='B' ? 'var(--success)' : 'var(--danger)' ?>">
                    <?= $k['tipe'] == 'B' ? 'Benefit' : 'Cost' ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3" class="text-center py-3" style="color:var(--muted);font-size:.83rem">Belum ada kriteria</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
