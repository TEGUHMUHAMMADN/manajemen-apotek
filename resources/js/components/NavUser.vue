
<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { ChevronsUpDown } from "@lucide/vue";
import { computed } from "vue";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from "@/components/ui/sidebar";

import UserInfo from "@/components/UserInfo.vue";
import UserMenuContent from "@/components/UserMenuContent.vue";

const page = usePage();
const user = computed(() => page.props.auth.user);

const { isMobile, state } = useSidebar();

const dropdownSide = computed(() => {
    if (isMobile.value) return "top";
    if (state.value === "collapsed") return "right";
    return "top";
});
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <!-- Profile Button -->
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton
                        size="lg"
                        class="h-16 w-full rounded-xl
                        border border-transparent px-3
                        transition-all duration-200
                        hover:border-emerald-100
                        hover:bg-emerald-50
                        data-[state=open]:border-emerald-200
                        data-[state=open]:bg-emerald-50
                        group-data-[collapsible=icon]:!h-12
                        group-data-[collapsible=icon]:!w-12
                        group-data-[collapsible=icon]:mx-auto
                        group-data-[collapsible=icon]:!justify-center
                        group-data-[collapsible=icon]:!px-0"
                        data-test="sidebar-menu-button"
                    >
                        <UserInfo :user="user" />

                        <ChevronsUpDown
                            class="ml-auto h-4 w-4 shrink-0
                            text-slate-400
                            group-data-[collapsible=icon]:hidden"
                        />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>

                <!-- Dropdown -->
                <DropdownMenuContent
                    :side="dropdownSide"
                    align="end"
                    :side-offset="10"
                    :collision-padding="12"
                    class="z-[100] w-64 max-h-[calc(100svh-2rem)]
                    overflow-y-auto rounded-2xl
                    border border-emerald-100
                    bg-white p-2 shadow-2xl"
                >
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
