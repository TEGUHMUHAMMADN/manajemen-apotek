
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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
    name: string;
    rack_location: string;
    price: number;
    batches: Batch[];
}

interface CartItem {
    medicine_id: number;
    batch_id: number;
    name: string;
    batch_number: string;
    price: number;
    quantity: number;
    max_stock: number;
}

const props = defineProps<{
    medicines: Medicine[];
}>();

const cart = ref<CartItem[]>([]);
const cashAmount = ref<number>(0);
const search = ref('');

const formatRupiah = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(value);
};

// Filter obat berdasarkan nama
const filteredMedicines = computed(() => {
    return props.medicines.filter((medicine) =>
        medicine.name.toLowerCase().includes(search.value.toLowerCase())
    );
});

// Mengambil batch FEFO
const getActiveBatch = (medicine: Medicine) => {
    return medicine.batches?.[0] ?? null;
};

// Tambahkan obat ke keranjang
const addToCart = (medicine: Medicine) => {
    const activeBatch = getActiveBatch(medicine);

    if (!activeBatch || activeBatch.stock <= 0) {
        alert('Stok obat habis atau tidak tersedia!');
        return;
    }

    const existing = cart.value.find(
        (item) =>
            item.medicine_id === medicine.id &&
            item.batch_id === activeBatch.id
    );

    if (existing) {
        if (existing.quantity < activeBatch.stock) {
            existing.quantity++;
        } else {
            alert('Jumlah melebihi stok yang tersedia!');
        }
        return;
    }

    cart.value.push({
        medicine_id: medicine.id,
        batch_id: activeBatch.id,
        name: medicine.name,
        batch_number: activeBatch.batch_number,
        price: Number(medicine.price),
        quantity: 1,
        max_stock: activeBatch.stock,
    });
};

// Ubah jumlah barang
const updateQuantity = (item: CartItem, value: number | string) => {
    const quantity = Number(value);

    item.quantity = Math.max(
        1,
        Math.min(quantity || 1, item.max_stock)
    );
};

// Hapus barang dari keranjang
const removeFromCart = (index: number) => {
    cart.value.splice(index, 1);
};

// Kosongkan keranjang
const clearCart = () => {
    if (confirm('Kosongkan semua barang di keranjang?')) {
        cart.value = [];
        cashAmount.value = 0;
    }
};

// Hitung total belanja
const totalAmount = computed(() => {
    return cart.value.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );
});

// Hitung kembalian
const changeAmount = computed(() => {
    return Math.max(0, cashAmount.value - totalAmount.value);
});

// Jumlah item
const totalItems = computed(() => {
    return cart.value.reduce(
        (sum, item) => sum + item.quantity,
        0
    );
});

// Form transaksi
const form = useForm({
    items: [] as { medicine_id: number; quantity: number }[],
    cash_amount: 0,
});

