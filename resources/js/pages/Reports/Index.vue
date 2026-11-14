<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

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

const filterReport = () => {
    router.get('/reports', form, { preserveState: true });
};
</script>

<template>
    <Head title="Laporan Bulanan" />

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Laporan Bulanan & Stok Opname</h1>
        <p class="text-sm text-gray-500 mb-6">Rekapitulasi Mutasi Stok & Otomatisasi Akumulasi Transaksi Tunai.</p>

        <!-- Filter Periode Laporan -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-sidebar-border/70 mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Bulan</label>
                <select v-model="form.month" class="border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                <select v-model="form.year" class="border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
            </div>
            <button @click="filterReport" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition font-medium">Tampilkan</button>
        </div>

        <!-- Tabel Laporan Mutasi -->
        <div class="bg-white shadow-sm border border-sidebar-border/70 rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Kode</th>
                        <th class="px-6 py-3 text-left">Nama Obat</th>
                        <th class="px-6 py-3 text-center">Stok Awal</th>
                        <th class="px-6 py-3 text-center">Stok Masuk</th>
                        <th class="px-6 py-3 text-center">Stok Keluar</th>
                        <th class="px-6 py-3 text-center">Expired / Rusak</th>
                        <th class="px-6 py-3 text-center">Stok Akhir</th>
                        <th class="px-6 py-3 text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    <tr v-for="row in reportData" :key="row.code">
                        <td class="px-6 py-4 font-mono text-gray-600">{{ row.code }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ row.name }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ row.stok_awal }}</td>
                        <td class="px-6 py-4 text-center text-blue-600 font-semibold">{{ row.stok_masuk }}</td>
                        <td class="px-6 py-4 text-center text-orange-600 font-semibold">{{ row.stok_keluar }}</td>
                        <td class="px-6 py-4 text-center text-red-600 font-semibold">{{ row.expired_rusak }}</td>
                        <td class="px-6 py-4 text-center font-bold text-gray-900">{{ row.stok_akhir }}</td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">Rp {{ Number(row.total_pendapatan).toLocaleString() }}</td>
                    </tr>
                    <tr v-if="reportData.length === 0">
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Tidak ada data mutasi untuk periode ini.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>