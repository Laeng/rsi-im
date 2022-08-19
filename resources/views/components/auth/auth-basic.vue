<script setup lang="ts">
import {useForm} from "@inertiajs/inertia-vue3";
import TextInput from "@/views/components/input/text-input.vue";
import InputLabel from "@/views/components/label/input-label.vue";
import PrimaryButton from "@/views/components/button/primary-button.vue";
import InfoAlert from "@/views/components/alert/info-alert.vue";

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <info-alert title="Don't forget!">
                <ul>
                    <li>For the safety of your RSI account, be sure to check the web address. The correct URL for this service is <strong class="font-semibold underline decoration-dotted">rsi.im</strong>.</li>
                </ul>
            </info-alert>
        </div>
        <div>
            <input-label for="email" value="Email"/>
            <div class="mt-1">
                <text-input name="email" id="email" placeholder="someone@example.com"/>
            </div>
        </div>
        <div>
            <input-label for="password" value="Password"/>
            <div class="mt-1">
                <text-input name="password" id="password" type="password" placeholder="••••••••••••••••"/>
            </div>
        </div>
        <div class="flex items-center justify-between">
            <div class="text-sm">
                <a href="#" class="font-medium text-gray-600 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">What is this? Is it safe?</a>
            </div>
            <div class="text-sm">
                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">Account Recovery</a>
            </div>
        </div>
        <div>
            <primary-button>
                Sign in
            </primary-button>
        </div>
    </form>
</template>

<style scoped>

</style>
