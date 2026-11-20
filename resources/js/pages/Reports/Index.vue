
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive, computed } from 'vue';

defineOptions({
    layout: AppLayout,
});

interface ReportRow {
    code: string;
    name: string;
    stok_awal: number;
    stok_masuk: number;
    stok_keluar: number;
    expired_rusak: number;
    stok_akhir: number;
    total_pendapatan: number;
}

const props = defineProps<{
    reportData: ReportRow[];
    filters: { month: string; year: string };
}>();

const form = reactive({
    month: props.filters.month,
    year: props.filters.year,
});

const months = [
    { value: '01', label: 'Januari' },
    { value: '02', label: 'Februari' },
    { value: '03', label: 'Maret' },
    { value: '04', label: 'April' },
    { value: '05', label: 'Mei' },
    { value: '06', label: 'Juni' },
    { value: '07', label: 'Juli' },
    { value: '08', label: 'Agustus' },
    { value: '09', label: 'September' },
    { value: '10', label: 'Oktober' },
    { value: '11', label: 'November' },
    { value: '12', label: 'Desember' },
];

const currentYear = new Date().getFullYear();
const years = Array.from(
    { length: 6 },
    (_, index) => String(currentYear - index)
);

const filterReport = () => {
    router.get('/reports', {
        month: form.month,
        year: form.year,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Link export Excel
const exportUrl = computed(() =>
    `/reports/export?month=${encodeURIComponent(form.month)}&year=${encodeURIComponent(form.year)}`
);

// Ringkasan laporan
const totalMasuk = computed(() =>
    props.reportData.reduce((sum, row) => sum + Number(row.stok_masuk || 0), 0)
);

const totalKeluar = computed(() =>
    props.reportData.reduce((sum, row) => sum + Number(row.stok_keluar || 0), 0)
);

const totalStok = computed(() =>
    props.reportData.reduce((sum, row) => sum + Number(row.stok_akhir || 0), 0)
);

const totalPendapatan = computed(() =>
    props.reportData.reduce((sum, row) => sum + Number(row.total_pendapatan || 0), 0)
);

const formatRupiah = (value: number) =>
    'Rp ' + Number(value || 0).toLocaleString('id-ID');
</script>

<template>
    <Head title="Laporan Bulanan & Stok Opname" />

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">
                        Laporan Bulanan & Stok Opname
                    </h1>
                    <p class="text-sm text-slate-500 mt-2">
                        Rekapitulasi mutasi stok dan akumulasi transaksi apotek.
                    </p>
                </div>

                <div class="flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-100 px-4 py-2 rounded-full w-fit">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Laporan Aktif
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="bg-emerald-50 text-emerald-600 p-2 rounded-lg">
                        <span class="font-bold">01</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Filter Periode Laporan</h2>
                        <p class="text-xs text-slate-500">Pilih bulan dan tahun laporan.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-end gap-4">
                    <div class="w-full sm:w-56">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Bulan
                        </label>
                        <select
                            v-model="form.month"
                            class="w-full h-11 px-3 bg-white text-slate-800 border border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none"
                        >
                            <option v-for="month in months" :key="month.value" :value="month.value">
                                {{ month.label }}
                            </option>
                        </select>
                    </div>

                    <div class="w-full sm:w-40">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Tahun
                        </label>
                        <select
                            v-model="form.year"
                            class="w-full h-11 px-3 bg-white text-slate-800 border border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none"
                        >
                            <option v-for="year in years" :key="year" :value="year">
                                {{ year }}
                            </option>
                        </select>
                    </div>

                    <button
                        @click="filterReport"
                        class="h-11 px-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition shadow-sm"
                    >
                        Tampilkan Laporan
                    </button>

                    <a
                        :href="exportUrl"
                        class="h-11 px-6 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition shadow-sm"
                    >
                        <span>↓</span>
                        Export Excel
                    </a>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-slate-500 font-medium">Total Obat</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-2">
                        {{ reportData.length }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-2">Jenis obat terdata</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-slate-500 font-medium">Total Stok Masuk</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-2">
                        {{ totalMasuk.toLocaleString('id-ID') }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-2">Unit masuk periode ini</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-slate-500 font-medium">Total Stok Keluar</p>
                    <h3 class="text-2xl font-bold text-orange-600 mt-2">
                        {{ totalKeluar.toLocaleString('id-ID') }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-2">Unit keluar periode ini</p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-sm text-slate-500 font-medium">Total Pendapatan</p>
                    <h3 class="text-xl font-bold text-emerald-600 mt-2">
                        {{ formatRupiah(totalPendapatan) }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-2">Akumulasi pendapatan</p>
                </div>

            </div>

            <!-- Tabel -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Rekapitulasi Mutasi Stok
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Detail pergerakan stok seluruh obat.
                        </p>
                    </div>

                    <span class="text-xs font-semibold bg-emerald-50 text-emerald-700 px-3 py-2 rounded-full w-fit">
                        {{ reportData.length }} Data Obat
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-[1100px] w-full">
                        <thead class="bg-slate-50">
                            <tr class="text-xs uppercase tracking-wider text-slate-600">
                                <th class="px-5 py-4 text-left font-bold">Kode</th>
                                <th class="px-5 py-4 text-left font-bold">Nama Obat</th>
                                <th class="px-5 py-4 text-center font-bold">Stok Awal</th>
                                <th class="px-5 py-4 text-center font-bold">Stok Masuk</th>
                                <th class="px-5 py-4 text-center font-bold">Stok Keluar</th>
                                <th class="px-5 py-4 text-center font-bold">Expired / Rusak</th>
                                <th class="px-5 py-4 text-center font-bold">Stok Akhir</th>
                                <th class="px-5 py-4 text-right font-bold">Total Pendapatan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="row in reportData"
                                :key="row.code"
                                class="hover:bg-slate-50 transition"
                            >
                                <td class="px-5 py-4 text-sm font-mono text-slate-600">
                                    {{ row.code }}
                                </td>

                                <td class="px-5 py-4 text-sm font-semibold text-slate-800">
                                    {{ row.name }}
                                </td>

                                <td class="px-5 py-4 text-center text-sm text-slate-700">
                                    {{ row.stok_awal }}
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-semibold text-blue-600">
                                    +{{ row.stok_masuk }}
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-semibold text-orange-600">
                                    -{{ row.stok_keluar }}
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-semibold text-red-600">
                                    {{ row.expired_rusak }}
                                </td>

                                <td class="px-5 py-4 text-center text-sm font-bold text-slate-800">
                                    {{ row.stok_akhir }}
                                </td>

                                <td class="px-5 py-4 text-right text-sm font-bold text-emerald-600">
                                    {{ formatRupiah(row.total_pendapatan) }}
                                </td>
                            </tr>

                            <tr v-if="reportData.length === 0">
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="text-4xl mb-3">📦</div>
                                    <p class="font-semibold text-slate-700">
                                        Belum Ada Data Laporan
                                    </p>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Tidak ada mutasi obat untuk periode yang dipilih.
                                    </p>
                                </td>
                            </tr>
                        </tbody>

                        <tfoot v-if="reportData.length > 0" class="bg-slate-50 border-t border-slate-200">
                            <tr class="font-bold text-slate-800 text-sm">
                                <td colspan="3" class="px-5 py-4">
                                    TOTAL
                                </td>
                                <td class="px-5 py-4 text-center text-blue-600">
                                    {{ totalMasuk }}
                                </td>
                                <td class="px-5 py-4 text-center text-orange-600">
                                    {{ totalKeluar }}
                                </td>
                                <td class="px-5 py-4 text-center text-red-600">
                                    {{ reportData.reduce((sum, row) => sum + Number(row.expired_rusak || 0), 0) }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    {{ totalStok }}
                                </td>
                                <td class="px-5 py-4 text-right text-emerald-600">
                                    {{ formatRupiah(totalPendapatan) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 text-xs text-slate-400">
                    MediStock Pharmacy Management System
                </div>

            </div>

        </div>
    </div>
</template>
