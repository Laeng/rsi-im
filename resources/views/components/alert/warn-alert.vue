<template>
    <div class="rounded-md bg-yellow-100 dark:bg-yellow-200 p-4 shadow-md dark:shadow-yellow-200/50">
        <div class="flex">
            <div class="flex-shrink-0">
                <ExclamationIcon class="h-5 w-5 text-yellow-400 dark:text-yellow-500" aria-hidden="true" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-900">{{ title }}</h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-800">
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ExclamationIcon } from '@heroicons/vue/solid'

defineProps({
    title: {
        type: String,
        default: 'WARNING'
    }
});
</script>
