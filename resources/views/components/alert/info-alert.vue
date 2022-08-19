<template>
    <div class="rounded-md bg-blue-100 dark:bg-blue-200 p-4 shadow-md dark:shadow-blue-200/50">
        <div class="flex">
            <div class="flex-shrink-0">
                <InformationCircleIcon class="h-5 w-5 text-blue-400 dark:text-blue-500" aria-hidden="true" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-900">{{ title }}</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-800">
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { InformationCircleIcon } from '@heroicons/vue/solid'

defineProps({
    title: {
        type: String,
        default: ''
    }
});
</script>
