<?= $this->extend('layouts/Layout') ?>

<?= $this->section('content') ?>

<div class="card-glass">
  <div class="card-header">
    <h5><i class="bi bi-person-plus-fill me-2" style="color:var(--gold)"></i>Tambah Mahasiswa Baru</h5>
  </div>

  <div class="card-body">
    <form action="<?= base_url('mahasiswa') ?>" method="post">
      <?= csrf_field() ?>
      
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">NIM <span style="color:red">*</span></label>
          <input type="text" name="nim" class="form-control" placeholder="Contoh: 2021001001" minlength="5" maxlength="20" pattern="[0-9]+" title="Hanya angka yang diperbolehkan" required/>
        </div>

        <div class="col-md-6">
          <label class="form-label">Nama Lengkap <span style="color:red">*</span></label>
          <input type="text" name="nama" class="form-control" placeholder="Nama lengkap mahasiswa" minlength="3" maxlength="100" required/>
        </div>

        <div class="col-md-6">
          <label class="form-label">Program Studi <span style="color:red">*</span></label>
          <input type="text" name="prodi" class="form-control" placeholder="Contoh: Teknik Informatika" maxlength="100" required/>
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

<?= $this->endSection() ?>
