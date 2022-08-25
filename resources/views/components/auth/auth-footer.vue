<template>
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div>
            <ul class="text-sm text-gray-500 space-y-2">
                <li>
                    <p>We don't store your password and Multi-Factor authentication code. You can review the source code for this service <a href="#" class="underline decoration-dotted hover:text-indigo-500" target="_blank">here</a>.</p>
                </li>
                <li>
                    <p>This service is not endorsed by or affiliated with the Cloud Imperium or Roberts Space Industries group of companies.</p>
                </li>
                <li>
                    <Link :href="route('login')" class="underline decoration-dotted hover:text-indigo-500" target="_blank">
                        Privacy Policy
                    </Link>
                </li>
                <li>
                    <div class="flex justify-center space-x-2 items-end pt-6">
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-600 mb-0.5">Developed by</p>
                            <a href="https://github.com/laeng" target="_blank" rel="nofollow"><laeng-co-logo class="h-6 w-auto fill-current text-gray-400 dark:text-gray-600"/></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
import LaengCoLogo from "@/views/components/logo/laeng-co.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';

</script>

<style scoped>

</style>
