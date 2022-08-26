<script lang="ts" setup>
const props = defineProps({
    message: String,
    isError: Boolean
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm" :class="{'text-red-600 dark:text-red-400': isError, 'text-gray-500': !isError}">
            {{ message }}
        </p>
    </div>
</template>

<style scoped>

</style>
