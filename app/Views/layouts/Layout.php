<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $title ?? 'SPK Beasiswa' ?> — SPK Beasiswa</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    :root {
      --navy:   #0f1f3d;
      --gold:   #c9a84c;
      --gold-lt:#e8c97e;
      --cream:  #f8f5ef;
      --white:  #ffffff;
      --text:   #1a2640;
      --muted:  #6b7a99;
      --danger: #c0392b;
      --success:#1a7a4a;
      --border: #ddd8cc;
      --sidebar-w: 260px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: Poppins, sans-serif;
      background: var(--cream);
      color: var(--text);
      min-height: 100vh;
      display: flex;
    }

    /* ── SIDEBAR ── */
    #sidebar {
      width: var(--sidebar-w);
      background: var(--navy);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; left: 0;
      z-index: 100;
      transition: transform .3s ease;
    }
    .sidebar-brand {
      padding: 28px 24px 20px;
      border-bottom: 1px solid rgba(201,168,76,.25);
    }
    .sidebar-brand h1 {
      font-family: Poppins, sans-serif;
      color: var(--gold);
      font-size: 1.4rem;
      letter-spacing: .04em;
      line-height: 1.2;
    }
    .sidebar-brand p {
      color: rgba(255,255,255,.45);
      font-size: .7rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      margin-top: 4px;
    }
    .sidebar-nav { padding: 16px 0; flex: 1; }
    .nav-section {
      padding: 10px 20px 4px;
      font-size: .65rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255,255,255,.3);
    }
    .nav-item a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 24px;
      color: rgba(255,255,255,.65);
      text-decoration: none;
      font-size: .875rem;
      font-weight: 400;
      transition: all .2s;
      border-left: 3px solid transparent;
    }
    .nav-item a:hover,
    .nav-item a.active {
      color: var(--gold-lt);
      background: rgba(201,168,76,.08);
      border-left-color: var(--gold);
    }
    .nav-item a i { font-size: 1rem; width: 18px; text-align: center; }
    .sidebar-footer {
      padding: 18px 24px;
      border-top: 1px solid rgba(201,168,76,.2);
    }
    .user-card {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .user-avatar {
      width: 36px; height: 36px;
      border-radius: 50%;
      background: var(--gold);
      display: flex; align-items: center; justify-content: center;
      font-weight: 600; color: var(--navy); font-size: .85rem;
      flex-shrink: 0;
    }
    .user-info small { color: rgba(255,255,255,.4); font-size: .7rem; }
    .user-info span { color: rgba(255,255,255,.85); font-size: .82rem; display: block; }
    .btn-logout {
      display: flex; align-items: center; gap: 6px;
      background: rgba(192,57,43,.15);
      color: #e87e7e;
      border: 1px solid rgba(192,57,43,.3);
      border-radius: 6px;
      padding: 6px 12px;
      font-size: .78rem;
      text-decoration: none;
      margin-top: 12px;
      transition: all .2s;
    }
    .btn-logout:hover { background: rgba(192,57,43,.3); color: #f5a0a0; }

    /* ── MAIN ── */
    #main {
      margin-left: var(--sidebar-w);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .topbar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 14px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .topbar h2 {
      font-family: Poppins, sans-serif;
      font-size: 1.25rem;
      color: var(--navy);
    }
    .topbar .breadcrumb {
      font-size: .78rem;
      color: var(--muted);
      margin: 0;
      background: none;
    }
    .content-area {
      padding: 32px;
      flex: 1;
    }

    /* ── CARDS & TABLES ── */
    .card-glass {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(15,31,61,.06);
    }
    .card-glass .card-header {
      background: none;
      border-bottom: 1px solid var(--border);
      padding: 18px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .card-glass .card-header h5 {
      font-family: Poppins, sans-serif;
      font-size: 1.05rem;
      color: var(--navy);
      margin: 0;
    }
    .card-glass .card-body { padding: 24px; }

    .table thead th {
      background: var(--cream);
      color: var(--navy);
      font-weight: 600;
      font-size: .78rem;
      letter-spacing: .06em;
      text-transform: uppercase;
      border-bottom: 2px solid var(--border);
      padding: 12px 16px;
    }
    .table tbody td { padding: 13px 16px; font-size: .875rem; vertical-align: middle; }
    .table tbody tr:hover { background: rgba(201,168,76,.05); }
    .table-striped tbody tr:nth-child(odd) { background: rgba(248,245,239,.6); }

    /* ── BUTTONS ── */
    .btn-gold {
      background: var(--gold);
      color: var(--navy);
      border: none;
      font-weight: 600;
      font-size: .875rem;
      padding: 9px 20px;
      border-radius: 8px;
      transition: all .2s;
      display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-gold:hover { background: var(--gold-lt); color: var(--navy); }
    .btn-navy {
      background: var(--navy);
      color: var(--white);
      border: none;
      font-weight: 500;
      font-size: .875rem;
      padding: 9px 20px;
      border-radius: 8px;
      transition: all .2s;
      display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-navy:hover { background: #1a2e52; color: var(--white); }
    .btn-outline-danger { font-size: .8rem; padding: 6px 13px; border-radius: 6px; }
    .btn-outline-secondary { font-size: .8rem; padding: 6px 13px; border-radius: 6px; }

    /* ── BADGES ── */
    .badge-rank {
      background: var(--navy);
      color: var(--gold);
      font-weight: 700;
      font-size: .8rem;
      padding: 5px 10px;
      border-radius: 20px;
    }
    .badge-lulus {
      background: rgba(26,122,74,.12);
      color: var(--success);
      font-size: .78rem;
      padding: 4px 12px;
      border-radius: 20px;
      font-weight: 600;
    }
    .badge-tidak {
      background: rgba(192,57,43,.1);
      color: var(--danger);
      font-size: .78rem;
      padding: 4px 12px;
      border-radius: 20px;
      font-weight: 600;
    }

    /* ── STAT CARDS ── */
    .stat-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 22px;
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .stat-icon {
      width: 48px; height: 48px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem;
      flex-shrink: 0;
    }
    .stat-icon.gold { background: rgba(201,168,76,.15); color: var(--gold); }
    .stat-icon.navy { background: rgba(15,31,61,.08); color: var(--navy); }
    .stat-icon.green { background: rgba(26,122,74,.12); color: var(--success); }
    .stat-icon.red   { background: rgba(192,57,43,.1);  color: var(--danger); }
    .stat-card h3 { font-size: 1.8rem; font-weight: 700; color: var(--navy); margin: 0; }
    .stat-card p  { font-size: .78rem; color: var(--muted); margin: 0; }

    /* ── FORM ── */
    .form-label { font-size: .825rem; font-weight: 500; color: var(--navy); margin-bottom: 5px; }
    .form-control, .form-select {
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: .875rem;
      color: var(--text);
      background: var(--white);
      transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(201,168,76,.15);
      outline: none;
    }

    /* ── ALERT ── */
    .alert-success-custom {
      background: rgba(26,122,74,.08);
      border: 1px solid rgba(26,122,74,.25);
      color: var(--success);
      border-radius: 8px;
      padding: 12px 18px;
      font-size: .875rem;
    }
    .alert-danger-custom {
      background: rgba(192,57,43,.08);
      border: 1px solid rgba(192,57,43,.25);
      color: var(--danger);
      border-radius: 8px;
      padding: 12px 18px;
      font-size: .875rem;
    }

    /* ── MODAL ── */
    .modal-content { border-radius: 14px; border: none; }
    .modal-header {
      background: var(--navy);
      color: var(--white);
      border-radius: 14px 14px 0 0;
      padding: 16px 24px;
    }
    .modal-header .btn-close { filter: invert(1); }
    .modal-title { font-family: Poppins, sans-serif; font-size: 1.1rem; }

    @media (max-width: 768px) {
      #sidebar { transform: translateX(-100%); }
      #sidebar.open { transform: translateX(0); }
      #main { margin-left: 0; }
      .content-area { padding: 20px 16px; }
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<nav id="sidebar">
  <div class="sidebar-brand">
    <h1><i class="bi bi-award-fill"></i> SPK Beasiswa</h1>
    <p>Sistem Pendukung Keputusan</p>
  </div>
  <div class="sidebar-nav">
    <div class="nav-section">Menu Utama</div>
    <div class="nav-item">
      <a href="<?= base_url('dashboard') ?>" class="<?= uri_string() == 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-grid-1x2"></i> Dashboard
      </a>
    </div>
    <div class="nav-section">Data Master</div>
    <div class="nav-item">
      <a href="<?= base_url('mahasiswa') ?>" class="<?= str_starts_with(uri_string(), 'mahasiswa') ? 'active' : '' ?>">
        <i class="bi bi-people"></i> Data Mahasiswa
      </a>
    </div>
    <div class="nav-item">
      <a href="<?= base_url('kriteria') ?>" class="<?= str_starts_with(uri_string(), 'kriteria') ? 'active' : '' ?>">
        <i class="bi bi-sliders"></i> Kriteria & Bobot
      </a>
    </div>
    <div class="nav-section">Penilaian</div>
    <div class="nav-item">
      <a href="<?= base_url('penilaian') ?>" class="<?= str_starts_with(uri_string(), 'penilaian') ? 'active' : '' ?>">
        <i class="bi bi-pencil-square"></i> Input Penilaian
      </a>
    </div>
    <div class="nav-item">
      <a href="<?= base_url('hasil') ?>" class="<?= str_starts_with(uri_string(), 'hasil') ? 'active' : '' ?>">
        <i class="bi bi-trophy"></i> Hasil & Ranking
      </a>
    </div>
  </div>
  <div class="sidebar-footer">
    <div class="user-card">
    <div class="user-avatar"><?= strtoupper(substr(session()->get('username') ?? 'A', 0, 1)) ?></div>
      <div class="user-info">
        <small>Login sebagai</small>
        <span><?= session()->get('username') ?? 'Admin' ?></span>
      </div>
    </div>
    <a href="<?= base_url('logout') ?>" class="btn-logout">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
  </div>
</nav>

<!-- MAIN -->
<div id="main">
  <div class="topbar">
    <div>
      <h2><?= $title ?? 'Dashboard' ?></h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" style="color:var(--muted);text-decoration:none;">Home</a></li>
          <?php if(isset($breadcrumb)): ?>
            <li class="breadcrumb-item active" style="color:var(--navy);"><?= $breadcrumb ?></li>
          <?php endif; ?>
        </ol>
      </nav>
    </div>
    <div class="d-flex align-items-center gap-3">
      <span style="font-size:.8rem;color:var(--muted);"><?= date('d F Y') ?></span>
    </div>
  </div>

  <div class="content-area">

    <!-- FLASH MESSAGE -->
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert-success-custom mb-4 d-flex align-items-center gap-2">
      <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert-danger-custom mb-4 d-flex align-items-center gap-2">
      <i class="bi bi-exclamation-circle-fill"></i> <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?> 

    <!-- CONTENT PLACEHOLDER — tiap halaman meng-include layout ini lalu inject kontennya -->
    <?= $this->renderSection('content') ?>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>