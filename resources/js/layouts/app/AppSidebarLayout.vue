
<script setup lang="ts">
import AppContent from "@/components/AppContent.vue";
import AppShell from "@/components/AppShell.vue";
import AppSidebar from "@/components/AppSidebar.vue";
import AppSidebarHeader from "@/components/AppSidebarHeader.vue";
import { Toaster } from "@/components/ui/sonner";
import type { BreadcrumbItem } from "@/types";

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell
        variant="sidebar"
        class="min-h-svh w-full bg-[#f6faf8]"
    >
        <!-- Sidebar -->
        <AppSidebar />

        <!-- Main Content -->
        <AppContent
            variant="sidebar"
            class="min-w-0 flex-1 bg-[#f6faf8]"
        >
            <!-- Header -->
            <AppSidebarHeader
                :breadcrumbs="breadcrumbs"
                class="sticky top-0 z-40 h-16 shrink-0
                border-b border-emerald-100
                bg-white/95 shadow-sm backdrop-blur-md"
            />

            <!-- Page Content -->
            <main
                class="min-h-[calc(100svh-4rem)] w-full
                min-w-0 overflow-x-hidden
                px-4 py-6 sm:px-6 lg:px-8"
            >
                <div class="mx-auto w-full max-w-[1600px] min-w-0">
                    <slot />
                </div>
            </main>
        </AppContent>

        <!-- Notifications -->
        <Toaster
            position="top-right"
            richColors
            closeButton
        />
    </AppShell>
</template>
