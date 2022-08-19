<script setup lang="ts">
import AuthLayout from "@/views/layouts/auth.vue";
import AuthBasic from "@/views/components/auth/auth-basic.vue";
import AuthTwoFactor from "@/views/components/auth/auth-two-factor.vue";

</script>

<template>
    <auth-layout>
        <div class="">
            <auth-basic/>
            <auth-two-factor/>
        </div>
    </auth-layout>
</template>


