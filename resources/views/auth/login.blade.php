<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Login - Sistem Absensi</title>

  <link
    rel="stylesheet"
    href="{{ asset('admin/assets/css/styles.min.css') }}">

  <style>
    body {
      background: #f6f7fb;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 20px;
    }

    .login-card {
      width: 100%;
      max-width: 430px;
      border: 1px solid #edf0f5;
      border-radius: 20px;
      background: #fff;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      padding: 32px;
    }

    .login-logo {
      width: 60px;
      height: 60px;
      object-fit: contain;
    }

    .form-control {
      min-height: 46px;
      border-radius: 12px;
    }

    .btn-login {
      min-height: 46px;
      border-radius: 12px;
      font-weight: 600;
    }

    .password-field {
      position: relative;
    }

    .password-field .form-control {
      padding-right: 48px;
    }

    .password-toggle {
      position: absolute;
      top: 50%;
      right: 14px;
      transform: translateY(-50%);
      width: 32px;
      height: 32px;
      padding: 0;
      border: 0;
      background: transparent;
      color: #6c757d;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 21px;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition:
        opacity 0.2s ease,
        visibility 0.2s ease,
        color 0.2s ease;
      z-index: 5;
    }

    /* Ikon muncul saat kolom password digunakan */
    .password-field:focus-within .password-toggle,
    .password-field:hover .password-toggle {
      opacity: 1;
      visibility: visible;
    }

    .password-toggle:hover,
    .password-toggle:focus {
      color: #0d6efd;
      outline: none;
    }
  </style>
</head>

<body>
  <div class="login-card">

    <div class="text-center mb-4">
      <img
        src="{{ asset('admin/assets/images/logos/logosma.png') }}"
        alt="Logo SMAS Mathla'ul Anwar"
        class="login-logo mb-3">

      <h3 class="fw-bold mb-1">
        Login Sistem
      </h3>

      <p class="text-muted mb-0">
        Admin, guru, wali kelas, dan siswa masuk dari sini
      </p>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('login.proses') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">
          Email
        </label>

        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          value="{{ old('email') }}"
          autocomplete="email"
          required
          autofocus>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">
          Password
        </label>

        <div class="password-field">
          <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            autocomplete="current-password"
            required>

          <button
            type="button"
            class="password-toggle"
            id="togglePassword"
            aria-label="Tampilkan password"
            aria-pressed="false">
            <i class="ti ti-eye"></i>
          </button>
        </div>
      </div>

      <button
        type="submit"
        class="btn btn-primary w-100 btn-login">
        Masuk
      </button>
    </form>

    <div class="mt-4 small text-muted text-center">
      Gunakan akun yang sudah terdaftar sesuai hak akses pengguna
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const passwordInput =
        document.getElementById('password');

      const toggleButton =
        document.getElementById('togglePassword');

      if (!passwordInput || !toggleButton) {
        return;
      }

      const icon = toggleButton.querySelector('i');

      toggleButton.addEventListener('click', function () {
        const willShow =
          passwordInput.type === 'password';

        passwordInput.type =
          willShow ? 'text' : 'password';

        if (icon) {
          icon.classList.toggle('ti-eye', !willShow);
          icon.classList.toggle('ti-eye-off', willShow);
        }

        toggleButton.setAttribute(
          'aria-pressed',
          willShow ? 'true' : 'false'
        );

        toggleButton.setAttribute(
          'aria-label',
          willShow
            ? 'Sembunyikan password'
            : 'Tampilkan password'
        );

        passwordInput.focus();
      });
    });
  </script>
</body>

</html>