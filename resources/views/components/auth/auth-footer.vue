<template>
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <footer-label class="text-gray-500 text-sm space-y-1"/>
        <div class="flex justify-center space-x-2 items-end pt-6">
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-600 mb-0.5">Developed by</p>
                <a href="https://github.com/laeng" target="_blank" rel="nofollow"><laeng-co-logo class="h-6 w-auto fill-current text-gray-400 dark:text-gray-600 hover:text-rose-600"/></a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import FooterLabel from "@/views/components/label/footer-label.vue";
import LaengCoLogo from "@/views/components/logo/laeng-co.vue";


</script>

<style scoped>

</style>
