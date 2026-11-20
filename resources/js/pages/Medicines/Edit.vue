
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineOptions({
    layout: AppLayout,
});

interface Category {
    id: number;
    name: string;
}

interface Medicine {
    id: number;
    code: string;
    name: string;
    category_id: number;
    price: number;
    minimum_stock: number;
    rack_location: string;
}

const props = defineProps<{
    medicine: Medicine;
    categories: Category[];
}>();

const form = useForm({
    code: props.medicine.code,
    name: props.medicine.name,
    category_id: props.medicine.category_id,
    price: props.medicine.price,
    minimum_stock: props.medicine.minimum_stock,
    rack_location: props.medicine.rack_location,
});

const submit = () => {
    form.put(`/medicines/${props.medicine.id}`, {
        onSuccess: () => {
            alert('Data obat berhasil diperbarui!');
        },
    });
};

// Styling input
const inputClass =
    'w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 shadow-sm placeholder:text-gray-400 transition-all duration-200 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100';

const labelClass =
    'mb-2 block text-sm font-semibold text-gray-700';
</script>

<template>
    <Head title="Edit Data Obat" />

    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-5xl">

            <!-- Header -->
            <div class="mb-8">
                <div class="mb-3 flex items-center gap-2 text-sm text-gray-500">
                    <Link
                        href="/medicines"
                        class="transition hover:text-emerald-600"
                    >
                        Data Obat
                    </Link>

                    <span>/</span>

                    <span class="font-medium text-emerald-600">
                        Edit Obat
                    </span>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4">

                    <div>
                        <div class="mb-2 flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                    />
                                </svg>
                            </div>

                            <div>
                                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                                    Edit Data Obat
                                </h1>

                                <p class="mt-1 text-sm text-gray-500">
                                    Perbarui informasi master data obat MediStock.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-semibold text-emerald-700">
                            Data Obat
                        </span>
                    </div>

                </div>
            </div>

            <!-- Form Card -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <!-- Card Header -->
                <div class="border-b border-gray-100 px-6 py-5 sm:px-8">
                    <h2 class="text-lg font-bold text-gray-900">
                        Informasi Obat
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Silakan periksa dan ubah informasi obat di bawah ini.
                    </p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="p-6 sm:p-8">

                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">

                        <!-- Kode Obat -->
                        <div>
                            <label :class="labelClass">
                                Kode Barcode / QR Code
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                v-model="form.code"
                                :class="[inputClass, form.errors.code ? 'border-red-500' : '']"
                                placeholder="Masukkan kode obat"
                                autocomplete="off"
                                required
                            />

                            <p v-if="form.errors.code" class="mt-1 text-xs text-red-600">
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <!-- Nama Obat -->
                        <div>
                            <label :class="labelClass">
                                Nama Obat
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                v-model="form.name"
                                :class="[inputClass, form.errors.name ? 'border-red-500' : '']"
                                placeholder="Masukkan nama obat"
                                required
                            />

                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label :class="labelClass">
                                Kategori Obat
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                v-model.number="form.category_id"
                                :class="[inputClass, form.errors.category_id ? 'border-red-500' : '']"
                                required
                            >
                                <option value="" disabled>
                                    Pilih kategori obat
                                </option>

                                <option
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    :value="cat.id"
                                >
                                    {{ cat.name }}
                                </option>
                            </select>

                            <p v-if="form.errors.category_id" class="mt-1 text-xs text-red-600">
                                {{ form.errors.category_id }}
                            </p>
                        </div>

                        <!-- Harga -->
                        <div>
                            <label :class="labelClass">
                                Harga Jual (Rp)
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-500">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    v-model.number="form.price"
                                    min="0"
                                    step="0.01"
                                    :class="[inputClass, 'pl-12', form.errors.price ? 'border-red-500' : '']"
                                    placeholder="Contoh: 20000"
                                    required
                                />
                            </div>

                            <p v-if="form.errors.price" class="mt-1 text-xs text-red-600">
                                {{ form.errors.price }}
                            </p>
                        </div>

                        <!-- Minimal Stok -->
                        <div>
                            <label :class="labelClass">
                                Minimal Stok (Alert)
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="number"
                                v-model.number="form.minimum_stock"
                                min="0"
                                step="1"
                                :class="[inputClass, form.errors.minimum_stock ? 'border-red-500' : '']"
                                placeholder="Contoh: 10"
                                required
                            />

                            <p class="mt-1 text-xs text-gray-400">
                                Batas stok minimum untuk peringatan persediaan.
                            </p>

                            <p v-if="form.errors.minimum_stock" class="mt-1 text-xs text-red-600">
                                {{ form.errors.minimum_stock }}
                            </p>
                        </div>

                        <!-- Lokasi Rak -->
                        <div>
                            <label :class="labelClass">
                                Lokasi Rak
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                v-model="form.rack_location"
                                :class="[inputClass, form.errors.rack_location ? 'border-red-500' : '']"
                                placeholder="Contoh: A3"
                                required
                            />

                            <p v-if="form.errors.rack_location" class="mt-1 text-xs text-red-600">
                                {{ form.errors.rack_location }}
                            </p>
                        </div>

                    </div>

                    <!-- Footer Form -->
                    <div class="mt-10 flex flex-col-reverse justify-end gap-3 border-t border-gray-100 pt-6 sm:flex-row">

                        <Link
                            href="/medicines"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Batal
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <svg
                                v-if="!form.processing"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                            <span>
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </span>
                        </button>

                    </div>

                </form>
            </div>

            <!-- Footer Information -->
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

                <p class="text-sm text-blue-700">
                    Pastikan seluruh informasi obat sudah benar sebelum menekan tombol Simpan Perubahan.
                </p>
            </div>

        </div>
    </div>
</template>
