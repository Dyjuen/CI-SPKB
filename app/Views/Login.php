<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login — SPKB</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    :root {
      --navy: #0f1f3d;
      --gold: #c9a84c;
      --gold-lt: #e8c97e;
      --cream: #f8f5ef;
      --border: #ddd8cc;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: Poppins, sans-serif;
      background: var(--cream);
      min-height: 100vh;
      display: flex;
      align-items: stretch;
    }

    /* LEFT PANEL */
    .panel-left {
      width: 55%;
      background: var(--navy);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 60px;
      position: relative;
      overflow: hidden;
    }
    .panel-left::before {
      content: '';
      position: absolute;
      width: 420px; height: 420px;
      border-radius: 50%;
      border: 60px solid rgba(201,168,76,.07);
      top: -120px; right: -120px;
    }
    .panel-left::after {
      content: '';
      position: absolute;
      width: 280px; height: 280px;
      border-radius: 50%;
      border: 40px solid rgba(201,168,76,.05);
      bottom: -80px; left: -80px;
    }
    .brand-logo {
      text-align: center;
      position: relative;
      z-index: 1;
    }
    .brand-logo .icon-wrap {
      width: 80px; height: 80px;
      background: rgba(201,168,76,.15);
      border: 1px solid rgba(201,168,76,.3);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      font-size: 2.4rem;
      color: var(--gold);
      margin: 0 auto 24px;
    }
    .brand-logo h1 {
      font-family: Poppins, sans-serif;
      color: var(--gold);
      font-size: 2.2rem;
      letter-spacing: .04em;
    }
    .brand-logo p {
      color: rgba(255,255,255,.5);
      font-size: .85rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      margin-top: 8px;
    }
    .panel-info {
      margin-top: 48px;
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 380px;
    }
    .info-item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 16px 0;
      border-bottom: 1px solid rgba(255,255,255,.07);
    }
    .info-item:last-child { border-bottom: none; }
    .info-item .ic {
      width: 36px; height: 36px;
      background: rgba(201,168,76,.12);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: var(--gold);
      font-size: 1rem;
      flex-shrink: 0;
    }
    .info-item h6 { color: rgba(255,255,255,.85); font-size: .875rem; margin: 0 0 3px; font-weight: 500; }
    .info-item p  { color: rgba(255,255,255,.4); font-size: .78rem; margin: 0; }

    /* RIGHT PANEL */
    .panel-right {
      width: 45%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 48px;
    }
    .login-card {
      width: 100%;
      max-width: 380px;
    }
    .login-card h2 {
      font-family: Poppins, sans-serif;
      font-size: 1.9rem;
      color: var(--navy);
      margin-bottom: 6px;
    }
    .login-card .sub {
      color: #7b8aaa;
      font-size: .875rem;
      margin-bottom: 36px;
    }
    .form-label { font-size: .825rem; font-weight: 500; color: var(--navy); margin-bottom: 6px; }
    .input-wrap { position: relative; }
    .input-wrap .icon {
      position: absolute;
      left: 14px; top: 50%;
      transform: translateY(-50%);
      color: #aab4c8;
      font-size: 1rem;
    }
    .form-control {
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 11px 14px 11px 42px;
      font-size: .875rem;
      background: #fff;
      transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(201,168,76,.15);
      outline: none;
    }
    .btn-login {
      background: var(--navy);
      color: #fff;
      border: none;
      width: 100%;
      padding: 13px;
      border-radius: 10px;
      font-size: .9rem;
      font-weight: 600;
      letter-spacing: .03em;
      transition: all .2s;
      margin-top: 8px;
    }
    .btn-login:hover { background: #1a2e52; }
    .error-box {
      background: rgba(192,57,43,.08);
      border: 1px solid rgba(192,57,43,.25);
      color: #c0392b;
      border-radius: 8px;
      padding: 11px 16px;
      font-size: .83rem;
      margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }
    .divider {
      text-align: center;
      position: relative;
      margin: 28px 0 22px;
      color: #aab4c8;
      font-size: .78rem;
    }
    .divider::before, .divider::after {
      content: '';
      position: absolute;
      top: 50%; width: 38%;
      height: 1px;
      background: var(--border);
    }
    .divider::before { left: 0; }
    .divider::after { right: 0; }
    .login-footer {
      text-align: center;
      margin-top: 32px;
      font-size: .75rem;
      color: #aab4c8;
    }

    @media (max-width: 768px) {
      body { flex-direction: column; }
      .panel-left { width: 100%; padding: 40px 24px; }
      .panel-info { display: none; }
      .panel-right { width: 100%; padding: 40px 24px; }
    }
  </style>
</head>
<body>

<!-- LEFT -->
<div class="panel-left">
  <div class="brand-logo">
    <div class="icon-wrap"><i class="bi bi-award-fill"></i></div>
    <h1>SPK Beasiswa</h1>
    <p>Sistem Pendukung Keputusan Beasiswa</p>
  </div>
  <div class="panel-info">
    <div class="info-item">
      <div class="ic"><i class="bi bi-clipboard-data"></i></div>
      <div>
        <h6>Metode SAW</h6>
        <p>Simple Additive Weighting untuk seleksi objektif</p>
      </div>
    </div>
    <div class="info-item">
      <div class="ic"><i class="bi bi-people"></i></div>
      <div>
        <h6>Multi Kriteria</h6>
        <p>IPK, penghasilan, tanggungan & prestasi</p>
      </div>
    </div>
    <div class="info-item">
      <div class="ic"><i class="bi bi-trophy"></i></div>
      <div>
        <h6>Ranking Otomatis</h6>
        <p>Hasil seleksi transparan dan akuntabel</p>
      </div>
    </div>
  </div>
</div>

<!-- RIGHT -->
<div class="panel-right">
  <div class="login-card">
    <h2>Selamat Datang</h2>
    <p class="sub">Masuk untuk mengakses sistem penilaian beasiswa</p>

    <?php if(isset($error) && $error): ?>
      <div class="error-box">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post">
      <?= csrf_field() /* CI4 */ /* CI3: pakai input hidden dari $this->security->get_csrf_token_name() */ ?>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-wrap">
          <i class="bi bi-person icon"></i>
          <input type="text" name="username" class="form-control"
                 placeholder="Masukkan username"
                 value="<?= isset($old_username) ? htmlspecialchars($old_username) : '' ?>"
                 required autocomplete="username"/>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-wrap">
          <i class="bi bi-lock icon"></i>
          <input type="password" name="password" class="form-control"
                 placeholder="Masukkan password"
                 required autocomplete="current-password"/>
        </div>
      </div>
      <button type="submit" class="btn-login">
        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
      </button>
    </form>

    <div class="login-footer">
      &copy; <?= date('Y') ?> SPK Beasiswa
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>