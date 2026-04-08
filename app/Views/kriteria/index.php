<?= $this->extend('layouts/Layout') ?>

<?= $this->section('content') ?>

<div class="row g-4">

  <!-- DAFTAR KRITERIA -->
  <div class="col-lg-8">
    <div class="card-glass">
      <div class="card-header">
        <h5><i class="bi bi-sliders me-2" style="color:var(--gold)"></i>Data Kriteria & Bobot</h5>
        <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalTambahKriteria">
          <i class="bi bi-plus-lg"></i> Tambah Kriteria
        </button>
      </div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead>
            <tr>
              <th style="width:50px">No</th>
              <th>Kode</th>
              <th>Nama Kriteria</th>
              <th class="text-center">Bobot (%)</th>
              <th class="text-center">Tipe</th>
              <th class="text-center" style="width:120px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($kriteria)): ?>
              <?php $total_bobot = 0; ?>
              <?php foreach($kriteria as $i => $k): ?>
              <?php 
                $total_bobot += $k->bobot; 
                $kode = $k->kode ?? ('C' . ($i + 1));
              ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td style="font-family:monospace;font-weight:600;color:var(--navy)"><?= strtoupper($kode) ?></td>
                <td style="font-weight:500"><?= $k->nama_kriteria ?></td>
                <td class="text-center">
                  <div style="display:flex;align-items:center;gap:8px;justify-content:center">
                    <div style="flex:1;background:#eee;border-radius:4px;height:6px;max-width:80px">
                      <div style="width:<?= min($k->bobot * 100, 100) ?>%;background:var(--gold);height:6px;border-radius:4px"></div>
                    </div>
                    <span style="font-weight:700;color:var(--navy);font-size:.9rem"><?= number_format($k->bobot * 100, 0) ?>%</span>
                  </div>
                </td>
                <td class="text-center">
                  <span style="font-size:.78rem;padding:4px 12px;border-radius:20px;font-weight:600;
                    background:<?= $k->tipe=='B' || $k->tipe=='benefit' ? 'rgba(26,122,74,.12)' : 'rgba(192,57,43,.1)' ?>;
                    color:<?= $k->tipe=='B' || $k->tipe=='benefit' ? 'var(--success)' : 'var(--danger)' ?>">
                    <?= ($k->tipe == 'benefit' || $k->tipe == 'B') ? '↑ Benefit' : '↓ Cost' ?>
                  </span>
                </td>
                <td class="text-center">
                  <div class="d-flex gap-1 justify-content-center">
                    <button class="btn btn-outline-secondary btn-sm"
                            onclick="editKriteria(<?= htmlspecialchars(json_encode($k)) ?>)"
                            title="Edit">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <form action="<?= base_url('kriteria/delete/'.$k->id) ?>" method="post" style="display:inline" onsubmit="return confirm('Hapus kriteria ini?')">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <!-- TOTAL ROW -->
              <tr style="background:rgba(201,168,76,.08)">
                <td colspan="3" style="font-weight:700;font-size:.875rem;color:var(--navy);padding-left:16px">
                  Total Bobot
                </td>
                <td class="text-center" style="font-weight:700;font-size:1rem;color:<?= $total_bobot==100 ? 'var(--success)' : 'var(--danger)' ?>">
                  <?= $total_bobot ?>%
                  <?php if($total_bobot != 100): ?>
                    <i class="bi bi-exclamation-triangle-fill ms-1" title="Total bobot harus 100%"></i>
                  <?php else: ?>
                    <i class="bi bi-check-circle-fill ms-1"></i>
                  <?php endif; ?>
                </td>
                <td colspan="2"></td>
              </tr>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-5" style="color:var(--muted)">
                  <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                  Belum ada data kriteria
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- PANEL INFO -->
  <div class="col-lg-4">
    <div class="card-glass">
      <div class="card-header"><h5><i class="bi bi-question-circle me-2" style="color:var(--gold)"></i>Panduan Kriteria</h5></div>
      <div class="card-body">
        <div style="font-size:.83rem;color:var(--muted);line-height:1.8">
          <p class="mb-3"><strong style="color:var(--navy)">Tipe Benefit</strong><br>
            Semakin <em>tinggi</em> nilainya, semakin <em>baik</em>.<br>
            Contoh: IPK, Prestasi Non-Akademik
          </p>
          <p class="mb-3"><strong style="color:var(--navy)">Tipe Cost</strong><br>
            Semakin <em>rendah</em> nilainya, semakin <em>baik</em>.<br>
            Contoh: Penghasilan Orang Tua
          </p>
          <hr style="border-color:var(--border)"/>
          <p class="mb-0"><strong style="color:var(--navy)">⚠ Perhatian</strong><br>
            Total bobot semua kriteria harus tepat <strong>100%</strong> sebelum proses perhitungan SAW dapat dijalankan.
          </p>
        </div>
      </div>
    </div>

    <div class="card-glass mt-4">
      <div class="card-header"><h5><i class="bi bi-list-check me-2" style="color:var(--gold)"></i>Kriteria Default</h5></div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <tbody>
            <tr><td style="font-size:.83rem">IPK</td><td class="text-end" style="font-size:.83rem;color:var(--muted)">Benefit</td></tr>
            <tr><td style="font-size:.83rem">Penghasilan Ortu</td><td class="text-end" style="font-size:.83rem;color:var(--muted)">Cost</td></tr>
            <tr><td style="font-size:.83rem">Jml. Tanggungan</td><td class="text-end" style="font-size:.83rem;color:var(--muted)">Benefit</td></tr>
            <tr><td style="font-size:.83rem">Prestasi Non-Akademik</td><td class="text-end" style="font-size:.83rem;color:var(--muted)">Benefit</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>


