@extends('layouts.app')

@section('title', 'Buat Password Baru')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card shadow-lg border-0" style="border-radius:16px;overflow:hidden;">

                    {{-- Header --}}
                    <div style="background:linear-gradient(135deg,#101216 0%,#1e2530 100%);padding:2rem 2rem 1.5rem;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#D4AF37,#E8C34B);
                                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-key-fill" style="color:#101216;font-size:1.3rem;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color:#fff;">Buat Password Baru</h5>
                                <small style="color:rgba(255,255,255,.55);">Athaya Fish Farm</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        {{-- Info email aktif --}}
                        <div class="mb-4 p-3 rounded-3 d-flex gap-2 align-items-center"
                             style="background:#F0FDF4;border-left:4px solid #10B981;">
                            <i class="bi bi-person-check-fill" style="color:#10B981;flex-shrink:0;"></i>
                            <span style="font-size:.875rem;color:#065F46;">
                                Akun ditemukan: <strong>{{ $email }}</strong>
                            </span>
                        </div>

                        {{-- Flash info --}}
                        @if (session('info'))
                            <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                {{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                @foreach ($errors->all() as $err)
                                    <div>{{ $err }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST" id="resetForm">
                            @csrf

                            {{-- Password baru --}}
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-1" style="color:#D4AF37;"></i> Password Baru
                                </label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        placeholder="Minimal 8 karakter"
                                        pattern="(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#])[a-zA-Z0-9!@#]{8,}"
                                        title="Minimal 8 karakter, terdiri dari angka, huruf, dan simbol (!, @, #)"
                                        required
                                        style="border-radius:10px 0 0 10px;padding:.65rem 1rem;"
                                    >
                                    <button class="btn btn-outline-secondary" type="button"
                                            style="border-radius:0 10px 10px 0;border-color:#E5E7EB;"
                                            onclick="toggleVis('password','eye1')">
                                        <i class="bi bi-eye" id="eye1"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimal 8 karakter (harus terdiri dari angka, huruf, dan simbol !, @, #)</small>
                                @error('password')
                                    <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi --}}
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-1" style="color:#D4AF37;"></i> Konfirmasi Password
                                </label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Ulangi password baru"
                                        required
                                        style="border-radius:10px 0 0 10px;padding:.65rem 1rem;"
                                    >
                                    <button class="btn btn-outline-secondary" type="button"
                                            style="border-radius:0 10px 10px 0;border-color:#E5E7EB;"
                                            onclick="toggleVis('password_confirmation','eye2')">
                                        <i class="bi bi-eye" id="eye2"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Indikator kekuatan --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Kekuatan Password</small>
                                    <small id="strengthText" class="fw-semibold text-muted">—</small>
                                </div>
                                <div class="progress" style="height:6px;border-radius:10px;">
                                    <div id="strengthBar" class="progress-bar" role="progressbar"
                                         style="width:0%;border-radius:10px;transition:width .3s,background-color .3s;"></div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                                    id="btnSimpan" style="border-radius:10px;">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Password Baru
                            </button>
                        </form>

                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-decoration-none"
                               style="font-size:.875rem;color:#D4AF37;font-weight:600;">
                                <i class="bi bi-arrow-left me-1"></i> Ganti Email
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleVis(id, iconId) {
            const input = document.getElementById(id);
            const icon  = document.getElementById(iconId);
            input.type === 'password'
                ? (input.type = 'text', icon.classList.replace('bi-eye','bi-eye-slash'))
                : (input.type = 'password', icon.classList.replace('bi-eye-slash','bi-eye'));
        }

        document.getElementById('password').addEventListener('input', function () {
            const v    = this.value;
            const bar  = document.getElementById('strengthBar');
            const txt  = document.getElementById('strengthText');
            let score  = 0;
            if (v.length >= 8)            score++;
            if (/[A-Z]/.test(v))          score++;
            if (/[0-9]/.test(v))          score++;
            if (/[^A-Za-z0-9]/.test(v))   score++;
            const lvl = [
                {w:'0%',   c:'#E5E7EB', l:'—'},
                {w:'25%',  c:'#EF4444', l:'Lemah'},
                {w:'50%',  c:'#38BDF8', l:'Cukup'},
                {w:'75%',  c:'#3B82F6', l:'Kuat'},
                {w:'100%', c:'#10B981', l:'Sangat Kuat'},
            ][v.length === 0 ? 0 : Math.min(score, 4)];
            bar.style.width = lvl.w; bar.style.backgroundColor = lvl.c;
            txt.textContent = lvl.l; txt.style.color = lvl.c;
        });

        document.getElementById('resetForm').addEventListener('submit', function () {
            const btn = document.getElementById('btnSimpan');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
        });
    </script>
    @endpush
@endsection
