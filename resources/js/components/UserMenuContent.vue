
<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { LogOut, Settings, UserRound } from "@lucide/vue";

import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from "@/components/ui/dropdown-menu";

import UserInfo from "@/components/UserInfo.vue";

import { logout } from "@/routes";
import { edit } from "@/routes/profile";

import type { User } from "@/types";

defineProps<{
    user: User;
}>();
</script>

<template>
    <div class="space-y-1">

        <!-- User Profile -->
        <DropdownMenuLabel class="p-1 font-normal">
            <div
                class="rounded-xl bg-emerald-50/80 p-3
                ring-1 ring-emerald-100"
            >
                <UserInfo
                    :user="user"
                    :show-email="true"
                />
            </div>
        </DropdownMenuLabel>

        <DropdownMenuSeparator class="my-2 bg-slate-100" />

        <!-- Menu -->
        <DropdownMenuGroup>

            <!-- Settings -->
            <DropdownMenuItem
                as-child
                class="cursor-pointer rounded-lg p-0
                text-slate-600 outline-none
                focus:bg-emerald-50 focus:text-emerald-700"
            >
                <Link
                    :href="edit()"
                    prefetch
                    class="flex w-full items-center gap-3
                    rounded-lg px-3 py-2.5"
                >
                    <span
                        class="flex size-9 items-center justify-center
                        rounded-lg bg-slate-100
                        text-slate-600"
                    >
                        <Settings class="size-[18px]" />
                    </span>

                    <span class="text-sm font-medium">
                        Pengaturan
                    </span>
                </Link>
            </DropdownMenuItem>

            <!-- Profile -->
            <DropdownMenuItem
                as-child
                class="cursor-pointer rounded-lg p-0
                text-slate-600 outline-none
                focus:bg-emerald-50 focus:text-emerald-700"
            >
                <Link
                    href="/settings/profile"
                    class="flex w-full items-center gap-3
                    rounded-lg px-3 py-2.5"
                >
                    <span
                        class="flex size-9 items-center justify-center
                        rounded-lg bg-slate-100
                        text-slate-600"
                    >
                        <UserRound class="size-[18px]" />
                    </span>

                    <span class="text-sm font-medium">
                        Profil Saya
                    </span>
                </Link>
            </DropdownMenuItem>

        </DropdownMenuGroup>

        <DropdownMenuSeparator class="my-2 bg-slate-100" />

        <!-- Logout -->
        <DropdownMenuItem
            as-child
            class="cursor-pointer rounded-lg p-0
            text-red-600 outline-none
            focus:bg-red-50 focus:text-red-700"
        >
            <Link
                :href="logout()"
                method="post"
                as="button"
                data-test="logout-button"
                class="flex w-full items-center gap-3
                rounded-lg px-3 py-2.5 text-left"
            >
                <span
                    class="flex size-9 items-center justify-center
                    rounded-lg bg-red-50 text-red-600"
                >
                    <LogOut class="size-[18px]" />
                </span>

                <span class="text-sm font-semibold">
                    Keluar dari Akun
                </span>
            </Link>
        </DropdownMenuItem>

    </div>
</template>