<!-- ═══ MODAL TAMBAH ═══ -->
<div class="modal fade" id="modalTambahKriteria" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Kriteria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('kriteria/simpan') ?>" method="post">
        <?= csrf_field() ?>
      <div class="modal-body">
        <div class="row g-3">
        <div class="row g-3">
          <div class="col-md-12">
            <label class="form-label">Nama Kriteria <span style="color:red">*</span></label>
            <input type="text" name="nama_kriteria" class="form-control" placeholder="Nama kriteria" minlength="3" maxlength="100" required/>
          </div>
          <div class="col-md-6">
            <label class="form-label">Bobot (0.00 - 1.00) <span style="color:red">*</span></label>
            <input type="number" name="bobot" class="form-control" placeholder="0.40" min="0" max="1" step="0.01" required/>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tipe <span style="color:red">*</span></label>
            <select name="tipe" class="form-select" required>
              <option value="">Pilih Tipe</option>
              <option value="B">Benefit (↑ lebih tinggi lebih baik)</option>
              <option value="C">Cost (↓ lebih rendah lebih baik)</option>
            </select>
          </div>
        </div>
        </div>
      </div>
      <div class="modal-footer border-top" style="border-color:var(--border)!important">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn-gold"><i class="bi bi-check-lg"></i> Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- ═══ MODAL EDIT ═══ -->
<div class="modal fade" id="modalEditKriteria" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Kriteria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('kriteria/update') ?>" method="post">
        <?= csrf_field() ?>
      <input type="hidden" name="id" id="ek_id"/>
      <div class="modal-body">
        <div class="row g-3">
        <div class="row g-3">
          <div class="col-md-12">
            <label class="form-label">Nama Kriteria</label>
            <input type="text" name="nama_kriteria" id="ek_nama" class="form-control" minlength="3" maxlength="100" required/>
          </div>
          <div class="col-md-6">
            <label class="form-label">Bobot (0.00 - 1.00)</label>
            <input type="number" name="bobot" id="ek_bobot" class="form-control" min="0" max="1" step="0.01" required/>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tipe</label>
            <select name="tipe" id="ek_tipe" class="form-select" required>
              <option value="B">Benefit</option>
              <option value="C">Cost</option>
            </select>
          </div>
        </div>
        </div>
      </div>
      <div class="modal-footer border-top" style="border-color:var(--border)!important">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn-gold"><i class="bi bi-check-lg"></i> Perbarui</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
function editKriteria(d) {
  document.getElementById('ek_id').value          = d.id;
  document.getElementById('ek_nama').value        = d.nama_kriteria;
  document.getElementById('ek_bobot').value       = d.bobot;
  document.getElementById('ek_tipe').value        = d.tipe;
  new bootstrap.Modal(document.getElementById('modalEditKriteria')).show();
}
</script>

<?= $this->endSection() ?>
