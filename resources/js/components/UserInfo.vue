
<script setup lang="ts">
import { computed } from "vue";
import {
    Avatar,
    AvatarFallback,
    AvatarImage,
} from "@/components/ui/avatar";

import { useInitials } from "@/composables/useInitials";
import type { User } from "@/types";

type Props = {
    user: User;
    showEmail?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const { getInitials } = useInitials();

const showAvatar = computed(
    () => !!props.user.avatar
);
</script>

<template>
    <div
        class="flex min-w-0 flex-1 items-center gap-3
        group-data-[collapsible=icon]:justify-center
        group-data-[collapsible=icon]:gap-0"
    >
        <!-- User Avatar -->
        <Avatar
            class="h-10 w-10 shrink-0 rounded-xl
            border-2 border-emerald-100
            shadow-sm transition-all duration-300
            group-hover:border-emerald-300
            group-hover:shadow-emerald-100"
        >
            <AvatarImage
                v-if="showAvatar"
                :src="user.avatar!"
                :alt="user.name"
                class="rounded-xl object-cover"
            />

            <AvatarFallback
                class="rounded-xl bg-gradient-to-br
                from-emerald-500 to-teal-700
                text-sm font-bold tracking-wide text-white"
            >
                {{ getInitials(user.name) }}
            </AvatarFallback>
        </Avatar>

        <!-- User Information -->
        <div
            class="grid min-w-0 flex-1 text-left
            group-data-[collapsible=icon]:hidden"
        >
            <span
                class="truncate text-sm font-bold text-slate-800"
            >
                {{ user.name }}
            </span>

            <span
                v-if="showEmail"
                class="truncate text-xs text-slate-500"
            >
                {{ user.email }}
            </span>

            <span
                v-else
                class="text-[10px] font-medium
                tracking-wide text-emerald-600"
            >
                ADMIN APOTEK
            </span>
        </div>
    </div>
</template>
