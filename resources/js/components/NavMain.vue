
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/components/ui/sidebar";

import { useCurrentUrl } from "@/composables/useCurrentUrl";
import type { NavItem } from "@/types";

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <SidebarGroup class="px-2 py-3">
        <SidebarGroupLabel
            class="mb-3 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 group-data-[collapsible=icon]:hidden"
        >
            Menu Utama
        </SidebarGroupLabel>

        <SidebarMenu class="gap-2">
            <SidebarMenuItem
                v-for="item in items"
                :key="item.title"
            >
                <SidebarMenuButton
                    as-child
                    :is-active="isCurrentUrl(item.href)"
                    :tooltip="item.title"
                    class="h-12 rounded-xl px-3 text-slate-600
                    transition-colors duration-200
                    hover:bg-emerald-50 hover:text-emerald-700
                    data-[active=true]:bg-emerald-100
                    data-[active=true]:text-emerald-700
                    group-data-[collapsible=icon]:!mx-auto
                    group-data-[collapsible=icon]:!h-12
                    group-data-[collapsible=icon]:!w-12
                    group-data-[collapsible=icon]:!justify-center
                    group-data-[collapsible=icon]:!px-0"
                >
                    <Link
                        :href="item.href"
                        class="flex w-full items-center gap-3 group-data-[collapsible=icon]:justify-center"
                    >
                        <component
                            :is="item.icon"
                            class="h-5 w-5 shrink-0 text-emerald-600"
                        />

                        <span class="truncate group-data-[collapsible=icon]:hidden">
                            {{ item.title }}
                        </span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
