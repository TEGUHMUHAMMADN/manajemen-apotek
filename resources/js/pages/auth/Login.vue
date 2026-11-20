
<script setup lang="ts">
import { Form, Head, Link } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import PasswordInput from "@/components/PasswordInput.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Spinner } from "@/components/ui/spinner";
import { home, register } from "@/routes";
import { store } from "@/routes/login";
import { request } from "@/routes/password";
import PasskeyVerify from "@/components/PasskeyVerify.vue";

defineOptions({
    layout: {
        title: "Selamat Datang Kembali",
        description: "Masuk ke akun MediStock untuk mengelola apotek Anda.",
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Login - MediStock" />

    <div class="min-h-screen bg-[#f5faf7] text-slate-800">

        <!-- Navbar -->
        <nav class="sticky top-0 z-30 flex items-center justify-between border-b border-emerald-100 bg-white/95 px-5 py-4 shadow-sm backdrop-blur-md md:px-12">

            <Link :href="home().url" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-2xl font-bold text-white shadow-md shadow-emerald-200">
                    +
                </div>

                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-800">
                        Medi<span class="text-emerald-600">Stock</span>
                    </h1>
                    <p class="text-[10px] font-semibold tracking-[2px] text-slate-400">
                        PHARMACY MANAGEMENT
                    </p>
                </div>
            </Link>

            <div class="flex items-center gap-3 sm:gap-5">
                <Link
                    :href="home().url"
                    class="hidden items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-emerald-600 sm:flex"
                >
                    ← Kembali ke Beranda
                </Link>

                <span class="hidden text-sm text-slate-500 md:block">
                    Belum punya akun?
                </span>

                <Button
                    as-child
                    class="rounded-xl bg-emerald-600 px-6 font-semibold text-white shadow-sm hover:bg-emerald-700"
                >
                    <Link :href="register().url">
                        Register
                    </Link>
                </Button>
            </div>
        </nav>

        <!-- Main -->
        <main class="mx-auto grid min-h-[calc(100vh-76px)] max-w-7xl items-center gap-12 px-5 py-10 md:px-10 lg:grid-cols-2 lg:px-12">

            <!-- Left Information -->
            <section class="hidden lg:block">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Pharmacy Management System
                </div>

                <h2 class="max-w-xl text-5xl font-extrabold leading-tight tracking-tight text-slate-800">
                    Kelola Stok Obat dengan
                    <span class="text-emerald-600">Lebih Mudah.</span>
                </h2>

                <p class="mt-6 max-w-lg text-lg leading-8 text-slate-500">
                    Selamat datang di MediStock. Sistem pengelolaan apotek untuk membantu pencatatan obat, pemantauan stok, dan pengelolaan transaksi secara lebih praktis.
                </p>

                <!-- Features -->
                <div class="mt-10 grid max-w-lg grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl font-bold text-emerald-600">
                            +
                        </div>
                        <h3 class="font-bold text-slate-800">
                            Manajemen Obat
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Kelola data obat dengan praktis.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl font-bold text-emerald-600">
                            ✓
                        </div>
                        <h3 class="font-bold text-slate-800">
                            Monitoring Stok
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Pantau ketersediaan obat.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Login Card -->
            <section class="mx-auto w-full max-w-md">
                <div class="rounded-3xl border border-emerald-100 bg-white p-7 shadow-xl shadow-emerald-900/5 sm:p-10">

                    <!-- Card Header -->
                    <div class="mb-8">
                        <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-3xl font-bold text-white shadow-lg shadow-emerald-200">
                            +
                        </div>

                        <h2 class="text-3xl font-extrabold tracking-tight text-slate-800">
                            Selamat Datang!
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Silakan masuk menggunakan email dan password akun MediStock Anda.
                        </p>
                    </div>

                    <!-- Status -->
                    <div
                        v-if="status"
                        class="mb-5 rounded-xl border border-emerald-100 bg-emerald-50 p-3 text-sm font-medium text-emerald-700"
                    >
                        {{ status }}
                    </div>

                    <!-- Passkey -->
                    <div class="mb-6">
                        <PasskeyVerify />
                    </div>

                    <!-- Login Form -->
                    <Form
                        v-bind="store.form()"
                        :reset-on-success="['password']"
                        v-slot="{ errors, processing }"
                        class="flex flex-col gap-5"
                    >
                        <!-- Email -->
                        <div class="grid gap-2">
                            <Label for="email" class="font-semibold text-slate-700">
                                Email Address
                            </Label>

                            <Input
                                id="email"
                                type="email"
                                name="email"
                                required
                                v-focus
                                :tabindex="1"
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="h-12 rounded-xl border border-slate-200 bg-white px-4 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus-visible:border-emerald-500 focus-visible:ring-2 focus-visible:ring-emerald-100"
                            />

                            <InputError :message="errors.email" />
                        </div>

                        <!-- Password -->
                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label for="password" class="font-semibold text-slate-700">
                                    Password
                                </Label>

                                <TextLink
                                    v-if="canResetPassword"
                                    :href="request()"
                                    class="text-sm font-semibold !text-emerald-600 hover:!text-emerald-700"
                                    :tabindex="5"
                                >
                                    Lupa Password?
                                </TextLink>
                            </div>

                            <PasswordInput
                                id="password"
                                name="password"
                                required
                                :tabindex="2"
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                            />

                            <InputError :message="errors.password" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center gap-3">
                            <Checkbox
                                id="remember"
                                name="remember"
                                :tabindex="3"
                                class="data-[state=checked]:border-emerald-600 data-[state=checked]:bg-emerald-600"
                            />

                            <Label
                                for="remember"
                                class="cursor-pointer text-sm text-slate-600"
                            >
                                Ingat saya
                            </Label>
                        </div>

                        <!-- Submit -->
                        <Button
                            type="submit"
                            class="mt-2 h-12 w-full rounded-xl bg-emerald-600 text-base font-bold text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-700 hover:shadow-emerald-200"
                            :tabindex="4"
                            :disabled="processing"
                            data-test="login-button"
                        >
                            <Spinner v-if="processing" />
                            Masuk ke Akun
                        </Button>
                    </Form>

                    <!-- Register -->
                    <div class="mt-7 border-t border-slate-100 pt-6 text-center">
                        <p class="text-sm text-slate-500">
                            Belum memiliki akun?
                            <Link
                                :href="register().url"
                                class="font-bold text-emerald-600 hover:text-emerald-700"
                            >
                                Daftar Sekarang
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
