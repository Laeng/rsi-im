<script setup lang="ts">
defineProps(['modelValue'])
defineEmits(['update:modelValue'])
</script>

<template>
    <select class="mt-1 block f-full pl-3 pr-10 py-2 w-full text-base border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-[#1B2435] dark:text-white"
            :value="modelValue" @input="$emit('update:modelValue', $event.target.value)">
        <slot/>
    </select>
</template>

<style scoped>
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    -webkit-appearance: none;
    -moz-appearance: none;
}
select::-ms-expand {
    display: none;
}
</style>
