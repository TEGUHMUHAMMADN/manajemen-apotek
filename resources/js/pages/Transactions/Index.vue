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

const addToCart = (medicine: Medicine) => {
    const activeBatch = medicine.batches && medicine.batches.length > 0 ? medicine.batches[0] : null;
    if (!activeBatch || activeBatch.stock <= 0) {
        alert('Stok obat habis atau sudah kadaluarsa!');
        return;
    }

    const existing = cart.value.find(item => item.medicine_id === medicine.id && item.batch_id === activeBatch.id);
    if (existing) {
        if (existing.quantity < activeBatch.stock) {
            existing.quantity++;
        } else {
            alert('Jumlah melebihi stok batch FEFO yang tersedia!');
        }
    } else {
        cart.value.push({
            medicine_id: medicine.id,
            batch_id: activeBatch.id,
            name: medicine.name,
            batch_number: activeBatch.batch_number,
            price: medicine.price,
            quantity: 1,
            max_stock: activeBatch.stock,
        });
    }
};

const removeFromCart = (index: number) => {
    cart.value.splice(index, 1);
};

const totalAmount = computed(() => {
    return cart.value.reduce((sum, item) => sum + (item.price * item.quantity), 0);
});

const changeAmount = computed(() => {
    return cashAmount.value - totalAmount.value;
});

const form = useForm({
    items: [] as { medicine_id: number; quantity: number }[],
    cash_amount: 0,
});

const checkout = () => {
    if (cashAmount.value < totalAmount.value) {
        alert('Uang tunai kurang dari total belanja!');
        return;
    }

    form.items = cart.value.map(item => ({
        medicine_id: item.medicine_id,
        quantity: item.quantity,
    }));
    form.cash_amount = cashAmount.value;

    form.post('/transactions', {
        onSuccess: () => {
            alert('Transaksi berhasil!');
            cart.value = [];
            cashAmount.value = 0;
        },
    });
};
</script>

<template>
    <Head title="Kasir POS" />

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Daftar Obat untuk Dipilih -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-sidebar-border/70">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Pilih Obat (Otomatis Rekomendasi FEFO)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto pr-2">
                <div v-for="med in medicines" :key="med.id" @click="addToCart(med)" class="border border-gray-200 p-4 rounded-xl cursor-pointer hover:border-indigo-500 transition bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">{{ med.name }}</h3>
                    <p class="text-sm text-gray-500">Rak: {{ med.rack_location }}</p>
                    <p class="text-sm font-semibold text-indigo-600 mt-2">Rp {{ Number(med.price).toLocaleString() }}</p>
                    <p class="text-xs text-orange-600 mt-1" v-if="med.batches && med.batches.length > 0">
                        Batch FEFO: {{ med.batches[0].batch_number }} (Exp: {{ med.batches[0].expired_date }}) | Stok: {{ med.batches[0].stock }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Keranjang Belanja & Checkout Kasir -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-sidebar-border/70 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold mb-4 text-gray-800">Keranjang Belanja</h2>
                <div class="divide-y max-h-[300px] overflow-y-auto mb-4">
                    <div v-for="(item, index) in cart" :key="index" class="py-3 flex justify-between items-center text-sm">
                        <div>
                            <p class="font-bold text-gray-800">{{ item.name }}</p>
                            <p class="text-xs text-gray-500">Batch: {{ item.batch_number }} | Rp {{ Number(item.price).toLocaleString() }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" v-model.number="item.quantity" :max="item.max_stock" min="1" class="w-16 text-center border-gray-300 rounded-md shadow-sm text-sm" />
                            <button @click="removeFromCart(index)" class="text-red-500 font-bold px-1 hover:text-red-700">&times;</button>
                        </div>
                    </div>
                    <div v-if="cart.length === 0" class="py-6 text-center text-sm text-gray-500">
                        Keranjang masih kosong.
                    </div>
                </div>
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between text-lg font-bold mb-2 text-gray-800">
                    <span>Total:</span>
                    <span>Rp {{ totalAmount.toLocaleString() }}</span>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uang Tunai (Cash)</label>
                    <input type="number" v-model.number="cashAmount" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan nominal uang..." />
                </div>
                <div class="flex justify-between text-sm font-semibold mb-4 text-green-600">
                    <span>Kembalian:</span>
                    <span>Rp {{ changeAmount >= 0 ? changeAmount.toLocaleString() : 0 }}</span>
                </div>
                <button @click="checkout" :disabled="cart.length === 0" class="w-full bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 transition disabled:opacity-50 font-medium">
                    Proses Pembayaran Tunai
                </button>
            </div>
        </div>

    </div>
</template>