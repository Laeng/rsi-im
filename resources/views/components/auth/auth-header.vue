<template>
    <div class="grid grid-cols-1 place-items-center space-y-4 mt-4">
        <rsi-im-logo class="fill-current h-12 w-auto" color-one="#6366f1" color-two="#72A1E0" />
        <p class="text-gray-600 dark:text-gray-400">
            {{ $t('auth.text_service_subtitle') }}
        </p>
    </div>
</template>

<script setup lang="ts">
import RsiImLogo from "@/views/components/logo/rsi-im.vue";
</script>
