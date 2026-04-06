<?php
// views/mahasiswa/tambah.php
// Form untuk menambah mahasiswa baru
?>

<div class="card-glass">
  <div class="card-header">
    <h5><i class="bi bi-person-plus-fill me-2" style="color:var(--gold)"></i>Tambah Mahasiswa Baru</h5>
  </div>

  <div class="card-body">
    <form action="<?= base_url('mahasiswa/simpan') ?>" method="post">
      <?= csrf_field() ?>
      
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">NIM <span style="color:red">*</span></label>
          <input type="text" name="nim" class="form-control" placeholder="Contoh: 2021001001" required/>
        </div>

        <div class="col-md-6">
          <label class="form-label">Nama Lengkap <span style="color:red">*</span></label>
          <input type="text" name="nama" class="form-control" placeholder="Nama lengkap mahasiswa" required/>
        </div>

        <div class="col-md-6">
          <label class="form-label">Program Studi <span style="color:red">*</span></label>
          <input type="text" name="prodi" class="form-control" placeholder="Contoh: Teknik Informatika" required/>
        </div>

        <div class="col-md-3">
          <label class="form-label">Semester</label>
          <select name="semester" class="form-select">
            <option value="">Pilih</option>
            <?php for($s=1;$s<=14;$s++): ?>
              <option value="<?= $s ?>"><?= $s ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">No. HP</label>
          <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx"/>
        </div>

        <div class="col-12">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap (opsional)"></textarea>
        </div>

        <div class="col-12">
          <div class="d-flex gap-2 justify-content-end">
            <a href="<?= base_url('mahasiswa') ?>" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn-gold">
              <i class="bi bi-check-lg"></i> Simpan Mahasiswa
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
