<script setup lang="ts">
import { Form, Head, Link } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import PasswordInput from "@/components/PasswordInput.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { login } from "@/routes";
import { store } from "@/routes/register";

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: "Buat Akun MediStock",
        description: "Daftarkan akun untuk mulai mengelola apotek Anda.",
    },
});
</script>

<template>
    <Head title="Register - MediStock" />

    <div class="min-h-screen bg-[#f4faf6] text-slate-800">
        <!-- Navbar -->
        <nav
            class="flex items-center justify-between border-b border-emerald-100 bg-white px-6 py-4 md:px-12"
        >
            <Link :href="login().url" class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-2xl font-bold text-white shadow-lg shadow-emerald-200"
                >
                    +
                </div>

                <div>
                    <h1
                        class="text-xl font-extrabold tracking-tight text-slate-800"
                    >
                        Medi<span class="text-emerald-600">Stock</span>
                    </h1>

                    <p
                        class="text-[10px] font-semibold tracking-[2px] text-slate-400"
                    >
                        PHARMACY MANAGEMENT
                    </p>
                </div>
            </Link>

            <div class="flex items-center gap-3">
                <span
                    class="hidden text-sm font-medium text-slate-500 sm:block"
                >
                    Sudah punya akun?
                </span>

                <Button
                    as-child
                    class="rounded-xl bg-emerald-600 px-6 text-white shadow-md shadow-emerald-100 hover:bg-emerald-700"
                >
                    <Link :href="login().url"> Login </Link>
                </Button>
            </div>
        </nav>

        <!-- Main Content -->
        <main
            class="mx-auto grid min-h-[calc(100vh-76px)] max-w-7xl items-center gap-12 px-6 py-10 lg:grid-cols-2 lg:px-12"
        >
            <!-- Left Information -->
            <section class="hidden lg:block">
                <div
                    class="mb-6 inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700"
                >
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Pharmacy Management System
                </div>

                <h2
                    class="max-w-xl text-5xl font-extrabold leading-tight tracking-tight text-slate-800"
                >
                    Mulai Kelola Apotek dengan
                    <span class="text-emerald-600">Lebih Mudah.</span>
                </h2>

                <p class="mt-6 max-w-lg text-lg leading-8 text-slate-500">
                    Buat akun MediStock untuk membantu pengelolaan data obat,
                    memantau ketersediaan stok, dan mencatat transaksi apotek
                    secara lebih praktis.
                </p>

                <!-- Features -->
                <div class="mt-10 grid max-w-lg grid-cols-2 gap-4">
                    <div
                        class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm"
                    >
                        <div
                            class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-xl text-emerald-700"
                        >
                            +
                        </div>

                        <h3 class="font-bold text-slate-800">Manajemen Obat</h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Kelola informasi obat dengan praktis.
                        </p>
                    </div>

                    <div
                        class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm"
                    >
                        <div
                            class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-xl text-emerald-700"
                        >
                            ✓
                        </div>

                        <h3 class="font-bold text-slate-800">
                            Monitoring Stok
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Pantau persediaan obat.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Register Card -->
            <section class="mx-auto w-full max-w-md">
                <div
                    class="rounded-3xl border border-emerald-100 bg-white p-7 shadow-xl shadow-emerald-900/5 sm:p-9"
                >
                    <!-- Card Header -->
                    <div class="mb-7">
                        <div
                            class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-600 text-3xl font-bold text-white shadow-lg shadow-emerald-200"
                        >
                            +
                        </div>

                        <h2
                            class="text-3xl font-extrabold tracking-tight text-slate-800"
                        >
                            Buat Akun!
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Lengkapi informasi berikut untuk membuat akun
                            MediStock Anda.
                        </p>
                    </div>

                    <!-- Register Form -->
                    <Form
                        v-bind="store.form()"
                        :reset-on-success="[
                            'password',
                            'password_confirmation',
                        ]"
                        v-slot="{ errors, processing }"
                        class="flex flex-col gap-5"
                    >
                        <!-- Name -->
                        <div class="grid gap-2">
                            <Label
                                for="name"
                                class="font-semibold text-slate-700"
                            >
                                Nama Lengkap
                            </Label>

                            <Input
                                id="name"
                                type="text"
                                name="name"
                                required
                                v-focus
                                :tabindex="1"
                                autocomplete="name"
                                placeholder="Masukkan nama lengkap"
                                class="h-12 rounded-xl border-slate-200 bg-white text-slate-800 placeholder:text-slate-400 focus-visible:border-emerald-500 focus-visible:ring-emerald-500"
                            />

                            <InputError :message="errors.name" />
                        </div>

                        <!-- Email -->
                        <div class="grid gap-2">
                            <Label
                                for="email"
                                class="font-semibold text-slate-700"
                            >
                                Alamat Email
                            </Label>

                            <Input
                                id="email"
                                type="email"
                                name="email"
                                required
                                :tabindex="2"
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="h-12 rounded-xl border-slate-200 bg-white text-slate-800 placeholder:text-slate-400 focus-visible:border-emerald-500 focus-visible:ring-emerald-500"
                            />

                            <InputError :message="errors.email" />
                        </div>

                        <!-- Password -->
                        <div class="grid gap-2">
                            <Label
                                for="password"
                                class="font-semibold text-slate-700"
                            >
                                Password
                            </Label>

                            <PasswordInput
                                id="password"
                                name="password"
                                required
                                :tabindex="3"
                                autocomplete="new-password"
                                placeholder="Buat password"
                                :passwordrules="passwordRules"
                            />

                            <InputError :message="errors.password" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="grid gap-2">
                            <Label
                                for="password_confirmation"
                                class="font-semibold text-slate-700"
                            >
                                Konfirmasi Password
                            </Label>

                            <PasswordInput
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                :tabindex="4"
                                autocomplete="new-password"
                                placeholder="Masukkan ulang password"
                                :passwordrules="passwordRules"
                            />

                            <InputError
                                :message="errors.password_confirmation"
                            />
                        </div>

                        <!-- Submit Button -->
                        <Button
                            type="submit"
                            class="mt-2 h-12 w-full rounded-xl bg-emerald-600 text-base font-bold text-white shadow-lg shadow-emerald-100 transition-all hover:bg-emerald-700"
                            :tabindex="5"
                            :disabled="processing"
                            data-test="register-user-button"
                        >
                            <Spinner v-if="processing" />
                            {{
                                processing
                                    ? "Membuat Akun..."
                                    : "Daftar Sekarang"
                            }}
                        </Button>
                    </Form>

                    <!-- Login Link -->
                    <div
                        class="mt-7 border-t border-slate-100 pt-6 text-center"
                    >
                        <p class="text-sm text-slate-500">
                            Sudah memiliki akun?

                            <Link
                                :href="login().url"
                                class="font-bold text-emerald-600 hover:text-emerald-700"
                            >
                                Masuk Sekarang
                            </Link>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <p class="mt-6 text-center text-xs text-slate-400">
                    © 2026 MediStock. Pharmacy Management System.
                </p>
            </section>
        </main>
    </div>
</template>
