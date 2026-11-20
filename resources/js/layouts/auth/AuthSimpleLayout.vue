<script setup lang="ts">
defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-screen w-full bg-[#f4faf6]">
        <slot />
    </div>
</template>
