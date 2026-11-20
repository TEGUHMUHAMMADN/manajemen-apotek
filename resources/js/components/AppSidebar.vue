
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

import {
    LayoutGrid,
    Pill,
    Tags,
    ShoppingCart,
    FileText,
    Barcode,
} from "@lucide/vue";

import AppLogo from "@/components/AppLogo.vue";
import NavMain from "@/components/NavMain.vue";
import NavUser from "@/components/NavUser.vue";

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/components/ui/sidebar";

import { dashboard } from "@/routes";
import type { NavItem } from "@/types";

const mainNavItems: NavItem[] = [
    {
        title: "Dashboard",
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: "Kategori Obat",
        href: "/categories",
        icon: Tags,
    },
    {
        title: "Data Obat",
        href: "/medicines",
        icon: Pill,
    },
    {
        title: "Input Obat Masuk",
        href: "/medicines/create",
        icon: Barcode,
    },
    {
        title: "Kasir (POS)",
        href: "/transactions",
        icon: ShoppingCart,
    },
    {
        title: "Laporan Bulanan",
        href: "/reports",
        icon: FileText,
    },
];
</script>

<template>
    <Sidebar
        collapsible="icon"
        variant="sidebar"
        class="
            border-r border-emerald-100
            bg-white
            [--sidebar-width:16.25rem]
            [--sidebar-width-icon:4.75rem]
            [&_[data-sidebar=sidebar]]:!bg-white
            [&_[data-sidebar=sidebar]]:!text-slate-700
        "
    >
        <!-- LOGO -->
        <SidebarHeader
            class="border-b border-emerald-100 bg-white p-3"
        >
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        size="lg"
                        as-child
                        tooltip="MediStock"
                        class="
                            h-14 rounded-xl
                            transition-all duration-200
                            hover:bg-emerald-50
                            group-data-[collapsible=icon]:!justify-center
                            group-data-[collapsible=icon]:!px-0
                        "
                    >
                        <Link
                            :href="dashboard()"
                            class="flex items-center gap-2 overflow-hidden"
                        >
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <!-- NAVIGATION -->
        <SidebarContent
            class="bg-white px-2 py-5"
        >
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <!-- ADMIN PROFILE -->
        <SidebarFooter
            class="
                border-t border-emerald-100
                bg-white p-2
            "
        >
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>
