@extends('layout.master')
@section('title', 'Ganti Password')

@section('styles')
<style>
    /* Hanya berlaku pada halaman ganti password */
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

    /* Ikon muncul ketika input sedang digunakan */
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
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Ganti Password</h5>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.update-password') }}">
            @csrf

           <div class="mb-3">
                <label>Password Lama</label>
                <input
                    type="password"
                    name="password_lama"
                    class="form-control"
                    required>
                </div>

                <div class="mb-3">
                    <label>Password Baru</label>
                    <input
                        type="password"
                        name="password_baru"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label>Konfirmasi Password Baru</label>
                    <input
                        type="password"
                        name="password_baru_confirmation"
                        class="form-control"
                        required>
                </div>

            <button type="submit" class="btn btn-primary">
                Simpan Password
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll(
            '[data-password-target]'
        );

        toggleButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const targetId = button.getAttribute(
                    'data-password-target'
                );

                const input = document.getElementById(targetId);
                const icon = button.querySelector('i');

                if (!input || !icon) {
                    return;
                }

                const willShow = input.type === 'password';

                input.type = willShow ? 'text' : 'password';

                icon.classList.toggle('ti-eye', !willShow);
                icon.classList.toggle('ti-eye-off', willShow);

                button.setAttribute(
                    'aria-pressed',
                    willShow ? 'true' : 'false'
                );

                button.setAttribute(
                    'aria-label',
                    willShow
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'
                );

                input.focus();
            });
        });
    });
</script>
@endsection