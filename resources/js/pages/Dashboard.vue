<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

import {
    Pill,
    Boxes,
    AlertTriangle,
    Clock,
    XCircle,
    ArrowUpRight,
    Activity,
    ShieldCheck,
    PackageCheck,
} from "@lucide/vue";

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

// Data card dashboard
const cards = [
    {
        title: "Jenis Obat",
        key: "total_types",
        icon: Pill,
        color: "emerald",
        description: "Total variasi obat",
        href: "/medicines",
    },
    {
        title: "Total Stok",
        key: "total_stock",
        icon: Boxes,
        color: "teal",
        description: "Unit obat tersedia",
        href: "/medicines",
    },
    {
        title: "Stok Menipis",
        key: "low_stock",
        icon: AlertTriangle,
        color: "amber",
        description: "Perlu segera diperiksa",
        href: "/medicines?filter=low_stock",
    },
    {
        title: "Hampir Kedaluwarsa",
        key: "near_expiry",
        icon: Clock,
        color: "orange",
        description: "Masa kedaluwarsa H-90",
        href: "/medicines?filter=near_expiry",
    },
    {
        title: "Kedaluwarsa",
        key: "expired",
        icon: XCircle,
        color: "red",
        description: "Perlu penanganan",
        href: "/medicines?filter=expired",
    },
] as const;
</script>