// Proses pembayaran
const checkout = () => {
    if (cart.value.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    if (cashAmount.value < totalAmount.value) {
        alert('Uang tunai kurang dari total belanja!');
        return;
    }

    form.items = cart.value.map((item) => ({
        medicine_id: item.medicine_id,
        quantity: item.quantity,
    }));

    form.cash_amount = cashAmount.value;

    form.post('/transactions', {
        onSuccess: () => {
            alert('Transaksi berhasil!');
            cart.value = [];
            cashAmount.value = 0;
            search.value = '';
        },
    });
};
</script>

<template>
    <Head title="Kasir POS - MediStock" />

    <div class="min-h-screen w-full bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <div class="mb-2 flex items-center gap-2">
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        MEDISTOCK POS
                    </span>
                    <span class="text-xs text-slate-400">
                        Sistem Kasir Apotek
                    </span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                    Kasir & Transaksi
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Pilih obat, masukkan jumlah, dan proses pembayaran pelanggan.
                </p>
            </div>

            <div class="flex items-center gap-2 rounded-xl border border-emerald-100 bg-white px-4 py-3 shadow-sm">
                <div class="flex size-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                    <span class="text-lg font-bold">+</span>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Status Sistem</p>
                    <p class="text-sm font-semibold text-emerald-600">
                        Sistem Aktif
                    </p>
                </div>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Jenis Obat
                </p>
                <p class="mt-2 text-2xl font-bold text-slate-800">
                    {{ medicines.length }}
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    Obat tersedia di inventaris
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm font-medium text-emerald-700">
                    Total Item Keranjang
                </p>
                <p class="mt-2 text-2xl font-bold text-emerald-800">
                    {{ totalItems }}
                </p>
                <p class="mt-1 text-xs text-emerald-600">
                    Jumlah seluruh obat yang dipilih
                </p>
            </div>
        </div>

        <!-- Konten Kasir -->
        <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-3">

            <!-- Daftar Obat -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-2">

                <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Pilih Obat
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Rekomendasi batch otomatis berdasarkan FEFO.
                        </p>
                    </div>

                    <span class="w-fit rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700">
                        {{ filteredMedicines.length }} Obat
                    </span>
                </div>

                <!-- Pencarian -->
                <div class="relative mb-5">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        ⌕
                    </span>

                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama obat..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-800 placeholder:text-slate-400 shadow-sm outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-100"
                    />
                </div>

                <!-- Daftar Kartu Obat -->
                <div
                    v-if="filteredMedicines.length > 0"
                    class="grid max-h-[650px] grid-cols-1 gap-4 overflow-y-auto pr-1 sm:grid-cols-2"
                >
                    <button
                        v-for="med in filteredMedicines"
                        :key="med.id"
                        type="button"
                        @click="addToCart(med)"
                        class="group rounded-xl border border-slate-200 bg-white p-4 text-left transition duration-200 hover:-translate-y-1 hover:border-emerald-400 hover:bg-emerald-50/40 hover:shadow-md"
                    >
                        <div class="mb-3 flex items-start justify-between gap-2">
                            <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 transition group-hover:bg-emerald-600 group-hover:text-white">
                                <span class="text-xl font-bold">+</span>
                            </div>

                            <span
                                v-if="getActiveBatch(med) && getActiveBatch(med)!.stock > 0"
                                class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                            >
                                Tersedia
                            </span>

                            <span
                                v-else
                                class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-600"
                            >
                                Stok Habis
                            </span>
                        </div>

                        <h3 class="font-bold text-slate-800">
                            {{ med.name }}
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Lokasi Rak: {{ med.rack_location || '-' }}
                        </p>

                        <p class="mt-3 text-lg font-bold text-emerald-700">
                            {{ formatRupiah(Number(med.price)) }}
                        </p>

                        <div
                            v-if="getActiveBatch(med)"
                            class="mt-3 rounded-lg bg-orange-50 p-3"
                        >
                            <p class="text-xs font-semibold text-orange-700">
                                Batch FEFO
                            </p>
                            <p class="mt-1 break-words text-xs text-orange-600">
                                {{ getActiveBatch(med)!.batch_number }}
                            </p>
                            <div class="mt-2 flex justify-between gap-2 text-xs text-orange-700">
                                <span>
                                    Exp:
                                    {{ getActiveBatch(med)!.expired_date }}
                                </span>
                                <span class="font-semibold">
                                    Stok: {{ getActiveBatch(med)!.stock }}
                                </span>
                            </div>
                        </div>

                        <div
                            v-else
                            class="mt-3 rounded-lg bg-red-50 p-3 text-xs text-red-600"
                        >
                            Tidak ada batch tersedia.
                        </div>

                        <div class="mt-4 border-t border-slate-100 pt-3">
                            <p class="text-xs font-semibold text-emerald-600 group-hover:text-emerald-700">
                                + Tambahkan ke Keranjang
                            </p>
                        </div>
                    </button>
                </div>

                <!-- Kosong -->
                <div
                    v-else
                    class="rounded-xl border border-dashed border-slate-300 py-16 text-center"
                >
                    <div class="mb-3 text-4xl text-slate-300">
                        ⌕
                    </div>
                    <p class="font-semibold text-slate-700">
                        Obat tidak ditemukan
                    </p>
                    <p class="mt-1 text-sm text-slate-400">
                        Coba gunakan kata pencarian lain.
                    </p>
                </div>
            </div>

            <!-- Keranjang -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <!-- Header Keranjang -->
                <div class="border-b border-slate-100 bg-emerald-50/70 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">
                                Keranjang Belanja
                            </h2>
                            <p class="mt-1 text-xs text-slate-500">
                                Detail pesanan pelanggan
                            </p>
                        </div>

                        <div class="flex size-11 items-center justify-center rounded-xl bg-emerald-600 text-white">
                            <span class="text-lg font-bold">🛒</span>
                        </div>
                    </div>
                </div>

                <!-- Isi Keranjang -->
                <div class="max-h-[400px] min-h-[180px] overflow-y-auto p-5">

                    <div
                        v-for="(item, index) in cart"
                        :key="`${item.medicine_id}-${item.batch_id}`"
                        class="border-b border-slate-100 py-4 first:pt-0"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-sm font-bold text-slate-800">
                                    {{ item.name }}
                                </h3>

                                <p class="mt-1 text-xs text-slate-500">
                                    Batch: {{ item.batch_number }}
                                </p>

                                <p class="mt-1 text-sm font-semibold text-emerald-700">
                                    {{ formatRupiah(item.price) }}
                                </p>
                            </div>

                            <button
                                type="button"
                                @click="removeFromCart(index)"
                                class="rounded-lg px-2 py-1 text-sm font-semibold text-red-500 transition hover:bg-red-50 hover:text-red-700"
                                title="Hapus item"
                            >
                                Hapus
                            </button>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <label class="text-xs text-slate-500">
                                    Jumlah
                                </label>

                                <input
                                    type="number"
                                    :value="item.quantity"
                                    @change="updateQuantity(item, ($event.target as HTMLInputElement).value)"
                                    min="1"
                                    :max="item.max_stock"
                                    class="w-20 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-center text-sm font-semibold text-slate-800 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                />
                            </div>

                            <p class="text-sm font-bold text-slate-800">
                                {{ formatRupiah(item.price * item.quantity) }}
                            </p>
                        </div>
                    </div>

                    <!-- Keranjang Kosong -->
                    <div
                        v-if="cart.length === 0"
                        class="flex flex-col items-center justify-center py-10 text-center"
                    >
                        <div class="mb-3 flex size-16 items-center justify-center rounded-full bg-slate-100 text-2xl">
                            🛒
                        </div>

                        <p class="font-semibold text-slate-700">
                            Keranjang masih kosong
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Pilih obat untuk memulai transaksi.
                        </p>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div class="border-t border-slate-200 bg-slate-50 p-5">

                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-500">
                            Total Item
                        </span>
                        <span class="text-sm font-bold text-slate-800">
                            {{ totalItems }} item
                        </span>
                    </div>

                    <div class="mb-5 flex items-center justify-between">
                        <span class="font-semibold text-slate-700">
                            Total Pembayaran
                        </span>
                        <span class="text-xl font-bold text-emerald-700">
                            {{ formatRupiah(totalAmount) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Uang Tunai Pelanggan
                        </label>

                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">
                                Rp
                            </span>

                            <input
                                v-model.number="cashAmount"
                                type="number"
                                min="0"
                                placeholder="Masukkan nominal uang..."
                                class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm font-semibold text-slate-800 placeholder:font-normal placeholder:text-slate-400 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                            />
                        </div>
                    </div>

                    <!-- Kembalian -->
                    <div class="mb-5 flex items-center justify-between rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <span class="text-sm font-semibold text-emerald-700">
                            Kembalian
                        </span>

                        <span class="text-lg font-bold text-emerald-700">
                            {{ formatRupiah(changeAmount) }}
                        </span>
                    </div>

                    <!-- Error -->
                    <div
                        v-if="form.errors.items || form.errors.cash_amount"
                        class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600"
                    >
                        {{ form.errors.items || form.errors.cash_amount }}
                    </div>

                    <!-- Tombol Bayar -->
                    <button
                        type="button"
                        @click="checkout"
                        :disabled="cart.length === 0 || cashAmount < totalAmount || form.processing"
                        class="w-full rounded-xl bg-emerald-600 px-4 py-3.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none"
                    >
                        {{ form.processing ? 'Memproses Transaksi...' : 'Proses Pembayaran Tunai' }}
                    </button>

                    <button
                        v-if="cart.length > 0"
                        type="button"
                        @click="clearCart"
                        class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                    >
                        Kosongkan Keranjang
                    </button>

                    <p class="mt-4 text-center text-xs text-slate-400">
                        MediStock Pharmacy Management System
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
