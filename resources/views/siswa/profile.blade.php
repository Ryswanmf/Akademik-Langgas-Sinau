@extends('layouts.siswa', ['title' => 'Profil Saya', 'header' => 'Profil Siswa'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Profile Information Header Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <img src="{{ $student->photo_url }}" class="h-24 w-24 rounded-2xl object-cover ring-4 ring-slate-100 shadow-md shrink-0" alt="{{ $student->user->name }}">
            <div class="space-y-1.5 flex-1">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black text-slate-800">{{ $student->user->name }}</h2>
                    <span class="rounded-full bg-emerald-50 px-3 py-0.5 text-xs font-bold text-emerald-700 capitalize">
                        {{ $student->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-mono">NIS: <strong class="text-slate-700">{{ $student->student_number }}</strong> • Email: <strong class="text-slate-700">{{ $student->user->email }}</strong></p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-3 text-xs text-slate-600 border-t border-slate-100 mt-3">
                    <div>
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Kelas</span>
                        <span class="font-semibold">{{ $student->class->name ?? 'Belum terdaftar' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Program Keahlian</span>
                        <span class="font-semibold text-slate-800">{{ $student->program }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Asal Sekolah / Instansi</span>
                        <span class="font-bold text-emerald-700">{{ $student->school_origin ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Tanggal Masuk</span>
                        <span class="font-semibold">{{ $student->entry_date ? $student->entry_date->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form (Biodata Lengkap Siswa) -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800">Perbarui Biodata Siswa</h3>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi program keahlian, asal instansi/sekolah, kontak, dan alamat Anda.</p>
            </div>
            <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg">Data Pribadi</span>
        </div>

        <form action="{{ route('siswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Program Keahlian -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Program Keahlian *</label>
                    <input 
                        type="text" 
                        name="program" 
                        value="{{ old('program', $student->program) }}" 
                        required 
                        list="programList"
                        placeholder="cth. Rekayasa Perangkat Lunak / TKJ" 
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                    <datalist id="programList">
                        <option value="Teknologi Informasi">
                        <option value="Rekayasa Perangkat Lunak (Coding)">
                        <option value="Teknik Komputer & Jaringan (TKJ)">
                        <option value="Desain Komunikasi Visual (DKV)">
                        <option value="Aplikasi Perkantoran & Komputer Dasar">
                    </datalist>
                </div>

                <!-- Asal Instansi / Sekolah (Wajib / Tidak Opsional) -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">Asal Instansi / Sekolah *</label>
                        <span class="text-[10px] font-bold text-rose-500">Wajib Diisi</span>
                    </div>
                    <input 
                        type="text" 
                        name="school_origin" 
                        value="{{ old('school_origin', $student->school_origin) }}" 
                        required 
                        placeholder="cth. SMK Negeri 1 Batanghari / SMA / Universitas / Umum" 
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                </div>

                <!-- Nomor HP / WhatsApp -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ old('phone', $student->phone) }}" 
                        placeholder="081234567890" 
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                </div>

                <!-- Ganti Foto Profil -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ganti Foto Profil</label>
                    <input 
                        type="file" 
                        name="photo" 
                        accept="image/*" 
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                    >
                    <p class="text-[10px] text-slate-400 mt-1">Maksimal 2 MB (JPG, PNG, WEBP).</p>
                </div>
            </div>

            <!-- Alamat Domisili Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Domisili Lengkap</label>
                <textarea 
                    name="address" 
                    rows="3" 
                    placeholder="Alamat tempat tinggal / tempat PKL..."
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                >{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                <button 
                    type="submit" 
                    class="rounded-xl bg-emerald-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-colors"
                >
                    Simpan Perubahan Biodata
                </button>
            </div>
        </form>
    </div>

    <!-- Informasi Penggantian Kata Sandi Akun (Khusus Administrator) -->
    <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700 shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800">Keamanan & Perubahan Kata Sandi</h4>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Penggantian kata sandi akun siswa dikelola secara terpusat oleh <strong>Administrator LKP</strong> demi menjaga integritas akun. Hubungi admin jika membutuhkan bantuan reset kata sandi.
                </p>
            </div>
        </div>
        <a 
            href="https://wa.me/6281234567890?text=Halo%20Admin%20LKP%20Langgas%20Sinau%2C%20saya%20memerlukan%20bantuan%20reset%20kata%20sandi%20akun%20siswa" 
            target="_blank" 
            class="shrink-0 inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold transition-colors"
        >
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span>Hubungi Admin</span>
        </a>
    </div>
</div>
@endsection
