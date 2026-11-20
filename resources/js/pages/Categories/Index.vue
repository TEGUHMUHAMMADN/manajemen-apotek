<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import {
    Plus,
    Trash2,
    Tags,
    Package,
    Search,
} from '@lucide/vue';

defineOptions({
    layout: AppLayout,
});

interface Category {
    id: number;
    name: string;
    medicines_count?: number;
}

const props = defineProps<{
    categories: Category[];
}>();

const form = useForm({
    name: '',
});

const search = ref('');

const filteredCategories = computed(() => {
    return props.categories.filter((category) =>
        category.name.toLowerCase().includes(search.value.toLowerCase())
    );
});

const submit = () => {
    form.post('/categories', {
        onSuccess: () => form.reset(),
    });
};

const deleteCategory = (id: number) => {
    if (confirm('Yakin ingin menghapus kategori ini?')) {
        const deleteForm = useForm({});
        deleteForm.delete(`/categories/${id}`);
    }
};
</script>

<template>
    <Head title="Kategori Obat" />

    <div class="w-full space-y-6">

        <!-- Judul Halaman -->
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm font-medium text-emerald-600">
                <Tags class="h-4 w-4" />
                Manajemen Persediaan
            </div>

            <h1 class="text-2xl font-bold text-slate-800 sm:text-3xl">
                Manajemen Kategori Obat
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Kelola dan atur kategori obat apotek dengan mudah.
            </p>
        </div>

        <!-- Form Tambah Kategori -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <Plus class="h-5 w-5" />
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">
                        Tambah Kategori Baru
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Masukkan nama kategori obat.
                    </p>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="flex flex-col items-end gap-4 sm:flex-row"
            >
                <div class="w-full flex-1">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Kategori
                    </label>

                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Contoh: Tablet, Sirup, Antibiotik"
                        class="block w-full rounded-xl border border-slate-300
                        bg-white px-4 py-3 text-sm font-medium
                        text-slate-900 caret-emerald-600
                        placeholder:text-slate-400
                        shadow-sm outline-none
                        transition-all duration-200
                        focus:border-emerald-500
                        focus:ring-4 focus:ring-emerald-100"
                    />

                    <p
                        v-if="form.errors.name"
                        class="mt-2 text-sm text-red-500"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing || !form.name.trim()"
                    class="flex w-full items-center justify-center gap-2
                    rounded-xl bg-emerald-600 px-6 py-3
                    text-sm font-semibold text-white
                    shadow-sm transition-all duration-200
                    hover:bg-emerald-700 active:scale-95
                    disabled:cursor-not-allowed disabled:opacity-50
                    sm:w-auto"
                >
                    <Plus class="h-4 w-4" />
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Kategori' }}
                </button>
            </form>
        </div>

        <!-- Daftar Kategori -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <!-- Header Tabel -->
            <div class="flex flex-col justify-between gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center">

                <div>
                    <h2 class="font-bold text-slate-800">
                        Daftar Kategori
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Data kategori obat yang tersedia.
                    </p>
                </div>

                <div class="relative w-full sm:w-64">
                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari kategori..."
                        class="w-full rounded-xl border border-slate-200
                        bg-white py-2.5 pl-10 pr-4 text-sm
                        text-slate-900 placeholder:text-slate-400
                        outline-none transition
                        focus:border-emerald-500
                        focus:ring-4 focus:ring-emerald-100"
                    />
                </div>
            </div>

            <!-- Isi Tabel -->
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                No
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Nama Kategori
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Jumlah Obat
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        <tr
                            v-for="(cat, index) in filteredCategories"
                            :key="cat.id"
                            class="transition-colors hover:bg-emerald-50/40"
                        >
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                {{ index + 1 }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                        <Tags class="h-4 w-4" />
                                    </div>

                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ cat.name }}
                                    </span>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                    <Package class="h-3.5 w-3.5" />
                                    {{ cat.medicines_count || 0 }} item
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <button
                                    type="button"
                                    @click="deleteCategory(cat.id)"
                                    class="inline-flex items-center gap-2 rounded-lg
                                    bg-red-50 px-3 py-2 text-xs font-semibold
                                    text-red-600 transition
                                    hover:bg-red-100 active:scale-95"
                                >
                                    <Trash2 class="h-4 w-4" />
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        <tr v-if="filteredCategories.length === 0">
                            <td colspan="4" class="px-6 py-14 text-center">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                    <Tags class="h-7 w-7" />
                                </div>

                                <h3 class="mt-4 text-sm font-bold text-slate-700">
                                    {{ search ? 'Kategori tidak ditemukan' : 'Belum ada kategori obat' }}
                                </h3>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ search ? 'Coba gunakan kata kunci lain.' : 'Tambahkan kategori melalui formulir di atas.' }}
                                </p>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-4">
                <p class="text-xs text-slate-500">
                    Menampilkan
                    <span class="font-bold text-emerald-600">
                        {{ filteredCategories.length }}
                    </span>
                    dari
                    <span class="font-bold text-slate-700">
                        {{ categories.length }}
                    </span>
                    kategori
                </p>
            </div>

        </div>
    </div>
</template>
