<?php
// views/beasiswa/penilaian.php
// $mahasiswa = array of mahasiswa
// $kriteria  = array of kriteria
// $penilaian = array[mahasiswa_id][kriteria_id] = nilai
?>

<div class="card-glass">
  <div class="card-header">
    <h5><i class="bi bi-pencil-square me-2" style="color:var(--gold)"></i>Input Penilaian Mahasiswa</h5>
    <div class="d-flex gap-2">
      <button class="btn-navy" onclick="isiContoh()" title="Isi semua dengan nilai contoh">
        <i class="bi bi-magic"></i> Isi Contoh
      </button>
      <button class="btn-gold" onclick="document.getElementById('formPenilaian').submit()">
        <i class="bi bi-save"></i> Simpan Semua
      </button>
    </div>
  </div>
  <div class="card-body">

    <!-- INFO KRITERIA -->
    <div style="background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2);border-radius:10px;padding:14px 18px;margin-bottom:20px">
      <div class="d-flex flex-wrap gap-3">
        <?php if(!empty($kriteria)): ?>
          <?php foreach($kriteria as $k): ?>
          <span style="font-size:.8rem">
            <strong style="color:var(--navy)"><?= $k['kode'] ?></strong>
            <span style="color:var(--muted)"> — <?= $k['nama_kriteria'] ?></span>
            <span style="font-size:.72rem;padding:2px 8px;border-radius:10px;margin-left:4px;
              background:<?= $k['tipe']=='benefit' ? 'rgba(26,122,74,.12)' : 'rgba(192,57,43,.1)' ?>;
              color:<?= $k['tipe']=='benefit' ? 'var(--success)' : 'var(--danger)' ?>">
              <?= $k['tipe'] == 'benefit' ? '↑' : '↓' ?> <?= $k['bobot'] ?>%
            </span>
          </span>
          <?php endforeach; ?>
        <?php else: ?>
          <span style="color:var(--muted);font-size:.83rem">Belum ada kriteria. Tambahkan kriteria terlebih dahulu.</span>
        <?php endif; ?>
      </div>
    </div>

    <form action="<?= base_url('penilaian/simpan') ?>" method="post" id="formPenilaian">
     <?= csrf_field() ?>
    <div class="table-responsive">
      <table class="table" id="tabelPenilaian">
        <thead>
          <tr>
            <th style="width:40px">No</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <?php if(!empty($kriteria)): ?>
              <?php foreach($kriteria as $k): ?>
              <th class="text-center" style="min-width:130px">
                <?= $k['kode'] ?><br>
                <span style="font-weight:400;font-size:.68rem;color:var(--muted)"><?= $k['nama_kriteria'] ?></span>
              </th>
              <?php endforeach; ?>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($mahasiswa)): ?>
            <?php foreach($mahasiswa as $i => $m): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td style="font-family:monospace;font-size:.84rem"><?= $m['nim'] ?></td>
              <td style="font-weight:500"><?= $m['nama'] ?></td>
              <?php if(!empty($kriteria)): ?>
                <?php foreach($kriteria as $k): ?>
                  <?php
                    $val = '';
                    if(!empty($penilaian[$m['id']][$k['id']])) {
                      $val = $penilaian[$m['id']][$k['id']];
                    }
                  ?>
                  <td class="text-center">
                    <input type="number"
                           name="nilai[<?= $m['id'] ?>][<?= $k['id'] ?>]"
                           class="form-control text-center nilai-input"
                           data-mhs="<?= $m['id'] ?>"
                           data-krt="<?= $k['id'] ?>"
                           data-tipe="<?= $k['tipe'] ?>"
                           value="<?= $val ?>"
                           placeholder="0"
                           min="0"
                           step="0.01"
                           style="max-width:110px;margin:0 auto;text-align:center"/>
                  </td>
                <?php endforeach; ?>
              <?php endif; ?>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="<?= 3 + count($kriteria ?? []) ?>" class="text-center py-5" style="color:var(--muted)">
                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                Belum ada data mahasiswa
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
      <p style="font-size:.8rem;color:var(--muted);margin:0">
        <i class="bi bi-info-circle me-1"></i>
        Masukkan nilai sesuai skala yang disepakati. Kosongkan jika belum dinilai.
      </p>
      <button type="submit" class="btn-gold">
        <i class="bi bi-save"></i> Simpan Semua Penilaian
      </button>
    </div>
    </form>

  </div>
</div>

<!-- PANDUAN SKALA NILAI -->
<div class="card-glass mt-4">
  <div class="card-header">
    <h5><i class="bi bi-info-circle me-2" style="color:var(--gold)"></i>Panduan Skala Nilai</h5>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-3">
        <div style="border:1px solid var(--border);border-radius:8px;padding:14px">
          <strong style="color:var(--navy);font-size:.875rem">IPK</strong>
          <ul style="margin:8px 0 0;padding-left:18px;font-size:.8rem;color:var(--muted)">
            <li>0.00 – 4.00</li>
            <li>Benefit: makin tinggi makin baik</li>
          </ul>
        </div>
      </div>
      <div class="col-md-3">
        <div style="border:1px solid var(--border);border-radius:8px;padding:14px">
          <strong style="color:var(--navy);font-size:.875rem">Penghasilan Ortu</strong>
          <ul style="margin:8px 0 0;padding-left:18px;font-size:.8rem;color:var(--muted)">
            <li>1 = &lt; Rp 1 juta</li>
            <li>2 = Rp 1–3 juta</li>
            <li>3 = Rp 3–5 juta</li>
            <li>4 = &gt; Rp 5 juta</li>
          </ul>
        </div>
      </div>
      <div class="col-md-3">
        <div style="border:1px solid var(--border);border-radius:8px;padding:14px">
          <strong style="color:var(--navy);font-size:.875rem">Jml. Tanggungan</strong>
          <ul style="margin:8px 0 0;padding-left:18px;font-size:.8rem;color:var(--muted)">
            <li>Jumlah anggota keluarga</li>
            <li>Benefit: makin besar makin baik</li>
          </ul>
        </div>
      </div>
      <div class="col-md-3">
        <div style="border:1px solid var(--border);border-radius:8px;padding:14px">
          <strong style="color:var(--navy);font-size:.875rem">Prestasi Non-Akademik</strong>
          <ul style="margin:8px 0 0;padding-left:18px;font-size:.8rem;color:var(--muted)">
            <li>1 = Tidak ada</li>
            <li>2 = Tingkat kampus</li>
            <li>3 = Tingkat kota/provinsi</li>
            <li>4 = Tingkat nasional</li>
            <li>5 = Internasional</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Validasi real-time: highlight sel yang kosong
document.querySelectorAll('.nilai-input').forEach(inp => {
  inp.addEventListener('input', function() {
    this.style.borderColor = this.value !== '' ? 'var(--gold)' : '';
  });
});

function isiContoh() {
  if(!confirm('Isi semua input dengan nilai contoh (untuk testing)?')) return;
  const inputs = document.querySelectorAll('.nilai-input');
  inputs.forEach(inp => {
    const tipe = inp.dataset.tipe;
    if(tipe === 'benefit') {
      inp.value = (Math.random() * 3 + 1).toFixed(2);
    } else {
      inp.value = Math.ceil(Math.random() * 4);
    }
    inp.style.borderColor = 'var(--gold)';
  });
}
</script>