@extends('layouts.app')

@section('title', 'Masuk Akun — EnergiKita')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 space-y-8">
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-extrabold text-slate-900">Masuk ke EnergiKita</h1>
        <p class="text-sm text-slate-600">Pilih akun demonstrasi atau masuk dengan kredensial Anda.</p>
    </div>

    <!-- Quick 1-Click Demo Login Pills -->
    <div class="p-5 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 space-y-3">
        <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider">Akses Cepat Pengujian (1-Klik):</p>
        <div class="grid grid-cols-2 gap-2.5">
            <a href="{{ route('demo.switch', 'mahasiswa') }}" class="p-3 rounded-xl bg-white border border-emerald-300 text-center hover:bg-emerald-100/50 transition">
                <span class="block text-xs font-bold text-slate-900">Faris Dzakiy</span>
                <span class="block text-[11px] text-emerald-700 font-medium">Mahasiswa (85 XP)</span>
            </a>
            <a href="{{ route('demo.switch', 'sre') }}" class="p-3 rounded-xl bg-white border border-purple-300 text-center hover:bg-purple-100/50 transition">
                <span class="block text-xs font-bold text-slate-900">Admin SRE</span>
                <span class="block text-[11px] text-purple-700 font-medium">Organisasi (450 XP)</span>
            </a>
        </div>
    </div>

    <!-- Form Login Standar -->
    <form method="POST" action="{{ route('login.post') }}" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs space-y-5">
        @csrf

        @if($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 text-rose-700 text-xs border border-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Alamat Email</label>
            <input type="email" name="email" value="faris@student.ac.id" required 
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi</label>
            <input type="password" name="password" value="password123" required 
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>

        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/25 transition">
            Masuk Sekarang
        </button>
    </form>
</div>
@endsection

