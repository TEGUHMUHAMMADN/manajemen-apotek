<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({
    layout: AppLayout,
});

interface Batch {
    id: number;
    batch_number: string;
    stock: number;
    expired_date: string;
}

interface Medicine {
    id: number;
    code: string;
    name: string;
    rack_location: string;
    price: number;
    minimum_stock: number;
    category: {
        name: string;
    };
    batches: Batch[];
}

defineProps<{
    medicine: Medicine;
}>();
</script>

<template>
    <Head :title="`Detail - ${medicine.name}`" />

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Informasi Obat</h1>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap spesifikasi dan daftar batch (FEFO).</p>
            </div>
            <Link 
                href="/medicines" 
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition"
            >
                &larr; Kembali ke Inventaris
            </Link>
        </div>

        <!-- Info Utama Obat -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-sidebar-border/70 mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Kode Barcode</p>
                <p class="text-base font-mono font-semibold text-gray-800 mt-1">{{ medicine.code }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Nama Obat</p>
                <p class="text-base font-bold text-gray-800 mt-1">{{ medicine.name }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Kategori</p>
                <p class="text-base font-semibold text-indigo-600 mt-1">{{ medicine.category?.name || '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Harga Jual</p>
                <p class="text-base font-semibold text-gray-800 mt-1">Rp {{ Number(medicine.price).toLocaleString() }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Lokasi Rak</p>
                <p class="text-base font-semibold text-gray-800 mt-1">{{ medicine.rack_location }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase">Minimal Stok (Alert)</p>
                <p class="text-base font-semibold text-orange-600 mt-1">{{ medicine.minimum_stock }} unit</p>
            </div>
        </div>

        <!-- Daftar Batch / FEFO -->
        <div class="bg-white shadow-sm border border-sidebar-border/70 rounded-xl overflow-hidden p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Daftar Batch Stok (Urutan FEFO - Cepat Kadaluarsa Didahulukan)</h2>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Batch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="batch in medicine.batches" :key="batch.id">
                        <td class="px-6 py-4 text-sm font-mono text-gray-800">{{ batch.batch_number }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-indigo-600">{{ batch.stock }} unit</td>
                        <td class="px-6 py-4 text-sm font-semibold text-red-600">{{ batch.expired_date }}</td>
                    </tr>
                    <tr v-if="!medicine.batches || medicine.batches.length === 0">
                        <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada batch aktif untuk obat ini.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>