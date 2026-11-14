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
        title: "Data Obat (Inventaris)",
        href: "/medicines",
        icon: Pill,
    },
    {
        title: "Input / Scan Obat Masuk",
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
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>