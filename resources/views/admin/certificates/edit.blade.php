@extends('layouts.admin', ['title' => 'Edit Nilai & Sertifikat', 'header' => 'Edit Nilai & Sertifikat'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="certificateEditForm()">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Nilai & Sertifikat: {{ $certificate->name }}</h2>
            <p class="text-xs text-slate-500">Perbarui 6 komponen nilai evaluasi kompetensi, berkas sertifikat, dan status publikasi siswa.</p>
        </div>
        <a href="{{ route('admin.certificates.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <form action="{{ route('admin.certificates.update', $certificate) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card 1: Data Identitas Siswa & Sertifikat -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 text-xs">1</span>
                    Identitas Siswa & Sertifikat
                </h3>
            </div>

            <!-- Pilih Siswa -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Siswa Penerima *</label>
                <select 
                    name="student_id" 
                    required 
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                >
                    @foreach ($students as $s)
                        <option 
                            value="{{ $s->id }}" 
                            data-attendance="{{ $s->attendance_rate }}"
                            {{ old('student_id', $certificate->student_id) == $s->id ? 'selected' : '' }}
                        >
                            {{ $s->user->name }} (NIS: {{ $s->student_number }}) - {{ $s->school_origin ?: 'Umum' }}
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Sertifikat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama / Judul Sertifikat *</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name', $certificate->name) }}" 
                    required 
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                >
                @error('name')
                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No Sertifikat & Program -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Sertifikat *</label>
                    <input 
                        type="text" 
                        name="certificate_number" 
                        value="{{ old('certificate_number', $certificate->certificate_number) }}" 
                        required 
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    >
                    @error('certificate_number')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Program Keahlian *</label>
                    <input 
                        type="text" 
                        name="program" 
                        value="{{ old('program', $certificate->program) }}" 
                        required 
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    >
                    @error('program')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tanggal Terbit & Upload Berkas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Diterbitkan *</label>
                    <input 
                        type="date" 
                        name="issued_date" 
                        value="{{ old('issued_date', optional($certificate->issued_date)->format('Y-m-d')) }}" 
                        required 
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    >
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ganti Berkas Sertifikat (Kosongkan jika tetap)</label>
                    <input 
                        type="file" 
                        name="file" 
                        accept=".pdf,image/*" 
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    >
                    @if ($certificate->file)
                        <p class="text-[10px] text-slate-500 mt-1">
                            Berkas tersimpan saat ini: 
                            <a href="{{ route('admin.certificates.download', $certificate) }}" class="text-indigo-600 font-bold underline">
                                Unduh File Berkas
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Penanda Tangan Sertifikat (Mentor & Pimpinan) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Mentor Penanda Tangan</label>
                    <input 
                        type="text" 
                        name="mentor_name" 
                        value="{{ old('mentor_name', $certificate->mentor_name) }}" 
                        placeholder="cth. Ahmad Fauzi, S.Kom. / Tim Mentor" 
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    >
                    <p class="text-[10px] text-slate-400 mt-1">Dicantumkan pada tanda tangan mentor sebelah kiri sertifikat.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pimpinan LKP Penanda Tangan</label>
                    <input 
                        type="text" 
                        name="leader_name" 
                        value="{{ old('leader_name', $certificate->leader_name) }}" 
                        placeholder="cth. Drs. Riswan, M.Kom. / Pimpinan Lembaga" 
                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    >
                    <p class="text-[10px] text-slate-400 mt-1">Dicantumkan pada tanda tangan pimpinan sebelah kanan sertifikat.</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Form Penilaian 6 Komponen Nilai -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-slate-100 gap-2">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 text-xs">2</span>
                        Komponen Nilai Evaluasi (Skala 0 - 100)
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Penilaian aspek kompetensi & etika kerja siswa selama pelatihan.</p>
                </div>
                
                <!-- Live Score Calculator Badge -->
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-200/80">
                    <span class="text-xs font-bold text-slate-600">Rata-rata:</span>
                    <span class="text-lg font-black text-indigo-700" x-text="computedAverage">0.0</span>
                    <span class="rounded-md bg-purple-100 text-purple-700 px-2 py-0.5 text-[10px] font-extrabold uppercase" x-text="computedPredicate">-</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- 1. Disiplin -->
                <div class="rounded-2xl border border-slate-200/80 p-4 bg-slate-50/40">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-800">1. Disiplin</label>
                        <span class="text-[10px] text-slate-400">0-100</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mb-2">Ketaatan waktu dan aturan.</p>
                    <input 
                        type="number" 
                        name="score_discipline" 
                        step="0.01" 
                        min="0" 
                        max="100" 
                        x-model.number="scores.discipline"
                        placeholder="85" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                </div>

                <!-- 2. Inisiatif & Kreatifitas -->
                <div class="rounded-2xl border border-slate-200/80 p-4 bg-slate-50/40">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-800">2. Inisiatif & Kreatifitas</label>
                        <span class="text-[10px] text-slate-400">0-100</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mb-2">Ide kreatif & proaktif tugas.</p>
                    <input 
                        type="number" 
                        name="score_initiative" 
                        step="0.01" 
                        min="0" 
                        max="100" 
                        x-model.number="scores.initiative"
                        placeholder="88" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                </div>

                <!-- 3. Kerja sama -->
                <div class="rounded-2xl border border-slate-200/80 p-4 bg-slate-50/40">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-800">3. Kerja sama</label>
                        <span class="text-[10px] text-slate-400">0-100</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mb-2">Kolaborasi & komunikasi tim.</p>
                    <input 
                        type="number" 
                        name="score_teamwork" 
                        step="0.01" 
                        min="0" 
                        max="100" 
                        x-model.number="scores.teamwork"
                        placeholder="90" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                </div>

                <!-- 4. Tanggung Jawab -->
                <div class="rounded-2xl border border-slate-200/80 p-4 bg-slate-50/40">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-800">4. Tanggung Jawab</label>
                        <span class="text-[10px] text-slate-400">0-100</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mb-2">Penyelesaian target tuntas.</p>
                    <input 
                        type="number" 
                        name="score_responsibility" 
                        step="0.01" 
                        min="0" 
                        max="100" 
                        x-model.number="scores.responsibility"
                        placeholder="92" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                </div>

                <!-- 5. Sikap -->
                <div class="rounded-2xl border border-slate-200/80 p-4 bg-slate-50/40">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-800">5. Sikap</label>
                        <span class="text-[10px] text-slate-400">0-100</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mb-2">Etika, integritas & adab.</p>
                    <input 
                        type="number" 
                        name="score_attitude" 
                        step="0.01" 
                        min="0" 
                        max="100" 
                        x-model.number="scores.attitude"
                        placeholder="95" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                </div>

                <!-- 6. Kehadiran -->
                <div class="rounded-2xl border border-slate-200/80 p-4 bg-slate-50/40">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-800">6. Kehadiran</label>
                        <button 
                            type="button" 
                            @click="useSystemAttendance()" 
                            class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 underline"
                            title="Ambil persentase kehadiran dari sistem absensi siswa"
                        >
                            Ambil % Absensi
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-500 mb-2">Konsistensi presensi siswa.</p>
                    <input 
                        type="number" 
                        name="score_attendance" 
                        step="0.01" 
                        min="0" 
                        max="100" 
                        x-model.number="scores.attendance"
                        placeholder="90" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
                    >
                </div>
            </div>

            <!-- Catatan Evaluasi Mentor -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Evaluasi / Pesan Mentor</label>
                <textarea 
                    name="assessment_notes" 
                    rows="3" 
                    placeholder="Tuliskan catatan apresiasi, evaluasi kompetensi, atau rekomendasi pengembangan diri siswa..." 
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                >{{ old('assessment_notes', $certificate->assessment_notes) }}</textarea>
            </div>
        </div>

        <!-- Card 3: Status Publikasi ke Halaman Siswa -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-purple-100 text-purple-700 text-xs">3</span>
                    Status Publikasi ke Siswa
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Atur visibilitas data nilai & sertifikat ini di halaman siswa.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Pilihan 1: Publikasikan Langsung -->
                <label class="relative flex cursor-pointer rounded-2xl border p-4 transition-all" :class="isPublished ? 'border-emerald-500 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white hover:border-slate-300'">
                    <input type="radio" name="is_published" value="1" x-model="isPublished" :checked="isPublished" class="sr-only">
                    <div class="flex items-start gap-3">
                        <div class="flex h-5 w-5 items-center justify-center rounded-full border" :class="isPublished ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 bg-white'">
                            <span class="h-2 w-2 rounded-full bg-white" x-show="isPublished"></span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Publikasikan ke Siswa</p>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                Nilai 6 kriteria dan berkas sertifikat <strong>dapat dilihat</strong> oleh siswa di halaman portal mereka.
                            </p>
                        </div>
                    </div>
                </label>

                <!-- Pilihan 2: Simpan Sebagai Draft -->
                <label class="relative flex cursor-pointer rounded-2xl border p-4 transition-all" :class="!isPublished ? 'border-amber-500 bg-amber-50/40 ring-2 ring-amber-500/20' : 'border-slate-200 bg-white hover:border-slate-300'">
                    <input type="radio" name="is_published" value="0" x-model="isPublished" :checked="!isPublished" class="sr-only">
                    <div class="flex items-start gap-3">
                        <div class="flex h-5 w-5 items-center justify-center rounded-full border" :class="!isPublished ? 'border-amber-600 bg-amber-600 text-white' : 'border-slate-300 bg-white'">
                            <span class="h-2 w-2 rounded-full bg-white" x-show="!isPublished"></span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Simpan sebagai Draft</p>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                Data <strong>disembunyikan dari siswa</strong> hingga siap dipublikasikan.
                            </p>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Submit Button Area -->
        <div class="pt-2 flex items-center justify-end gap-3">
            <a href="{{ route('admin.certificates.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                Batal
            </a>
            <button 
                type="submit" 
                class="rounded-xl px-7 py-2.5 text-xs font-bold text-white shadow-md transition-colors"
                :class="isPublished ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/20'"
            >
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
function certificateEditForm() {
    return {
        isPublished: {{ old('is_published', $certificate->is_published) ? 'true' : 'false' }},
        scores: {
            discipline: {{ old('score_discipline', $certificate->score_discipline ?? 85) }},
            initiative: {{ old('score_initiative', $certificate->score_initiative ?? 85) }},
            teamwork: {{ old('score_teamwork', $certificate->score_teamwork ?? 85) }},
            responsibility: {{ old('score_responsibility', $certificate->score_responsibility ?? 85) }},
            attitude: {{ old('score_attitude', $certificate->score_attitude ?? 90) }},
            attendance: {{ old('score_attendance', $certificate->score_attendance ?? 90) }},
        },

        useSystemAttendance() {
            const selectEl = document.querySelector('select[name="student_id"]');
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.attendance) {
                this.scores.attendance = parseFloat(selectedOpt.dataset.attendance);
            }
        },

        get computedAverage() {
            const vals = [
                parseFloat(this.scores.discipline),
                parseFloat(this.scores.initiative),
                parseFloat(this.scores.teamwork),
                parseFloat(this.scores.responsibility),
                parseFloat(this.scores.attitude),
                parseFloat(this.scores.attendance),
            ].filter(v => !isNaN(v));

            if (vals.length === 0) return '0.0';
            const avg = vals.reduce((a, b) => a + b, 0) / vals.length;
            return avg.toFixed(1);
        },

        get computedPredicate() {
            const avg = parseFloat(this.computedAverage);
            if (isNaN(avg) || avg === 0) return '-';
            if (avg >= 85) return 'A (Sangat Baik)';
            if (avg >= 75) return 'B (Baik)';
            if (avg >= 60) return 'C (Cukup)';
            return 'D (Kurang)';
        }
    }
}
</script>
@endsection
