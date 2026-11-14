<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: AppLayout,
});

interface Category {
    id: number;
    name: string;
    medicines_count?: number;
}

defineProps<{
    categories: Category[];
}>();

const form = useForm({
    name: '',
});

const submit = () => {
    form.post('/categories', {
        onSuccess: () => form.reset(),
    });
};

const deleteCategory = (id: number) => {
    if (confirm('Yakin ingin menghapus kategori ini?')) {
        useForm().delete(`/categories/${id}`);
    }
};
</script>

<template>
    <Head title="Kategori Obat" />

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Kategori Obat</h1>

        <!-- Form Tambah Kategori -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-sidebar-border/70 mb-6">
            <form @submit.prevent="submit" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input 
                        type="text" 
                        v-model="form.name" 
                        placeholder="Contoh: Tablet, Sirup, Antibiotik" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                </div>
                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition disabled:opacity-50"
                >
                    Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Kategori -->
        <div class="bg-white shadow-sm border border-sidebar-border/70 rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Obat</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(cat, index) in categories" :key="cat.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ cat.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ cat.medicines_count || 0 }} item</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="deleteCategory(cat.id)" class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                    <tr v-if="categories.length === 0">
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada kategori obat.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>