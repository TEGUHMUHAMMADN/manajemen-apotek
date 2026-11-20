
<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import {
    Pill,
    Package,
    AlertTriangle,
    Search,
    Plus,
    MapPin,
    Eye,
    Pencil,
    Trash2,
    Boxes,
    ClipboardList,
    CalendarDays,
    XCircle,
    ChevronRight,
    Clock,
} from "@lucide/vue";

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
    total_stock: number;
    price: number;
    minimum_stock?: number;
    batches: Batch[];
}

const props = defineProps<{
    medicines: Medicine[];
}>();

const search = ref("");

const filteredMedicines = computed(() => {
    const keyword = search.value.toLowerCase().trim();

    if (!keyword) return props.medicines;

    return props.medicines.filter((med) =>
        med.name.toLowerCase().includes(keyword) ||
        med.code.toLowerCase().includes(keyword) ||
        med.rack_location.toLowerCase().includes(keyword)
    );
});

// Total seluruh stok
const totalStock = computed(() =>
    props.medicines.reduce(
        (total, med) => total + Number(med.total_stock || 0),
        0
    )
);

// Stok menipis
const lowStock = computed(() =>
    props.medicines.filter((med) =>
        Number(med.total_stock || 0) <= Number(med.minimum_stock ?? 10) &&
        Number(med.total_stock || 0) > 0
    ).length
);

// Stok habis
const emptyStock = computed(() =>
    props.medicines.filter(
        (med) => Number(med.total_stock || 0) <= 0
    ).length
);

// Menghapus obat
const deleteMedicine = (id: number) => {
    if (confirm("Yakin ingin menghapus data obat ini?")) {
        useForm({}).delete(`/medicines/${id}`);
    }
};

// Format rupiah
const formatRupiah = (value: number) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(Number(value || 0));

// Status stok
const stockStatus = (med: Medicine) => {
    const stock = Number(med.total_stock || 0);
    const minimum = Number(med.minimum_stock ?? 10);

    if (stock <= 0) return "Habis";
    if (stock <= minimum) return "Menipis";

    return "Aman";
};

// Mengambil tanggal kedaluwarsa terdekat
const nearestExpiry = (med: Medicine): string | null => {
    const batches = med.batches ?? [];

    const validBatches = batches
        .filter((batch) => Number(batch.stock) > 0)
        .sort((a, b) =>
            String(a.expired_date).slice(0, 10).localeCompare(
                String(b.expired_date).slice(0, 10)
            )
        );

    return validBatches[0]?.expired_date ?? null;
};

// Status kedaluwarsa
const expiryStatus = (med: Medicine) => {
    const expiry = nearestExpiry(med);

    if (!expiry) return "Tidak Ada Batch";

    const dateString = String(expiry).slice(0, 10);
    const [year, month, day] = dateString.split("-").map(Number);

    const expiryDate = new Date(year, month - 1, day);

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const difference = Math.ceil(
        (expiryDate.getTime() - today.getTime()) /
        (1000 * 60 * 60 * 24)
    );

    if (difference < 0) return "Kedaluwarsa";
    if (difference <= 90) return "Hampir Kedaluwarsa";

    return "Aman";
};

// Format tanggal
const formatDate = (date: string | null) => {
    if (!date) return "-";

    const dateString = String(date).slice(0, 10);
    const [year, month, day] = dateString.split("-");

    return `${day}-${month}-${year}`;
};

// Jumlah obat hampir kedaluwarsa
const nearExpiryCount = computed(() =>
    props.medicines.filter(
        (med) => expiryStatus(med) === "Hampir Kedaluwarsa"
    ).length
);

// Jumlah obat kedaluwarsa
const expiredCount = computed(() =>
    props.medicines.filter(
        (med) => expiryStatus(med) === "Kedaluwarsa"
    ).length
);
</script>

