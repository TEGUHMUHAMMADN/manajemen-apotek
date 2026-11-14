<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: AppLayout,
});

interface Medicine {
    id: number;
    code: string;
    name: string;
    rack_location: string;
    total_stock: number;
    price: number;
}

defineProps<{
    medicines: Medicine[];
}>();

const deleteMedicine = (id: number) => {
    if (confirm('Yakin ingin menghapus data obat ini?')) {
        useForm().delete(`/medicines/${id}`);
    }
};
</script>

<template>
    <Head title="Data Obat & Inventaris" />

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Data Obat (Inventaris)</h1>
                <p class="text-sm text-gray-500 mt-1">Daftar seluruh stok obat, lokasi rak, dan harga jual.</p>
            </div>
            <Link 
                href="/medicines/create" 
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-sm"
            >
                + Input / Scan Obat Masuk
            </Link>
        </div>

        <!-- Tabel Daftar Obat -->
        <div class="bg-white shadow-sm border border-sidebar-border/70 rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Obat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="med in medicines" :key="med.id" class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ med.code }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ med.name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ med.rack_location }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-indigo-600">{{ med.total_stock || 0 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ Number(med.price).toLocaleString() }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                            <Link :href="`/medicines/${med.id}`" class="text-blue-600 hover:text-blue-900">Detail</Link>
                            <Link :href="`/medicines/${med.id}/edit`" class="text-amber-600 hover:text-amber-900">Edit</Link>
                            <button @click="deleteMedicine(med.id)" class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                    <tr v-if="medicines.length === 0">
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada data obat di inventaris.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>