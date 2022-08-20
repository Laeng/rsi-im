<script setup lang="ts">
import AuthLayout from "@/views/layouts/auth.vue";
import AuthBasic from "@/views/components/auth/auth-basic.vue";
import AuthTwoFactor from "@/views/components/auth/auth-two-factor.vue";

const props = defineProps({
    code: String,
    message: String,
});

let viewType: string = 'unknown';

switch (props.code) {
    case 'ErrNeedLogin':
    case 'ErrWrongPassword_email':
    case 'ErrCaptchaRequiredLauncher':
    case 'ErrInvalidChallengeCode':
        viewType = 'basic';
        break;
    case 'ErrMultiStepRequired':
    case 'ErrMultiStepWrongCode':
    case 'ErrMultiStepExpired':
        viewType = 'two-factor';
        break;
    default:
        break;
}

console.log(props.code);

</script>

<template>
    <auth-layout>
        <template v-if="viewType === 'basic'">
            <auth-basic :code="this.$props.code" :message="this.$props.message"/>
        </template>
        <template v-if="viewType === 'two-factor'">
            <auth-two-factor :code="this.$props.code" :message="this.$props.message"/>
        </template>
        <template v-if="viewType === 'unknown'">

        </template>
    </auth-layout>
</template>


