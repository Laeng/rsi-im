<template>
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div>
            <ul class="text-sm text-gray-500 space-y-2">
                <li>
                    <p v-html="$t('auth.text_service_description_1')"></p>
                </li>
                <li>
                    <p v-html="$t('auth.text_service_description_2')"></p>
                </li>
                <li>
                    <p v-html="$t('auth.text_service_description_3')"></p>
                </li>
                <li>
                    <p v-html="$t('auth.text_service_description_4')"></p>
                </li>
                <li>
                    <p v-html="$t('auth.text_service_description_5')"></p>
                </li>
                <li class="flex">
                    <Link :href="route('login')" class="underline decoration-dotted hover:text-indigo-500" target="_blank" v-html="$t('auth.link_privacy_policy')"></Link>
                </li>
                <li>
                    <div class="flex justify-center space-x-2 items-end pt-6">
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-600 mb-0.5">Developed by</p>
                            <a href="https://github.com/laeng" target="_blank" rel="nofollow"><laeng-co-logo class="h-6 w-auto fill-current text-gray-400 dark:text-gray-600 hover:text-rose-600"/></a>
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
.decorate-link {
    text-decoration-line: underline;
    text-decoration-style: dotted;
}
.decorate-link:hover {
    color: rgb(99 102 241);
}
</style>
