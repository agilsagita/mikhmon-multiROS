<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
?>

<!-- Google Fonts Inter -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
  /* Reset and base styles for modern login page */
  body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif !important;
    background: radial-gradient(circle at 10% 20%, rgba(18, 16, 32, 1) 0%, rgba(24, 22, 46, 1) 90%) !important;
    min-height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
  }

  .login-page-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
    position: relative;
    background-color: transparent;
  }

  /* Decorative glowing ambient spots */
  .ambient-glow-1 {
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%);
    top: 15%;
    left: 20%;
    filter: blur(40px);
    z-index: 1;
    pointer-events: none;
  }

  .ambient-glow-2 {
    position: absolute;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, rgba(236, 72, 153, 0) 70%);
    bottom: 10%;
    right: 15%;
    filter: blur(50px);
    z-index: 1;
    pointer-events: none;
  }

  /* Glassmorphism Card Design */
  .glass-card {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 400px;
    padding: 2.5rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-sizing: border-box;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    flex-shrink: 0;
  }

  .glass-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.35), 
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
  }

  /* Header design */
  .card-header-modern {
    text-align: center;
    margin-bottom: 2rem;
  }

  .logo-wrapper {
    display: inline-block;
    padding: 10px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
  }

  .logo-wrapper:hover {
    transform: scale(1.05) rotate(5deg);
  }

  .logo-wrapper img {
    width: 64px;
    height: 64px;
    display: block;
  }

  .app-title {
    font-size: 26px;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #ffffff;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  .app-subtitle {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.5);
    margin: 5px 0 0 0;
    font-weight: 400;
  }

  /* Modern Form Elements */
  .form-group-modern {
    position: relative;
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
  }

  .form-group-modern i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.4);
    font-size: 16px;
    transition: color 0.3s ease;
  }

  .input-modern {
    width: 100% !important;
    height: 50px !important;
    padding: 0 16px 0 46px !important;
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 14px !important;
    color: #ffffff !important;
    font-size: 15px !important;
    outline: none !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-sizing: border-box !important;
    font-family: 'Inter', sans-serif !important;
  }

  .input-modern:focus {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(99, 102, 241, 0.8) !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15) !important;
  }

  .input-modern:focus + i {
    color: rgba(99, 102, 241, 1);
  }

  .input-modern::placeholder {
    color: rgba(255, 255, 255, 0.35);
  }

  /* Modern Submit Button */
  .btn-submit-modern {
    width: 100% !important;
    height: 50px !important;
    margin-top: 1rem !important;
    background: linear-gradient(135deg, rgba(99, 102, 241, 1) 0%, rgba(79, 70, 229, 1) 100%) !important;
    border: none !important;
    border-radius: 14px !important;
    color: #ffffff !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    font-family: 'Inter', sans-serif !important;
  }

  .btn-submit-modern:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4) !important;
    background: linear-gradient(135deg, rgba(110, 114, 245, 1) 0%, rgba(88, 80, 236, 1) 100%) !important;
  }

  .btn-submit-modern:active {
    transform: translateY(1px) !important;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3) !important;
  }

  /* Toast/Floating Alert for Errors */
  .alert-modern {
    margin-top: 1.5rem;
    padding: 12px 16px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 12px;
    color: #f87171;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideUp 0.3s ease;
  }

  .alert-modern i {
    font-size: 16px;
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(8px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Style the default Mikhmon wrapper for full-screen layout on login page */
  .wrapper {
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    align-items: center !important;
    min-height: 100vh !important;
    width: 100% !important;
    background: transparent !important;
    box-shadow: none !important;
    margin: 0 !important;
    padding: 0 !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
  }

  /* Ensure login container always centers properly */
  .login-page-container {
    align-items: center !important;
    padding: 16px !important;
  }

  @media (max-width: 480px) {
    .glass-card {
      padding: 2rem 1.25rem !important;
      border-radius: 20px !important;
    }

    .app-title {
      font-size: 22px !important;
    }

    .input-modern {
      font-size: 14px !important;
    }

    /* Prevent hover lift on touch devices */
    .glass-card:hover {
      transform: none !important;
    }
  }
</style>

<style>
  /* ── Eye toggle button ── */
  .pass-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }

  .pass-wrapper .input-modern {
    padding-right: 48px !important;
  }

  .eye-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: rgba(255, 255, 255, 0.35);
    font-size: 15px;
    line-height: 1;
    transition: color 0.25s ease;
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
  }

  .eye-toggle:hover {
    color: rgba(99, 102, 241, 0.9);
  }

  /* ── Remember Me: Custom Toggle Switch ── */
  .remember-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: -6px 0 18px 0;
  }

  /* Sembunyikan checkbox native */
  .remember-row input[type="checkbox"] {
    position: absolute !important;
    opacity: 0 !important;
    width: 0 !important;
    height: 0 !important;
    pointer-events: none !important;
  }

  /* Track toggle */
  .toggle-switch {
    position: relative;
    display: inline-block;
    width: 36px;
    height: 20px;
    flex-shrink: 0;
    cursor: pointer;
  }

  .toggle-switch .track {
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    transition: background 0.25s ease, border-color 0.25s ease;
  }

  .toggle-switch .thumb {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transition: transform 0.25s ease, background 0.25s ease;
  }

  /* Checked state */
  input[type="checkbox"]:checked + .toggle-switch .track {
    background: rgba(99, 102, 241, 0.7);
    border-color: rgba(99, 102, 241, 0.5);
  }

  input[type="checkbox"]:checked + .toggle-switch .thumb {
    transform: translateX(16px);
    background: #ffffff;
  }

  .remember-row label {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    user-select: none;
    line-height: 1.4;
  }

  /* ── Captcha row ── */
  .captcha-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1.5rem;
  }

  .captcha-question {
    background: rgba(99, 102, 241, 0.08);
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: 12px;
    padding: 8px 16px;
    font-size: 16px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.85);
    white-space: nowrap;
    flex-shrink: 0;
    font-family: 'Inter', monospace;
    letter-spacing: 1px;
  }

  .captcha-input {
    flex: 1;
    height: 44px !important;
    padding: 0 14px !important;
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 12px !important;
    color: #ffffff !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    outline: none !important;
    transition: all 0.3s ease !important;
    box-sizing: border-box !important;
    text-align: center;
    letter-spacing: 2px;
    font-family: 'Inter', sans-serif !important;
  }

  .captcha-input:focus {
    border-color: rgba(99, 102, 241, 0.7) !important;
    background: rgba(255, 255, 255, 0.07) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12) !important;
  }

  .captcha-input::placeholder {
    color: rgba(255, 255, 255, 0.25);
    font-weight: 400;
    letter-spacing: 0;
  }

  .captcha-equals {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.4);
    flex-shrink: 0;
  }
