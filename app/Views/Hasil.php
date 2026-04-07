<?php
// views/beasiswa/hasil.php
// $hasil         = array hasil akhir dengan ranking
// $normalisasi   = array matriks ternormalisasi
// $kriteria      = array kriteria
// $mahasiswa     = array mahasiswa (untuk referensi)
// $batas_lulus   = jumlah kuota beasiswa (int)
?>

<!-- HEADER AKSI -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <p style="font-size:.875rem;color:var(--muted);margin:0">
      Hasil perhitungan metode SAW — Simple Additive Weighting
    </p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= base_url('hasil/hitung') ?>" class="btn-gold"
       onclick="return confirm('Jalankan ulang perhitungan SAW?')">
      <i class="bi bi-calculator"></i> Hitung Ulang
    </a>
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
</ul>

<div class="tab-content">

  <!-- ═══ TAB 1: RANKING ═══ -->
  <div class="tab-pane fade show active" id="tabRanking">
    <div class="card-glass" style="border-top-left-radius:0;border-top-right-radius:0">
      <div class="card-body p-0">

        <!-- SUMMARY BADGE -->
        <div class="d-flex gap-3 p-4 border-bottom" style="border-color:var(--border)!important">
          <div style="text-align:center;padding:12px 24px;background:rgba(26,122,74,.08);border-radius:10px;border:1px solid rgba(26,122,74,.2)">
            <div style="font-size:1.6rem;font-weight:700;color:var(--success)"><?= $batas_lulus ?? 0 ?></div>
            <div style="font-size:.75rem;color:var(--muted)">Kuota Lulus</div>
          </div>
          <div style="text-align:center;padding:12px 24px;background:rgba(192,57,43,.07);border-radius:10px;border:1px solid rgba(192,57,43,.2)">
            <div style="font-size:1.6rem;font-weight:700;color:var(--danger)"><?= max(0, count($hasil ?? []) - ($batas_lulus ?? 0)) ?></div>
            <div style="font-size:.75rem;color:var(--muted)">Tidak Lulus</div>
          </div>
          <div style="text-align:center;padding:12px 24px;background:rgba(15,31,61,.06);border-radius:10px;border:1px solid rgba(15,31,61,.12)">
            <div style="font-size:1.6rem;font-weight:700;color:var(--navy)"><?= count($hasil ?? []) ?></div>
            <div style="font-size:.75rem;color:var(--muted)">Total Peserta</div>
          </div>
          <!-- FORM UBAH KUOTA -->
          <form action="<?= base_url('hasil/set_kuota') ?>" method="post" class="ms-auto d-flex align-items-center gap-2">
           <?= csrf_field() ?>
           <label style="font-size:.82rem;color:var(--muted);white-space:nowrap">Kuota beasiswa:</label>
           <input type="number" name="kuota" value="<?= $batas_lulus ?? 0 ?>"
                min="1" class="form-control" style="width:80px"/>
           <button type="submit" class="btn-navy" style="white-space:nowrap">
           <i class="bi bi-check-lg"></i> Set
           </button>
          </form>
        </div>

        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th style="width:60px">Rank</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Program Studi</th>
                <?php if(!empty($kriteria)): ?>
                  <?php foreach($kriteria as $k): ?>
                  <th class="text-center"><?= $k['kode'] ?></th>
                  <?php endforeach; ?>
                <?php endif; ?>
                <th class="text-end">Nilai Preferensi</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($hasil)): ?>
                <?php foreach($hasil as $i => $h): ?>
                <?php $lulus = ($i + 1) <= ($batas_lulus ?? 0); ?>
                <tr style="<?= $lulus ? 'background:rgba(26,122,74,.04)' : '' ?>">
                  <td>
                    <?php if($i == 0): ?>
                      <span style="font-size:1.3rem">🥇</span>
                    <?php elseif($i == 1): ?>
                      <span style="font-size:1.3rem">🥈</span>
                    <?php elseif($i == 2): ?>
                      <span style="font-size:1.3rem">🥉</span>
                    <?php else: ?>
                      <span class="badge-rank">#<?= $i+1 ?></span>
                    <?php endif; ?>
                  </td>
                  <td style="font-family:monospace;font-size:.84rem"><?= $h['nim'] ?></td>
                  <td style="font-weight:500"><?= $h['nama'] ?></td>
                  <td style="font-size:.83rem;color:var(--muted)"><?= $h['prodi'] ?></td>
                  <?php if(!empty($kriteria)): ?>
                    <?php foreach($kriteria as $k): ?>
                    <td class="text-center" style="font-size:.84rem">
                      <?= isset($h['nilai'][$k['id']]) ? number_format($h['nilai'][$k['id']], 2) : '-' ?>
                    </td>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  <td class="text-end">
                    <strong style="font-size:1rem;color:var(--navy)"><?= number_format($h['nilai_preferensi'] ?? 0, 4) ?></strong>
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
                  <td colspan="<?= 6 + count($kriteria ?? []) ?>" class="text-center py-5" style="color:var(--muted)">
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


  <!-- ═══ TAB 2: NORMALISASI ═══ -->
  <div class="tab-pane fade" id="tabNormalisasi">
    <div class="card-glass" style="border-top-left-radius:0;border-top-right-radius:0">
      <div class="card-body">
        <div style="background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2);border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:.82rem;color:var(--muted)">
          <strong style="color:var(--navy)">Rumus Normalisasi SAW:</strong>
          Benefit: r<sub>ij</sub> = x<sub>ij</sub> / max(x<sub>j</sub>) &nbsp;|&nbsp;
          Cost: r<sub>ij</sub> = min(x<sub>j</sub>) / x<sub>ij</sub>
        </div>
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th>Nama Mahasiswa</th>
                <?php if(!empty($kriteria)): ?>
                  <?php foreach($kriteria as $k): ?>
                  <th class="text-center">
                    <?= $k['kode'] ?>
                    <span style="font-size:.68rem;font-weight:400;color:<?= $k['tipe']=='benefit' ? 'var(--success)' : 'var(--danger)' ?>">
                      (<?= $k['tipe'] == 'benefit' ? '↑' : '↓' ?>)
                    </span>
                  </th>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($normalisasi)): ?>
                <?php foreach($normalisasi as $n): ?>
                <tr>
                  <td style="font-weight:500"><?= $n['nama'] ?></td>
                  <?php if(!empty($kriteria)): ?>
                    <?php foreach($kriteria as $k): ?>
                    <td class="text-center" style="font-family:monospace;font-size:.84rem">
                      <?= isset($n['r'][$k['id']]) ? number_format($n['r'][$k['id']], 4) : '-' ?>
                    </td>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="<?= 1 + count($kriteria ?? []) ?>" class="text-center py-4" style="color:var(--muted)">Belum ada data normalisasi</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


  <!-- ═══ TAB 3: NILAI PREFERENSI ═══ -->
  <div class="tab-pane fade" id="tabPreferensi">
    <div class="card-glass" style="border-top-left-radius:0;border-top-right-radius:0">
      <div class="card-body">
        <div style="background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2);border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:.82rem;color:var(--muted)">
          <strong style="color:var(--navy)">Rumus Nilai Preferensi:</strong>
          V<sub>i</sub> = Σ (w<sub>j</sub> × r<sub>ij</sub>) — bobot × nilai normalisasi tiap kriteria
        </div>
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th>Nama Mahasiswa</th>
                <?php if(!empty($kriteria)): ?>
                  <?php foreach($kriteria as $k): ?>
                  <th class="text-center" style="font-size:.78rem">
                    <?= $k['kode'] ?><br>
                    <span style="font-weight:400;color:var(--muted)">(w=<?= $k['bobot'] ?>%)</span>
                  </th>
                  <?php endforeach; ?>
                <?php endif; ?>
                <th class="text-end">Vi (Total)</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($hasil)): ?>
                <!-- BOBOT ROW -->
                <tr style="background:rgba(201,168,76,.07)">
                  <td style="font-weight:600;font-size:.82rem;color:var(--navy)">Bobot (wj)</td>
                  <?php if(!empty($kriteria)): ?>
                    <?php foreach($kriteria as $k): ?>
                    <td class="text-center" style="font-weight:700;font-size:.85rem;color:var(--gold)">
                      <?= $k['bobot']/100 ?>
                    </td>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  <td></td>
                </tr>
                <?php foreach($hasil as $h): ?>
                <tr>
                  <td style="font-weight:500"><?= $h['nama'] ?></td>
                  <?php if(!empty($kriteria)): ?>
                    <?php foreach($kriteria as $k): ?>
                    <td class="text-center" style="font-family:monospace;font-size:.83rem">
                      <?php
                        // nilai setelah dikalikan bobot
                        $v = isset($h['v_per_kriteria'][$k['id']]) ? $h['v_per_kriteria'][$k['id']] : '-';
                        echo is_numeric($v) ? number_format($v, 4) : $v;
                      ?>
                    </td>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  <td class="text-end">
                    <strong style="color:var(--navy)"><?= number_format($h['nilai_preferensi'] ?? 0, 4) ?></strong>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="<?= 2 + count($kriteria ?? []) ?>" class="text-center py-4" style="color:var(--muted)">Belum ada data preferensi</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

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