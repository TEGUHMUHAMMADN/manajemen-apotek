<script setup lang="ts">
import type { HTMLAttributes } from "vue";

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes["class"];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 40 40"
        :class="className"
        v-bind="$attrs"
    >
        <!-- Ikon silang medis -->
        <rect x="2" y="2" width="36" height="36" rx="10" fill="currentColor" />
        <path d="M17 9H23V17H31V23H23V31H17V23H9V17H17V9Z" fill="white" />
    </svg>
</template>