</style>

<div class="login-page-container">
  <div class="ambient-glow-1"></div>
  <div class="ambient-glow-2"></div>

  <div class="glass-card">
    <div class="card-header-modern">
      <div class="logo-wrapper">
        <img src="img/favicon.png" alt="MIKHMON Logo">
      </div>
      <h1 class="app-title">MIKHMON</h1>
      <p class="app-subtitle"><?= $_please_login ?></p>
    </div>

    <form autocomplete="off" action="" method="post" id="loginForm">

      <!-- Username -->
      <div class="form-group-modern">
        <input
          class="input-modern"
          type="text"
          name="user"
          id="_username"
          placeholder="Username"
          required
          autofocus
          value="<?= htmlspecialchars($remembered_user ?? '') ?>"
        >
        <i class="fa fa-user"></i>
      </div>

      <!-- Password + Eye Toggle -->
      <div class="form-group-modern">
        <div class="pass-wrapper">
          <!-- Ikon kunci di kiri -->
          <i class="fa fa-lock" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.4);font-size:15px;z-index:2;pointer-events:none;"></i>
          <input
            class="input-modern"
            type="password"
            name="pass"
            id="_password"
            placeholder="Password"
            required
            style="padding-left:46px !important; padding-right:48px !important;"
          >
          <button type="button" class="eye-toggle" id="togglePass" title="Lihat / Sembunyikan Password">
            <i class="fa fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <!-- Remember Me: Custom Toggle Switch -->
      <div class="remember-row">
        <input type="checkbox" name="remember" id="remember"
          <?= !empty($remembered_user) ? 'checked' : '' ?>>
        <label for="remember" class="toggle-switch" aria-hidden="true">
          <span class="track"></span>
          <span class="thumb"></span>
        </label>
        <label for="remember">Ingat saya selama 7 hari</label>
      </div>

      <!-- Captcha -->
      <div class="captcha-row">
        <div class="captcha-question">
          <?= $_SESSION['captcha_a'] ?> + <?= $_SESSION['captcha_b'] ?>
        </div>
        <span class="captcha-equals">=</span>
        <input
          class="captcha-input"
          type="number"
          name="captcha"
          id="_captcha"
          placeholder="?"
          required
          min="1"
          max="18"
          autocomplete="off"
        >
      </div>

      <input class="btn-submit-modern" type="submit" name="login" value="Login">

      <?php if ($error == 'captcha'): ?>
        <div class="alert-modern">
          <i class="fa fa-calculator"></i>
          <div>Jawaban captcha salah. Coba lagi.</div>
        </div>
      <?php elseif ($error == 'credentials'): ?>
        <div class="alert-modern">
          <i class="fa fa-exclamation-triangle"></i>
          <div>Username atau password salah.</div>
        </div>
      <?php endif; ?>

    </form>
  </div>
</div>

<script>
  // ── Eye Toggle ──────────────────────────────────────────────────
  document.getElementById('togglePass').addEventListener('click', function () {
    var passField = document.getElementById('_password');
    var eyeIcon   = document.getElementById('eyeIcon');
    if (passField.type === 'password') {
      passField.type = 'text';
      eyeIcon.className = 'fa fa-eye-slash';
      this.title = 'Sembunyikan Password';
    } else {
      passField.type = 'password';
      eyeIcon.className = 'fa fa-eye';
      this.title = 'Lihat Password';
    }
  });

  // ── Autofocus captcha jika username & password sudah terisi ────
  (function () {
    var u = document.getElementById('_username');
    var p = document.getElementById('_password');
    var c = document.getElementById('_captcha');
    if (u && u.value.trim() !== '') {
      p.focus();
    }
    p.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); c.focus(); }
    });
  })();
</script>

</body>
</html>
