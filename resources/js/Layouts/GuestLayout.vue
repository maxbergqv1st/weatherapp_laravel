<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="border-b border-gray-100 bg-white">
            <div class="mx-auto flex min-h-16 max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-8">
                <Link :href="route('home')" class="flex shrink-0 items-center">
                    <ApplicationLogo
                        class="block h-9 w-auto fill-current text-gray-800"
                    />
                </Link>

                <div class="flex items-center gap-3">
                    <Link
                        :href="route('login')"
                        class="rounded-md px-4 py-2 text-sm font-medium text-gray-600 transition hover:text-gray-900"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Register
                    </Link>
                </div>
            </div>
        </nav>

        <div
            class="flex min-h-[calc(100vh-4rem)] flex-col items-center justify-center px-4 py-6 sm:py-0"
        >
            <div class="w-full rounded-lg bg-white shadow-md ring-1 ring-gray-900/10 sm:max-w-md">
                <div class="px-6 py-8 sm:px-8">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
