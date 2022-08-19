<script setup lang="ts">
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);
defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
</script>

<template>
    <input class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
           :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>
