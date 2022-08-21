<template>
    <div class="rounded-md bg-yellow-100 dark:bg-red-200 p-4 shadow-md dark:shadow-red-200/50">
        <div class="flex">
            <div class="flex-shrink-0">
                <XCircleIcon class="h-5 w-5 text-red-400 dark:text-red-500" aria-hidden="true" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-900">{{ title }}</h3>
                <div class="mt-2 text-sm text-red-700 dark:text-red-800">
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { XCircleIcon } from '@heroicons/vue/solid'

defineProps({
    title: {
        type: String,
        default: ''
    }
});
</script>
