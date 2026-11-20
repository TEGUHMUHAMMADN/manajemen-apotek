
<script setup lang="ts">
import type { UrlMethodPair } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';
import { usePasskeyVerify } from '@laravel/passkeys/vue';
import { Fingerprint } from '@lucide/vue';

import InputError from '@/components/InputError.vue';
import { Spinner } from '@/components/ui/spinner';

type Props = {
    routes?: {
        options: UrlMethodPair;
        submit: UrlMethodPair;
    };
    label?: string;
    loadingLabel?: string;
    separator?: string;
};

const props = defineProps<Props>();

const { verify, isLoading, error, isSupported } = usePasskeyVerify({
    ...(props.routes
        ? {
              routes: {
                  options: props.routes.options.url,
                  submit: props.routes.submit.url,
              },
          }
        : {}),
    onSuccess: (response) => {
        router.visit(response.redirect ?? '/dashboard');
    },
});
</script>

<template>
    <div v-if="isSupported" class="w-full">

        <!-- Passkey Button -->
        <div class="grid gap-3">
            <button
                type="button"
                @click="verify"
                :disabled="isLoading"
                class="group flex min-h-[58px] w-full items-center justify-center gap-3 rounded-xl border border-emerald-200 bg-white px-5 py-3 text-sm font-semibold text-emerald-800 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-emerald-400 hover:bg-emerald-50 hover:shadow-md hover:shadow-emerald-100 active:translate-y-0 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-60"
            >
                <!-- Icon -->
                <span
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white"
                >
                    <Spinner
                        v-if="isLoading"
                        class="h-5 w-5"
                    />

                    <Fingerprint
                        v-else
                        class="h-6 w-6 transition-transform duration-300 group-hover:scale-110"
                        :stroke-width="1.8"
                    />
                </span>

                <!-- Label -->
                <span>
                    {{
                        isLoading
                            ? (props.loadingLabel ?? 'Authenticating...')
                            : (props.label ?? 'Sign in with a passkey')
                    }}
                </span>
            </button>

            <!-- Error -->
            <div
                v-if="error"
                class="rounded-xl border border-red-100 bg-red-50 px-4 py-3"
            >
                <InputError :message="error" />
            </div>
        </div>

        <!-- Elegant Divider -->
        <div class="my-7 flex items-center gap-4">
            <div class="h-px flex-1 bg-slate-200"></div>

            <span
                class="shrink-0 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400"
            >
                {{ props.separator ?? 'Or continue with email' }}
            </span>

            <div class="h-px flex-1 bg-slate-200"></div>
        </div>

    </div>
</template>
