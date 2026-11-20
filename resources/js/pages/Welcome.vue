<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { dashboard, login, register } from "@/routes";
</script>

<template>
    <Head title="Manajemen Apotek" />

    <div class="min-h-screen bg-[#f7faf8] text-slate-800">
        <!-- NAVBAR -->
        <nav
            class="sticky top-0 z-50 border-b border-emerald-100 bg-white/90 backdrop-blur-xl"
        >
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8"
            >
                <a href="/" class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-200"
                    >
                        <svg
                            class="h-7 w-7"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M12 5v14m-7-7h14"
                            />
                        </svg>
                    </div>
                    <div>
                        <h1
                            class="text-lg font-extrabold tracking-tight text-slate-900"
                        >
                            Medi<span class="text-emerald-600">Stock</span>
                        </h1>
                        <p
                            class="text-[10px] font-semibold uppercase tracking-[2px] text-slate-400"
                        >
                            Pharmacy Management
                        </p>
                    </div>
                </a>

                <div class="hidden items-center gap-9 md:flex">
                    <a
                        href="#beranda"
                        class="text-sm font-semibold text-emerald-700"
                        >Beranda</a
                    >
                    <a
                        href="#fitur"
                        class="text-sm font-medium text-slate-600 transition hover:text-emerald-600"
                        >Fitur</a
                    >
                    <a
                        href="#tentang"
                        class="text-sm font-medium text-slate-600 transition hover:text-emerald-600"
                        >Tentang</a
                    >
                    <a
                        href="#kontak"
                        class="text-sm font-medium text-slate-600 transition hover:text-emerald-600"
                        >Kontak</a
                    >
                </div>

                <div class="flex items-center gap-3">
                    <template v-if="$page.props.auth?.user">
                        <Link
                            :href="dashboard()"
                            class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-200 transition hover:bg-emerald-700"
                        >
                            Dashboard
                        </Link>
                    </template>

                    <template v-else>
                        <Link
                            :href="login()"
                            class="hidden rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-700 sm:block"
                        >
                            Login
                        </Link>

                        <Link
                            :href="register()"
                            class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-700"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- HERO -->
        <section id="beranda" class="relative overflow-hidden">
            <div
                class="absolute -left-40 top-20 h-96 w-96 rounded-full bg-emerald-100/60 blur-3xl"
            ></div>
            <div
                class="absolute -right-40 bottom-0 h-96 w-96 rounded-full bg-teal-100/60 blur-3xl"
            ></div>

            <div
                class="relative mx-auto grid max-w-7xl items-center gap-14 px-6 py-20 lg:grid-cols-2 lg:px-8 lg:py-28"
            >
                <!-- HERO TEXT -->
                <div>
                    <div
                        class="mb-7 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 py-2 shadow-sm"
                    >
                        <span
                            class="h-2 w-2 rounded-full bg-emerald-500"
                        ></span>
                        <span
                            class="text-xs font-bold tracking-wide text-emerald-700"
                        >
                            SISTEM MANAJEMEN APOTEK DIGITAL
                        </span>
                    </div>

                    <h2
                        class="max-w-xl text-4xl font-black leading-tight tracking-tight text-slate-900 sm:text-5xl lg:text-6xl"
                    >
                        Kelola Apotek
                        <span class="text-emerald-600">Lebih Mudah</span>
                        dan Terorganisir.
                    </h2>

                    <p class="mt-7 max-w-lg text-base leading-8 text-slate-500">
                        Sistem manajemen apotek untuk membantu mengelola data
                        obat, memantau persediaan stok, serta mencatat transaksi
                        dalam satu platform yang praktis dan efisien.
                    </p>

                    <div class="mt-9 flex flex-wrap items-center gap-4">
                        <template v-if="$page.props.auth?.user">
                            <Link
                                :href="dashboard()"
                                class="group inline-flex items-center gap-3 rounded-xl bg-emerald-600 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-emerald-200 transition hover:-translate-y-1 hover:bg-emerald-700"
                            >
                                Buka Dashboard
                                <span
                                    class="transition group-hover:translate-x-1"
                                    >→</span
                                >
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="register()"
                                class="group inline-flex items-center gap-3 rounded-xl bg-emerald-600 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-emerald-200 transition hover:-translate-y-1 hover:bg-emerald-700"
                            >
                                Mulai Sekarang
                                <span
                                    class="transition group-hover:translate-x-1"
                                    >→</span
                                >
                            </Link>

                            <Link
                                :href="login()"
                                class="rounded-xl border border-slate-200 bg-white px-7 py-4 text-sm font-bold text-slate-700 transition hover:border-emerald-300 hover:text-emerald-700"
                            >
                                Login
                            </Link>
                        </template>
                    </div>

                    <div
                        class="mt-12 flex flex-wrap items-center gap-8 border-t border-slate-200 pt-8"
                    >
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">
                                01
                            </h3>
                            <p class="mt-1 text-xs font-medium text-slate-500">
                                Platform Terintegrasi
                            </p>
                        </div>
                        <div class="h-10 w-px bg-slate-200"></div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">
                                24/7
                            </h3>
                            <p class="mt-1 text-xs font-medium text-slate-500">
                                Akses Sistem
                            </p>
                        </div>
                        <div class="h-10 w-px bg-slate-200"></div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">
                                Mudah
                            </h3>
                            <p class="mt-1 text-xs font-medium text-slate-500">
                                Pengelolaan Data
                            </p>
                        </div>
                    </div>
                </div>

                <!-- HERO VISUAL -->
                <div class="relative mx-auto w-full max-w-lg">
                    <div
                        class="absolute -right-5 -top-5 h-28 w-28 rounded-full border-[18px] border-emerald-100"
                    ></div>
                    <div
                        class="absolute -bottom-7 -left-7 h-32 w-32 rounded-full bg-teal-100/70 blur-2xl"
                    ></div>

                    <div
                        class="relative overflow-hidden rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-2xl shadow-emerald-900/10 sm:p-8"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-bold uppercase tracking-widest text-emerald-600"
                                >
                                    Pharmacy Overview
                                </p>
                                <h3
                                    class="mt-2 text-2xl font-black text-slate-900"
                                >
                                    Manajemen Apotek
                                </h3>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600"
                            >
                                <svg
                                    class="h-7 w-7"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 3v18m-9-9h18"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Dashboard illustration -->
                        <div
                            class="mt-8 rounded-2xl bg-emerald-600 p-6 text-white"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-sm font-medium text-emerald-100"
                                    >
                                        Sistem Pengelolaan
                                    </p>
                                    <p class="mt-1 text-xl font-extrabold">
                                        Apotek Digital
                                    </p>
                                </div>
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20"
                                >
                                    <svg
                                        class="h-9 w-9"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 5v14m-7-7h14"
                                        />
                                    </svg>
                                </div>
                            </div>

                            <div class="mt-7 grid grid-cols-3 gap-3">
                                <div class="rounded-xl bg-white/15 p-3">
                                    <div class="text-xl font-black">01</div>
                                    <p
                                        class="mt-1 text-[10px] text-emerald-100"
                                    >
                                        Data Obat
                                    </p>
                                </div>
                                <div class="rounded-xl bg-white/15 p-3">
                                    <div class="text-xl font-black">02</div>
                                    <p
                                        class="mt-1 text-[10px] text-emerald-100"
                                    >
                                        Stok Obat
                                    </p>
                                </div>
                                <div class="rounded-xl bg-white/15 p-3">
                                    <div class="text-xl font-black">03</div>
                                    <p
                                        class="mt-1 text-[10px] text-emerald-100"
                                    >
                                        Transaksi
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-4">
                            <div
                                class="rounded-2xl border border-slate-100 bg-slate-50 p-5"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                        />
                                    </svg>
                                </div>
                                <p
                                    class="mt-4 text-sm font-bold text-slate-800"
                                >
                                    Data Obat
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Kelola informasi obat
                                </p>
                            </div>

                            <div
                                class="rounded-2xl border border-slate-100 bg-slate-50 p-5"
                            >
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-100 text-teal-700"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 3v18h18M18 17V9m-5 8V5m-5 12v-5"
                                        />
                                    </svg>
                                </div>
                                <p
                                    class="mt-4 text-sm font-bold text-slate-800"
                                >
                                    Monitoring Stok
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Pantau persediaan
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-5 flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 p-4"
                        >
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-white"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-emerald-900">
                                    Pengelolaan Lebih Praktis
                                </p>
                                <p class="text-xs text-emerald-700">
                                    Semua data dalam satu sistem
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="absolute -left-8 top-1/3 hidden rounded-2xl border border-emerald-100 bg-white p-4 shadow-xl sm:block"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"
                            >
                                ✓
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">
                                    Sistem Terintegrasi
                                </p>
                                <p class="text-[10px] text-slate-500">
                                    Kelola lebih mudah
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section id="fitur" class="bg-white py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <span
                        class="text-xs font-extrabold uppercase tracking-[3px] text-emerald-600"
                    >
                        Fitur Utama
                    </span>
                    <h2
                        class="mt-4 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl"
                    >
                        Semua Kebutuhan Apotek dalam Satu Sistem
                    </h2>
                    <p class="mt-5 leading-7 text-slate-500">
                        Mendukung pengelolaan operasional apotek melalui fitur
                        yang mudah digunakan dan terorganisir.
                    </p>
                </div>

                <div class="mt-16 grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        class="group rounded-3xl border border-slate-100 bg-[#f9fcfa] p-7 transition duration-300 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5"
                    >
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 transition group-hover:bg-emerald-600 group-hover:text-white"
                        >
                            <svg
                                class="h-7 w-7"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-lg font-extrabold text-slate-900">
                            Manajemen Obat
                        </h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">
                            Mengelola data obat, kategori, harga, dan informasi
                            produk.
                        </p>
                    </div>

                    <div
                        class="group rounded-3xl border border-slate-100 bg-[#f9fcfa] p-7 transition duration-300 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5"
                    >
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-100 text-teal-700 transition group-hover:bg-teal-600 group-hover:text-white"
                        >
                            <svg
                                class="h-7 w-7"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3v18h18M18 17V9m-5 8V5m-5 12v-5"
                                />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-lg font-extrabold text-slate-900">
                            Monitoring Stok
                        </h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">
                            Memantau ketersediaan obat dan membantu mengelola
                            perubahan stok.
                        </p>
                    </div>

                    <div
                        class="group rounded-3xl border border-slate-100 bg-[#f9fcfa] p-7 transition duration-300 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5"
                    >
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100 text-orange-600 transition group-hover:bg-orange-500 group-hover:text-white"
                        >
                            <svg
                                class="h-7 w-7"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3h2l2.4 12.4a2 2 0 002 1.6h8.8a2 2 0 002-1.6L22 8H6M10 21h.01M18 21h.01"
                                />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-lg font-extrabold text-slate-900">
                            Transaksi
                        </h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">
                            Membantu pencatatan transaksi penjualan obat secara
                            terstruktur.
                        </p>
                    </div>

                    <div
                        class="group rounded-3xl border border-slate-100 bg-[#f9fcfa] p-7 transition duration-300 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5"
                    >
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-100 text-purple-700 transition group-hover:bg-purple-600 group-hover:text-white"
                        >
                            <svg
                                class="h-7 w-7"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 17v-2a4 4 0 018 0v2m-8 0H5m4 0h8m-8 0v3h8v-3m-5-8a3 3 0 110-6 3 3 0 010 6z"
                                />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-lg font-extrabold text-slate-900">
                            Laporan
                        </h3>
                        <p class="mt-3 text-sm leading-7 text-slate-500">
                            Menyediakan informasi laporan untuk membantu
                            pemantauan aktivitas apotek.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ABOUT -->
        <section id="tentang" class="bg-[#f0f8f3] py-24">
            <div
                class="mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-2 lg:px-8"
            >
                <div>
                    <span
                        class="text-xs font-extrabold uppercase tracking-[3px] text-emerald-600"
                    >
                        Tentang Sistem
                    </span>
                    <h2
                        class="mt-4 text-3xl font-black leading-tight text-slate-900 sm:text-4xl"
                    >
                        Pengelolaan Apotek yang Lebih Terstruktur dan Efisien
                    </h2>
                    <p class="mt-6 leading-8 text-slate-600">
                        MediStock merupakan sistem manajemen apotek berbasis web
                        yang dirancang untuk membantu proses pengelolaan data
                        obat, persediaan, transaksi, dan laporan dalam satu
                        platform.
                    </p>
                    <p class="mt-4 leading-8 text-slate-600">
                        Dengan tampilan yang sederhana dan fitur yang
                        terorganisir, pengelolaan aktivitas apotek dapat
                        dilakukan dengan lebih praktis.
                    </p>

                    <div class="mt-8 flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white"
                        >
                            ✓
                        </div>
                        <span class="text-sm font-bold text-slate-800"
                            >Antarmuka mudah digunakan</span
                        >
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white"
                        >
                            ✓
                        </div>
                        <span class="text-sm font-bold text-slate-800"
                            >Pengelolaan data terpusat</span
                        >
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white"
                        >
                            ✓
                        </div>
                        <span class="text-sm font-bold text-slate-800"
                            >Akses melalui browser</span
                        >
                    </div>
                </div>

                <div class="relative">
                    <div
                        class="rounded-[2rem] bg-emerald-700 p-8 shadow-2xl shadow-emerald-900/20 sm:p-12"
                    >
                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/15 text-white"
                        >
                            <svg
                                class="h-12 w-12"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M12 5v14m-7-7h14"
                                />
                            </svg>
                        </div>

                        <h3 class="mt-9 text-3xl font-black text-white">
                            MediStock
                        </h3>
                        <p class="mt-4 max-w-sm leading-8 text-emerald-100">
                            Satu sistem untuk mendukung pengelolaan apotek yang
                            lebih rapi, praktis, dan terorganisir.
                        </p>

                        <div class="mt-12 border-t border-white/20 pt-7">
                            <p class="text-sm font-semibold text-emerald-100">
                                Pharmacy Management System
                            </p>
                        </div>
                    </div>

                    <div
                        class="absolute -bottom-5 -right-5 -z-10 h-40 w-40 rounded-full bg-emerald-200 blur-2xl"
                    ></div>
                </div>
            </div>
        </section>

        <!-- CTA -->

        <!-- FOOTER -->
        <footer class="border-t border-slate-100 bg-white">
            <div
                class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-6 py-8 sm:flex-row lg:px-8"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white"
                    >
                        +
                    </div>
                    <span class="font-extrabold text-slate-900">
                        Medi<span class="text-emerald-600">Stock</span>
                    </span>
                </div>

                <p class="text-center text-xs text-slate-400">
                    © 2026 MediStock. Pharmacy Management System.
                </p>

                <a
                    href="#beranda"
                    class="text-sm font-bold text-emerald-600 transition hover:text-emerald-800"
                >
                    Kembali ke atas ↑
                </a>
            </div>
        </footer>
    </div>
</template>