<template>
    <Head title="Data Obat & Inventaris" />

    <div class="w-full space-y-7">

        <!-- HEADER -->
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-emerald-600">
                    <Pill class="h-4 w-4" />
                    Manajemen Persediaan
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                    Data Obat & Inventaris
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Pantau seluruh persediaan, lokasi rak, harga jual, dan masa kedaluwarsa obat.
                </p>
            </div>

            <Link
                href="/medicines/create"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 active:scale-95"
            >
                <Plus class="h-4 w-4" />
                Input / Scan Obat
            </Link>
        </div>

        <!-- STATISTICS -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">

            <!-- JENIS OBAT -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <Pill class="h-5 w-5" />
                    </div>
                    <span class="text-xs font-semibold text-emerald-600">Inventaris</span>
                </div>

                <p class="mt-4 text-sm font-medium text-slate-500">
                    Jenis Obat
                </p>

                <h3 class="mt-1 text-3xl font-bold text-slate-800">
                    {{ medicines.length }}
                </h3>
            </div>

            <!-- TOTAL STOK -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <Boxes class="h-5 w-5" />
                    </div>
                    <span class="text-xs font-semibold text-blue-600">Keseluruhan</span>
                </div>

                <p class="mt-4 text-sm font-medium text-slate-500">
                    Total Stok
                </p>

                <h3 class="mt-1 text-3xl font-bold text-slate-800">
                    {{ totalStock.toLocaleString("id-ID") }}
                </h3>
            </div>

            <!-- STOK MENIPIS -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <AlertTriangle class="h-5 w-5" />
                    </div>
                    <span class="text-xs font-semibold text-amber-600">Perhatian</span>
                </div>

                <p class="mt-4 text-sm font-medium text-slate-500">
                    Stok Menipis
                </p>

                <h3 class="mt-1 text-3xl font-bold text-slate-800">
                    {{ lowStock }}
                </h3>
            </div>

            <!-- STOK HABIS -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <Package class="h-5 w-5" />
                    </div>
                    <span class="text-xs font-semibold text-red-600">Restock</span>
                </div>

                <p class="mt-4 text-sm font-medium text-slate-500">
                    Stok Habis
                </p>

                <h3 class="mt-1 text-3xl font-bold text-slate-800">
                    {{ emptyStock }}
                </h3>
            </div>

            <!-- HAMPIR KEDALUWARSA -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                        <Clock class="h-5 w-5" />
                    </div>
                    <span class="text-xs font-semibold text-orange-600">Perhatian</span>
                </div>

                <p class="mt-4 text-sm font-medium text-slate-500">
                    Hampir Kedaluwarsa
                </p>

                <h3 class="mt-1 text-3xl font-bold text-slate-800">
                    {{ nearExpiryCount }}
                </h3>
            </div>

            <!-- OBAT KEDALUWARSA -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <XCircle class="h-5 w-5" />
                    </div>
                    <span class="text-xs font-semibold text-red-600">
                        Perlu Tindakan
                    </span>
                </div>

                <p class="mt-4 text-sm font-medium text-slate-500">
                    Obat Kedaluwarsa
                </p>

                <h3 class="mt-1 text-3xl font-bold text-slate-800">
                    {{ expiredCount }}
                </h3>
            </div>

        </div>

        <!-- INVENTORY CARDS -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <!-- SECTION HEADER -->
            <div class="flex flex-col justify-between gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center">

                <div>
                    <div class="flex items-center gap-2">
                        <ClipboardList class="h-5 w-5 text-emerald-600" />

                        <h2 class="font-bold text-slate-800">
                            Daftar Inventaris Obat
                        </h2>
                    </div>

                    <p class="mt-1 text-xs text-slate-500">
                        Kelola dan pantau informasi seluruh persediaan obat.
                    </p>
                </div>

                <!-- SEARCH -->
                <div class="relative w-full sm:w-80">
                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama, kode, atau rak..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100"
                    />
                </div>

            </div>

            <!-- CARD CONTAINER -->
            <div class="bg-slate-50/50 p-5">

                <div
                    v-if="filteredMedicines.length > 0"
                    class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3"
                >

                    <div
                        v-for="med in filteredMedicines"
                        :key="med.id"
                        class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-lg"
                    >

                        <!-- CARD TOP -->
                        <div class="border-b border-slate-100 p-5">

                            <div class="flex items-start justify-between gap-3">

                                <div class="flex min-w-0 items-center gap-3">

                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-100">
                                        <Pill class="h-6 w-6" />
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-bold text-slate-800">
                                            {{ med.name }}
                                        </h3>

                                        <p class="mt-1 font-mono text-xs text-slate-400">
                                            {{ med.code }}
                                        </p>
                                    </div>

                                </div>

                                <span
                                    v-if="stockStatus(med) === 'Aman'"
                                    class="shrink-0 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-700"
                                >
                                    Aman
                                </span>

                                <span
                                    v-else-if="stockStatus(med) === 'Menipis'"
                                    class="shrink-0 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-700"
                                >
                                    Menipis
                                </span>

                                <span
                                    v-else
                                    class="shrink-0 rounded-full bg-red-50 px-3 py-1 text-[11px] font-bold text-red-700"
                                >
                                    Habis
                                </span>

                            </div>

                        </div>

                        <!-- STOCK HIGHLIGHT -->
                        <div class="px-5 py-4">

                            <div class="flex items-center justify-between rounded-xl bg-slate-50 p-4">

                                <div>
                                    <p class="text-xs font-medium text-slate-500">
                                        Total Persediaan
                                    </p>

                                    <div class="mt-1 flex items-baseline gap-1">
                                        <span class="text-2xl font-bold text-slate-800">
                                            {{ Number(med.total_stock || 0).toLocaleString("id-ID") }}
                                        </span>

                                        <span class="text-xs text-slate-400">
                                            unit
                                        </span>
                                    </div>
                                </div>

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
                                    <Boxes class="h-5 w-5" />
                                </div>

                            </div>

                        </div>

                        <!-- MEDICINE DETAILS -->
                        <div class="space-y-4 px-5 pb-5">

                            <!-- LOCATION -->
                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-2 text-slate-500">
                                    <MapPin class="h-4 w-4" />

                                    <span class="text-xs">
                                        Lokasi Rak
                                    </span>
                                </div>

                                <span class="text-sm font-semibold text-slate-700">
                                    {{ med.rack_location || "-" }}
                                </span>

                            </div>

                            <!-- PRICE -->
                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-2 text-slate-500">
                                    <Package class="h-4 w-4" />

                                    <span class="text-xs">
                                        Harga Jual
                                    </span>
                                </div>

                                <span class="text-sm font-bold text-slate-800">
                                    {{ formatRupiah(med.price) }}
                                </span>

                            </div>

                            <!-- EXPIRY -->
                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-2 text-slate-500">
                                    <CalendarDays class="h-4 w-4" />

                                    <span class="text-xs">
                                        Kedaluwarsa
                                    </span>
                                </div>

                                <span class="text-sm font-semibold text-slate-700">
                                    {{ formatDate(nearestExpiry(med)) }}
                                </span>

                            </div>

                            <!-- EXPIRY STATUS -->
                            <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-4">

                                <span class="text-xs font-medium text-slate-500">
                                    Status Kedaluwarsa
                                </span>

                                <span
                                    v-if="expiryStatus(med) === 'Aman'"
                                    class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-700"
                                >
                                    ● Aman
                                </span>

                                <span
                                    v-else-if="expiryStatus(med) === 'Hampir Kedaluwarsa'"
                                    class="rounded-full bg-orange-50 px-3 py-1 text-[11px] font-bold text-orange-700"
                                >
                                    ● Hampir Kedaluwarsa
                                </span>

                                <span
                                    v-else-if="expiryStatus(med) === 'Kedaluwarsa'"
                                    class="rounded-full bg-red-50 px-3 py-1 text-[11px] font-bold text-red-700"
                                >
                                    ● Kedaluwarsa
                                </span>

                                <span
                                    v-else
                                    class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600"
                                >
                                    ● Tidak Ada Batch
                                </span>

                            </div>

                        </div>

                        <!-- CARD ACTIONS -->
                        <div class="flex items-center justify-between border-t border-slate-100 bg-white px-5 py-4">

                            <Link
                                :href="`/medicines/${med.id}`"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 transition hover:text-blue-800"
                            >
                                <Eye class="h-4 w-4" />
                                Detail
                                <ChevronRight class="h-3 w-3" />
                            </Link>

                            <div class="flex items-center gap-2">

                                <Link
                                    :href="`/medicines/${med.id}/edit`"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition hover:bg-amber-100"
                                    title="Edit obat"
                                >
                                    <Pencil class="h-4 w-4" />
                                </Link>

                                <button
                                    type="button"
                                    @click="deleteMedicine(med.id)"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100"
                                    title="Hapus obat"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- EMPTY STATE -->
                <div
                    v-else
                    class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"
                >

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <Pill class="h-8 w-8" />
                    </div>

                    <h3 class="mt-4 text-base font-bold text-slate-700">
                        {{ search ? "Obat tidak ditemukan" : "Belum ada data obat" }}
                    </h3>

                    <p class="mt-2 text-sm text-slate-400">
                        {{
                            search
                                ? "Coba gunakan kata kunci pencarian yang lain."
                                : "Tambahkan obat melalui tombol Input / Scan Obat."
                        }}
                    </p>

                    <Link
                        v-if="!search"
                        href="/medicines/create"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700"
                    >
                        <Plus class="h-4 w-4" />
                        Tambah Obat
                    </Link>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="flex flex-col justify-between gap-2 border-t border-slate-100 bg-white px-5 py-4 sm:flex-row sm:items-center">

                <p class="text-xs text-slate-500">
                    Menampilkan
                    <span class="font-bold text-emerald-600">
                        {{ filteredMedicines.length }}
                    </span>
                    dari
                    <span class="font-bold text-slate-700">
                        {{ medicines.length }}
                    </span>
                    data obat
                </p>

                <p class="text-xs text-slate-400">
                    MediStock Pharmacy Management
                </p>

            </div>

        </div>

    </div>
</template>
