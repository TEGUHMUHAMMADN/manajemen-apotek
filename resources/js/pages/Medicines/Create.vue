<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Html5QrcodeScanner } from 'html5-qrcode';
import { onMounted, onUnmounted, ref } from 'vue';

defineOptions({
    layout: AppLayout,
});

interface Category {
    id: number;
    name: string;
}

defineProps<{
    categories: Category[];
}>();

const form = useForm({
    code: '',
    name: '',
    category_id: '',
    price: '',
    minimum_stock: 10,
    rack_location: '',
    batch_number: '',
    quantity: 1,
    expired_date: '',
});

let html5QrcodeScanner: Html5QrcodeScanner | null = null;
const isScanning = ref(false);

// Fungsi cek kode ke database (Auto-Learn)
const searchByCode = async (scannedCode: string) => {
    if (!scannedCode) return;
    form.code = scannedCode;

    try {
        const res = await fetch('/medicines/find-by-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
            },
            body: JSON.stringify({ code: scannedCode }),
        });
        const response = await res.json();
        
        if (response.found) {
            form.name = response.data.name;
            form.category_id = response.data.category_id;
            form.price = response.data.price;
            form.rack_location = response.data.rack_location;
            form.minimum_stock = response.data.minimum_stock;
            alert('Obat ditemukan! Silakan masukkan nomor batch, jumlah, dan tanggal kadaluarsa.');
        } else {
            alert('Kode obat baru / belum terdaftar. Silakan lengkapi data manual.');
        }
    } catch (error) {
        console.error('Gagal memeriksa kode obat');
    }
};

// Inisialisasi Kamera Scanner
const startScanner = () => {
    isScanning.value = true;
    setTimeout(() => {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: { width: 250, height: 150 } },
            /* verbose= */ false
        );

        html5QrcodeScanner.render(
            (decodedText) => {
                // Berhasil scan barcode
                searchByCode(decodedText);
                stopScanner(); // Matikan kamera otomatis setelah berhasil scan
            },
            (errorMessage) => {
                // Error scanning (biasanya diabaikan karena berjalan terus tiap frame)
            }
        );
    }, 100);
};

const stopScanner = () => {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear().catch(error => {
            console.error("Gagal menghentikan scanner.", error);
        });
    }
    isScanning.value = false;
};

onUnmounted(() => {
    stopScanner();
});

const submit = () => {
    form.post('/medicines', {
        onSuccess: () => {
            form.reset();
            alert('Stok obat berhasil ditambahkan!');
        },
    });
};
</script>

<template>
    <Head title="Input / Scan Kamera Obat Masuk" />

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Input / Scan Kamera Obat Masuk</h1>
        <p class="text-sm text-gray-500 mb-6">Gunakan kamera HP/Laptop untuk scan barcode atau isi form secara manual.</p>

        <!-- Bagian Kontrol Kamera Scanner -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-sidebar-border/70 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Scanner Kamera Barcode</h2>
                <button 
                    type="button" 
                    @click="isScanning ? stopScanner() : startScanner()"
                    :class="isScanning ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                    class="text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                >
                    {{ isScanning ? 'Tutup Kamera' : 'Buka Kamera Scanner' }}
                </button>
            </div>

            <!-- Elemen Tampilan Kamera -->
            <div v-show="isScanning" class="w-full max-w-md mx-auto">
                <div id="reader"></div>
            </div>
        </div>

        <!-- Formulir Input Data Obat / Restock -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-sidebar-border/70">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Formulir Detail Barang Masuk</h2>
            <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barcode / QR Code</label>
                    <input 
                        type="text" 
                        v-model="form.code" 
                        @blur="searchByCode(form.code)" 
                        placeholder="Scan atau ketik kode..." 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                        required 
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Obat</label>
                    <input type="text" v-model="form.name" placeholder="Nama obat" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select v-model="form.category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Pilih Kategori</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                    <input type="number" v-model="form.price" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimal Stok (Alert)</label>
                    <input type="number" v-model="form.minimum_stock" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Rak</label>
                    <input type="text" v-model="form.rack_location" placeholder="Contoh: Rak A-1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Batch</label>
                    <input type="text" v-model="form.batch_number" placeholder="No Batch" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Masuk (Qty)</label>
                    <input type="number" v-model="form.quantity" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required min="1" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                    <input type="date" v-model="form.expired_date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                </div>
                <div class="md:col-span-3 flex justify-end mt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition font-medium">Simpan / Masukkan Stok Obat</button>
                </div>
            </form>
        </div>
    </div>
</template>