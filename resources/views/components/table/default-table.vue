<template>
    <div class="relative border border-gray-300 dark:border-gray-700 rounded-md overflow-x-hidden">
        <div class="overflow-x-auto overflow-y-hidden rounded-t-md">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700 dark:text-white">
                <thead class="bg-gray-100 dark:bg-[#131925] text-xs font-medium text-left">
                <slot name="head"/>
                </thead>
                <tbody class="bg-white dark:bg-[#1B2435] divide-y divide-gray-300 dark:divide-gray-700 text-sm">
                <slot name="body"/>
                </tbody>
            </table>
        </div>
        <nav class="flex items-center justify-between border-t border-gray-300 dark:border-gray-700 text-sm bg-gray-100 dark:bg-[#131925] px-4 py-3 sm:px-6 rounded-b-md">
            <div class="">
                <slot name="paginateMessage"/>
            </div>
            <div class="flex flex-1 justify-between md:justify-end space-x-2">
                <slot name="paginateButton"/>
            </div>
        </nav>
    </div>
</template>

<script lang="ts" setup>
</script>

<style scoped>

</style>
