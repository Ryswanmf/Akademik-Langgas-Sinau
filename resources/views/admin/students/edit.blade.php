@extends('layouts.admin', ['title' => 'Edit Siswa', 'header' => 'Edit Data Siswa'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Data: {{ $student->user->name }}</h2>
            <p class="text-xs text-slate-500">Perbarui profil dan status akademik siswa.</p>
        </div>
        <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Akun Login -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">
                    1. Akun Login
                </h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name', $student->user->name) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email', $student->user->email) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Akademik -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">
                    2. Data Akademik
                </h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Induk Siswa (NIS) *</label>
                        <input type="text" name="student_number" value="{{ old('student_number', $student->student_number) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kelas Pelatihan</label>
                        <input 
                            type="text" 
                            name="class_name" 
                            value="{{ old('class_name', $student->class->name ?? '') }}" 
                            list="classList"
                            placeholder="cth. Reguler Pagi / Desain Grafis A" 
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                        >
                        <datalist id="classList">
                            @foreach ($classes as $c)
                                <option value="{{ $c->name }}">
                            @endforeach
                        </datalist>
                        <span class="text-[10px] text-slate-400 mt-1 block">Isi nama kelas pelatihan secara manual</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Program Keahlian *</label>
                        <input type="text" name="program" value="{{ old('program', $student->program) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Asal Instansi / Sekolah *</label>
                        <input type="text" name="school_origin" value="{{ old('school_origin', $student->school_origin) }}" required placeholder="cth. SMK Negeri 1 Batanghari / Umum" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Masuk *</label>
                        <input type="date" name="entry_date" value="{{ old('entry_date', optional($student->entry_date)->format('Y-m-d')) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Siswa *</label>
                        <select name="status" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                            <option value="aktif" {{ old('status', $student->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $student->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="lulus" {{ old('status', $student->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Foto Profil Baru</label>
                        <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>

            <!-- Section 3: Kontak & Alamat -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">
                    3. Kontak & Domisili
                </h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('address', $student->address) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
