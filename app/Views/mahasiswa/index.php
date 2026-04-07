<?= $this->extend('layouts/Layout') ?>

<?= $this->section('content') ?>

<div class="card-glass">
  <div class="card-header">
    <h5><i class="bi bi-people-fill me-2" style="color:var(--gold)"></i>Data Mahasiswa</h5>
    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalTambah">
      <i class="bi bi-plus-lg"></i> Tambah Mahasiswa
    </button>
  </div>
  <div class="card-body">

    <!-- SEARCH BAR -->
    <div class="row mb-3">
      <div class="col-md-4">
        <div style="position:relative">
          <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aab4c8"></i>
          <input type="text" id="searchInput" class="form-control" style="padding-left:38px"
                 placeholder="Cari nama atau NIM..." onkeyup="filterTable()"/>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped" id="tabelMahasiswa">
        <thead>
          <tr>
            <th style="width:50px">No</th>
            <th>NIM</th>
            <th>Nama Lengkap</th>
            <th>Program Studi</th>
            <th class="text-center" style="width:130px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($mahasiswa)): ?>
            <?php foreach($mahasiswa as $i => $m): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td style="font-family:monospace;font-weight:500"><?= $m['nim'] ?></td>
              <td style="font-weight:500"><?= $m['nama'] ?></td>
              <td style="font-size:.84rem;color:var(--muted)"><?= $m['prodi'] ?></td>
              <td class="text-center">
                <div class="d-flex gap-1 justify-content-center">
                  <button class="btn btn-outline-secondary btn-sm"
                          onclick="editMahasiswa(<?= htmlspecialchars(json_encode($m)) ?>)"
                          title="Edit">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <a href="<?= base_url('mahasiswa/delete/'.$m['id']) ?>"
                     class="btn btn-outline-danger btn-sm"
                     onclick="return confirm('Hapus mahasiswa ini?')"
                     title="Hapus">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center py-5" style="color:var(--muted)">
                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px"></i>
                Belum ada data mahasiswa
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <?php if(!empty($pager)): ?>
      <div class="d-flex justify-content-end mt-3">
        <?= $pager->links('default', 'bootstrap_full') ?>
      </div>
    <?php endif; ?>

  </div>
</div>


<!-- ═══ MODAL TAMBAH ═══ -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Tambah Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('mahasiswa/simpan') ?>" method="post">
        <?= csrf_field() ?>
      <div class="modal-body">
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
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Mahasiswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= base_url('mahasiswa/update') ?>" method="post">
        <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_id"/>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">NIM <span style="color:red">*</span></label>
            <input type="text" name="nim" id="edit_nim" class="form-control" required/>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nama Lengkap <span style="color:red">*</span></label>
            <input type="text" name="nama" id="edit_nama" class="form-control" required/>
          </div>
          <div class="col-md-6">
            <label class="form-label">Program Studi <span style="color:red">*</span></label>
            <input type="text" name="prodi" id="edit_prodi" class="form-control" required/>
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
function editMahasiswa(data) {
  document.getElementById('edit_id').value       = data.id;
  document.getElementById('edit_nim').value      = data.nim;
  document.getElementById('edit_nama').value     = data.nama;
  document.getElementById('edit_prodi').value    = data.prodi;
  new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#tabelMahasiswa tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
</script>

<?= $this->endSection() ?>
