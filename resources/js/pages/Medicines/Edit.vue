<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

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
</script>

<template>
    <Head title="Edit Data Obat" />

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Edit Data Obat</h1>
        <p class="text-sm text-gray-500 mb-6">Perbarui informasi master data obat.</p>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-sidebar-border/70">
            <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barcode / QR Code</label>
                    <input type="text" v-model="form.code" class="w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Obat</label>
                    <input type="text" v-model="form.name" class="w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select v-model="form.category_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">Pilih Kategori</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                    <input type="number" v-model="form.price" class="w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimal Stok (Alert)</label>
                    <input type="number" v-model="form.minimum_stock" class="w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Rak</label>
                    <input type="text" v-model="form.rack_location" class="w-full border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div class="md:col-span-2 flex justify-end mt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition font-medium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</template>