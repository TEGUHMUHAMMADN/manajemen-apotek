<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Pill, Boxes, AlertTriangle, Clock, XCircle } from '@lucide/vue';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    stats: {
        total_types: number;
        total_stock: number;
        low_stock: number;
        near_expiry: number;
        expired: number;
    };
}>();
</script>

<template>
    <Head title="Dashboard Apotek" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Apotek</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan status stok, obat menipis, dan masa kadaluarsa (FEFO).</p>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- Total Jenis Obat -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-sidebar-border/70 dark:border-sidebar-border flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Jenis Obat</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ stats?.total_types || 0 }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                    <Pill class="w-6 h-6" />
                </div>
            </div>

            <!-- Total Stok Tersedia -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-sidebar-border/70 dark:border-sidebar-border flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Stok</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ stats?.total_stock || 0 }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                    <Boxes class="w-6 h-6" />
                </div>
            </div>

            <!-- Stok Menipis (< 10) -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-sidebar-border/70 dark:border-sidebar-border flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Stok Menipis (&lt;10)</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ stats?.low_stock || 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-full text-yellow-600">
                    <AlertTriangle class="w-6 h-6" />
                </div>
            </div>

            <!-- Hampir Kadaluarsa (H-90) -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-sidebar-border/70 dark:border-sidebar-border flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Hampir Exp (H-90)</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ stats?.near_expiry || 0 }}</p>
                </div>
                <div class="p-3 bg-orange-50 rounded-full text-orange-600">
                    <Clock class="w-6 h-6" />
                </div>
            </div>

            <!-- Sudah Kadaluarsa -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-sidebar-border/70 dark:border-sidebar-border flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Kadaluarsa</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ stats?.expired || 0 }}</p>
                </div>
                <div class="p-3 bg-red-50 rounded-full text-red-600">
                    <XCircle class="w-6 h-6" />
                </div>
            </div>

        </div>

        <!-- Bagian Informasi & Panduan Sistem -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-sidebar-border/70 dark:border-sidebar-border">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Selamat Datang di Sistem Manajemen Apotek</h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                Sistem ini terintegrasi dengan pengelolaan multi-batch berbasis <strong class="text-indigo-600">FEFO (First-Expired-First-Out)</strong>, kasir penjualan tunai, pemindaian kode obat, serta rekapitulasi laporan bulanan otomatis. Silakan gunakan menu navigasi di sebelah kiri untuk mulai mengelola data atau melakukan transaksi.
            </p>
        </div>
    </div>
</template>