<template>
    <Head title="Dashboard Apotek" />

    <div class="min-h-full space-y-7 bg-[#f6faf7] p-5 md:p-8">

        <!-- Header -->
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm font-medium text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    MediStock Management System
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-gray-900 md:text-3xl">
                    Dashboard Apotek
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Pantau kondisi stok dan persediaan obat apotek dalam satu tempat.
                </p>
            </div>

            <div class="flex w-fit items-center gap-3 rounded-xl border border-emerald-100 bg-white px-4 py-3 shadow-sm">
                <div class="rounded-lg bg-emerald-50 p-2 text-emerald-700">
                    <Activity class="h-5 w-5" />
                </div>

                <div>
                    <p class="text-xs text-gray-400">Status Sistem</p>
                    <p class="text-sm font-semibold text-emerald-700">
                        Sistem Aktif
                    </p>
                </div>
            </div>
        </div>

        <!-- Welcome Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-600 p-6 text-white shadow-lg md:p-8">

            <div class="relative z-10 max-w-2xl">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-medium text-emerald-50">
                    <ShieldCheck class="h-4 w-4" />
                    Pharmacy Inventory System
                </div>

                <h2 class="text-2xl font-bold leading-tight md:text-3xl">
                    Selamat Datang di MediStock
                </h2>

                <p class="mt-3 max-w-xl text-sm leading-relaxed text-emerald-50 md:text-base">
                    Kelola persediaan obat dengan lebih mudah, teratur, dan efisien.
                    Pantau stok, periksa masa kedaluwarsa, serta pastikan pengelolaan
                    obat berjalan dengan baik.
                </p>

                <div class="mt-6 flex flex-wrap gap-3 text-xs font-medium">
                    <span class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-3 py-2">
                        <PackageCheck class="h-4 w-4" />
                        Manajemen Stok
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-3 py-2">
                        <Clock class="h-4 w-4" />
                        Monitoring FEFO
                    </span>
                </div>
            </div>

            <div class="pointer-events-none absolute -right-10 -top-16 h-64 w-64 rounded-full border-[35px] border-white/10"></div>

            <div class="pointer-events-none absolute -bottom-28 right-28 h-64 w-64 rounded-full bg-white/5"></div>

            <Pill class="pointer-events-none absolute bottom-8 right-12 hidden h-28 w-28 rotate-[-25deg] text-white/10 md:block" />
        </div>

        <!-- Statistics -->
        <section>
            <div class="mb-4">
                <h2 class="text-lg font-bold text-gray-900">
                    Ringkasan Persediaan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi terkini mengenai kondisi obat.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

                <!-- Clickable Cards -->
                <Link
                    v-for="card in cards"
                    :key="card.key"
                    :href="card.href"
                    class="group block cursor-pointer rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-md"
                >
                    <div class="flex items-start justify-between">
                        <div
                            class="rounded-xl p-3"
                            :class="{
                                'bg-emerald-50 text-emerald-600': card.color === 'emerald',
                                'bg-teal-50 text-teal-600': card.color === 'teal',
                                'bg-amber-50 text-amber-600': card.color === 'amber',
                                'bg-orange-50 text-orange-600': card.color === 'orange',
                                'bg-red-50 text-red-600': card.color === 'red',
                            }"
                        >
                            <component :is="card.icon" class="h-6 w-6" />
                        </div>

                        <ArrowUpRight class="h-4 w-4 text-gray-300 transition group-hover:text-emerald-600" />
                    </div>

                    <div class="mt-5">
                        <p class="text-sm font-medium text-gray-500">
                            {{ card.title }}
                        </p>

                        <p class="mt-1 text-3xl font-bold tracking-tight text-gray-900">
                            {{ stats?.[card.key] ?? 0 }}
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            {{ card.description }}
                        </p>

                        <p class="mt-3 text-xs font-semibold text-emerald-600 opacity-0 transition group-hover:opacity-100">
                            Lihat Detail →
                        </p>
                    </div>
                </Link>

            </div>
        </section>

        <!-- Information -->
        <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">

            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2">

                <div class="flex items-center gap-3">
                    <div class="rounded-xl bg-emerald-50 p-3 text-emerald-700">
                        <ShieldCheck class="h-6 w-6" />
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            Pengelolaan Obat Terintegrasi
                        </h2>

                        <p class="text-xs text-gray-400">
                            Fitur utama MediStock
                        </p>
                    </div>
                </div>

                <p class="mt-5 text-sm leading-7 text-gray-600">
                    MediStock membantu pengelolaan persediaan obat melalui
                    pencatatan stok, pengelolaan multi-batch, pemantauan masa
                    kedaluwarsa, dan transaksi penjualan. Sistem juga menerapkan
                    metode
                    <strong class="font-semibold text-emerald-700">
                        FEFO (First-Expired-First-Out)
                    </strong>
                    untuk memprioritaskan penggunaan stok obat dengan tanggal
                    kedaluwarsa paling dekat.
                </p>

                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="rounded-xl bg-emerald-50/70 p-4">
                        <p class="text-sm font-semibold text-emerald-800">
                            Manajemen Stok
                        </p>

                        <p class="mt-1 text-xs leading-relaxed text-emerald-700">
                            Membantu memantau jumlah dan ketersediaan obat.
                        </p>
                    </div>

                    <div class="rounded-xl bg-teal-50/70 p-4">
                        <p class="text-sm font-semibold text-teal-800">
                            Monitoring Kedaluwarsa
                        </p>

                        <p class="mt-1 text-xs leading-relaxed text-teal-700">
                            Membantu mengidentifikasi obat yang mendekati masa
                            kedaluwarsa.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Attention Panel -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

                <h2 class="font-bold text-gray-900">
                    Perlu Diperhatikan
                </h2>

                <p class="mt-1 text-xs text-gray-400">
                    Ringkasan kondisi yang perlu ditindaklanjuti.
                </p>

                <div class="mt-5 space-y-4">

                    <Link
                        href="/medicines?filter=low_stock"
                        class="flex items-center justify-between rounded-xl bg-amber-50 p-4 transition hover:bg-amber-100"
                    >
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-amber-100 p-2 text-amber-600">
                                <AlertTriangle class="h-5 w-5" />
                            </div>

                            <span class="text-sm font-medium text-gray-700">
                                Stok Menipis
                            </span>
                        </div>

                        <span class="text-lg font-bold text-amber-700">
                            {{ stats?.low_stock ?? 0 }}
                        </span>
                    </Link>

                    <Link
                        href="/medicines?filter=near_expiry"
                        class="flex items-center justify-between rounded-xl bg-orange-50 p-4 transition hover:bg-orange-100"
                    >
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-orange-100 p-2 text-orange-600">
                                <Clock class="h-5 w-5" />
                            </div>

                            <span class="text-sm font-medium text-gray-700">
                                Hampir Kedaluwarsa
                            </span>
                        </div>

                        <span class="text-lg font-bold text-orange-700">
                            {{ stats?.near_expiry ?? 0 }}
                        </span>
                    </Link>

                    <Link
                        href="/medicines?filter=expired"
                        class="flex items-center justify-between rounded-xl bg-red-50 p-4 transition hover:bg-red-100"
                    >
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-red-100 p-2 text-red-600">
                                <XCircle class="h-5 w-5" />
                            </div>

                            <span class="text-sm font-medium text-gray-700">
                                Kedaluwarsa
                            </span>
                        </div>

                        <span class="text-lg font-bold text-red-700">
                            {{ stats?.expired ?? 0 }}
                        </span>
                    </Link>

                </div>

                <p class="mt-5 text-xs leading-relaxed text-gray-400">
                    Periksa data obat secara berkala untuk menjaga kualitas dan
                    ketersediaan persediaan.
                </p>
            </div>
        </section>

        <!-- Footer -->
        <p class="pb-2 text-center text-xs text-gray-400">
            MediStock · Sistem Manajemen Persediaan Apotek
        </p>

    </div>
</template>
