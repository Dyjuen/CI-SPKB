<?= $this->extend('layouts/Layout') ?>

<?= $this->section('content') ?>

<!-- CSRF Token for AJAX -->
<meta name="csrf-hash" content="<?= csrf_hash() ?>">

<div class="card-glass">
  <div class="card-header">
    <h5><i class="bi bi-pencil-square me-2" style="color:var(--gold)"></i>Input Nilai Mahasiswa</h5>
    <div class="d-flex gap-2">
      <button class="btn-navy" onclick="isiContoh()" title="Isi semua dengan nilai contoh">
        <i class="bi bi-magic"></i> Isi Contoh
      </button>
      <button class="btn-gold" id="btnSimpanSemua">
        <i class="bi bi-save"></i> Simpan Semua
      </button>
    </div>
  </div>
  <div class="card-body">

    <!-- INFO KRITERIA -->
    <div style="background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2);border-radius:10px;padding:14px 18px;margin-bottom:20px">
      <div class="d-flex flex-wrap gap-3">
        <?php if(!empty($kriteriaList)): ?>
          <?php foreach($kriteriaList as $k): ?>
          <span style="font-size:.8rem">
            <strong style="color:var(--navy)"><?= 'C' . $k['id'] ?></strong>
            <span style="color:var(--muted)"> — <?= $k['nama_kriteria'] ?></span>
            <span style="font-size:.72rem;padding:2px 8px;border-radius:10px;margin-left:4px;
              background:<?= $k['tipe']=='B' || $k['tipe']=='benefit' ? 'rgba(26,122,74,.12)' : 'rgba(192,57,43,.1)' ?>;
              color:<?= $k['tipe']=='B' || $k['tipe']=='benefit' ? 'var(--success)' : 'var(--danger)' ?>">
              <?= ($k['tipe'] == 'B' || $k['tipe'] == 'benefit') ? '↑' : '↓' ?> <?= $k['bobot'] ?>%
            </span>
          </span>
          <?php endforeach; ?>
        <?php else: ?>
          <span style="color:var(--muted);font-size:.83rem">Belum ada kriteria. Tambahkan kriteria terlebih dahulu.</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table" id="tabelPenilaian">
        <thead>
          <tr>
            <th style="width:40px">No</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <?php if(!empty($kriteriaList)): ?>
              <?php foreach($kriteriaList as $k): ?>
              <th class="text-center" style="min-width:130px">
                <?= 'C' . $k['id'] ?><br>
                <span style="font-weight:400;font-size:.68rem;color:var(--muted)"><?= $k['nama_kriteria'] ?></span>
              </th>
              <?php endforeach; ?>
            <?php endif; ?>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($mahasiswaList)): ?>
            <?php
              $perPage     = $pager ? $pager->getPageCount() > 0 ? 10 : 10 : 10;
              $currentPage = $pager ? $pager->getCurrentPage() : 1;
              $offset      = ($currentPage - 1) * $perPage;
            ?>
            <?php foreach($mahasiswaList as $i => $m): ?>
            <tr data-mhs-id="<?= $m['id'] ?>">
              <td><?= $offset + $i + 1 ?></td>
              <td style="font-family:monospace;font-size:.84rem"><?= $m['nim'] ?></td>
              <td style="font-weight:500"><?= $m['nama'] ?></td>
              <?php if(!empty($kriteriaList)): ?>
                <?php foreach($kriteriaList as $k): ?>
                  <td class="text-center">
                    <input type="number"
                           step="any"
                           data-krt-id="<?= $k['id'] ?>"
                           class="form-control text-center nilai-input"
                           value="<?= $scores[$m['id']][$k['id']] ?? '' ?>"
                           placeholder="0"
                           style="max-width:110px;margin:0 auto;text-align:center"
                           required/>
                  </td>
                <?php endforeach; ?>
              <?php endif; ?>
              <td class="text-center">
                <button class="btn btn-sm btn-navy btn-save-row" title="Simpan baris ini">
                  <i class="bi bi-save"></i>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="<?= 4 + count($kriteriaList ?? []) ?>" class="text-center py-5" style="color:var(--muted)">
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

    <div class="mt-3">
      <p style="font-size:.8rem;color:var(--muted);margin:0">
        <i class="bi bi-info-circle me-1"></i>
        Masukkan nilai sesuai skala yang disepakati. Klik ikon simpan di kanan tiap baris atau "Simpan Semua" di atas.
      </p>
    </div>

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

<!-- Toast for feedback -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
  <div id="saveToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">Simpan berhasil</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toastEl = document.getElementById('saveToast');
    const toast = new bootstrap.Toast(toastEl);
    const toastMsg = document.getElementById('toastMessage');

    function showFeedback(msg, isError = false) {
        toastEl.classList.toggle('bg-success', !isError);
        toastEl.classList.toggle('bg-danger', isError);
        toastMsg.textContent = msg;
        toast.show();
    }

    async function saveRow(row) {
        const mhsId = row.dataset.mhsId;
        const inputs = row.querySelectorAll('.nilai-input');
        const data = { nilai: {} };
        let valid = true;

        inputs.forEach(input => {
            const krtId = input.dataset.krtId;
            const val = input.value;
            if (val === '') {
                valid = false;
                input.style.borderColor = 'var(--danger)';
            } else {
                input.style.borderColor = 'var(--gold)';
                data.nilai[krtId] = val;
            }
        });

        if (!valid) {
            showFeedback('Harap isi semua nilai kriteria', true);
            return false;
        }

        try {
            const response = await fetch(`<?= base_url('penilaian') ?>/${mhsId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-hash"]').content
                },
                body: JSON.stringify(data)
            });
            const res = await response.json();
            if (res.success) {
                if (res.csrf) document.querySelector('meta[name="csrf-hash"]').content = res.csrf;
                return true;
            } else {
                showFeedback(res.message || 'Gagal menyimpan', true);
                return false;
            }
        } catch (e) {
            showFeedback('Terjadi kesalahan koneksi', true);
            return false;
        }
    }

    // Individual Save
    document.querySelectorAll('.btn-save-row').forEach(btn => {
        btn.addEventListener('click', async function() {
            const row = this.closest('tr');
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            if (await saveRow(row)) {
                showFeedback('Data berhasil disimpan');
            }
            
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-save"></i>';
        });
    });

    // Save All
    document.getElementById('btnSimpanSemua').addEventListener('click', async function() {
        const rows = document.querySelectorAll('#tabelPenilaian tbody tr[data-mhs-id]');
        this.disabled = true;
        const originalHtml = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

        let successCount = 0;
        for (const row of rows) {
            if (await saveRow(row)) successCount++;
        }

        showFeedback(`${successCount} data berhasil diperbarui`);
        this.disabled = false;
        this.innerHTML = originalHtml;
    });
});

function isiContoh() {
  if(!confirm('Isi semua input dengan nilai contoh (untuk testing)?')) return;
  document.querySelectorAll('.nilai-input').forEach(inp => {
    // Basic randomization logic
    inp.value = (Math.random() * 3 + 1).toFixed(2);
    inp.style.borderColor = 'var(--gold)';
  });
}
</script>
<?= $this->endSection() ?>
