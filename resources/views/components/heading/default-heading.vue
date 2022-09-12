<template>
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold dark:text-white leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                <slot name="title"/>
            </h2>
        </div>
        <div class="flex md:ml-4">
            <slot name="buttons"/>
        </div>
    </div>
</template>
<script lang="ts" setup>

</script>

<style scoped>

</style>
