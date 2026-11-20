<script setup lang="ts"></script>

<template>
    <div
        class="flex min-w-0 items-center gap-2.5 group-data-[collapsible=icon]:justify-center group-data-[collapsible=icon]:gap-0"
    >
        <!-- Logo -->
        <div
            class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-600/20 transition-all duration-300"
        >
            <span class="text-3xl font-medium leading-none">+</span>
        </div>

        <!-- Nama Aplikasi -->
        <div
            class="flex min-w-0 flex-col transition-all duration-200 group-data-[collapsible=icon]:hidden"
        >
            <span
                class="truncate text-lg font-bold tracking-tight text-slate-800"
            >
                Medi<span class="text-emerald-600">Stock</span>
            </span>

            <span
                class="text-[9px] font-semibold uppercase tracking-[2px] text-slate-400"
            >
                Pharmacy Management
            </span>
        </div>
    </div>
</template>
