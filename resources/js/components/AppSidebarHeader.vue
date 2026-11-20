
<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { PanelLeftClose, PanelLeftOpen } from '@lucide/vue';
import { useSidebar } from '@/components/ui/sidebar';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const { state, isMobile, openMobile } = useSidebar();
</script>

<template>
    <header
        class="sticky top-0 z-40 flex h-16 shrink-0 items-center justify-between border-b border-emerald-100 bg-white px-5 shadow-sm"
    >
        <div class="flex items-center gap-4">

            <!-- Tombol Sidebar -->
            <SidebarTrigger
                class="!ml-0 !flex !h-11 !w-11 !items-center !justify-center
                !rounded-xl !border !border-emerald-200
                !bg-emerald-600 !text-white
                shadow-md shadow-emerald-200/50
                transition-all duration-200
                hover:!bg-emerald-700 hover:shadow-lg
                active:scale-95
                focus-visible:ring-4 focus-visible:ring-emerald-200"
            >
                <PanelLeftClose
                    v-if="isMobile ? openMobile : state === 'expanded'"
                    class="!h-5 !w-5"
                />
                <PanelLeftOpen
                    v-else
                    class="!h-5 !w-5"
                />
                <span class="sr-only">Toggle Sidebar</span>
            </SidebarTrigger>

            <!-- Breadcrumb -->
            <div
                v-if="breadcrumbs && breadcrumbs.length > 0"
                class="min-w-0"
            >
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>

            <div v-else class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-sm font-semibold text-slate-700">
                    MediStock Management System
                </span>
            </div>
        </div>

        <!-- Status -->
        <div
            class="hidden items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-2 sm:flex"
        >
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-semibold text-emerald-700">
                Sistem Aktif
            </span>
        </div>
    </header>
</template>
