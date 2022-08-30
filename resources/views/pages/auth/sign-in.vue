<script setup lang="ts">
import axios from 'axios'
import {useForm} from "@inertiajs/inertia-vue3";
import AuthLayout from "@/views/layouts/auth.vue";
import TextInput from "@/views/components/input/text-input.vue";
import InputLabel from "@/views/components/label/input-label.vue";
import PrimaryButton from "@/views/components/button/primary-button.vue";
import InfoAlert from "@/views/components/alert/info-alert.vue";
import ErrorAlert from "@/views/components/alert/error-alert.vue";
import {onMounted, reactive, ref, watch, watchEffect} from "vue";
import {RefreshIcon} from "@heroicons/vue/solid";
import InputDescription from "@/views/components/input/input-description.vue";

const defaultCaptchaImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
const props = defineProps({
    code: String,
    message: String,
    errors: Object,
});

const alert = reactive({
    show: false,

});

const captcha = reactive({
    src: defaultCaptchaImage,
    show: false,
    reload: false,
    lock: false
});

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const form = useForm({
    username: '',
    password: '',
    captcha: ''
});

const submit = () => {
    form.post(route('connect.process'), {
        onFinish: () => {
            form.reset('password');
            form.reset('captcha');

            captcha.src = defaultCaptchaImage;
            captcha.lock = false;
        },
    });
};


const refreshCaptcha = () => {
    if (!captcha.reload) {
        captcha.reload = true;
        axios.post(route('connect.captcha'))
            .then((response) => {
                captcha.src = response.data.data.image;
            })
            .catch(() => {

            })
            .finally(() => {
                captcha.reload = false;
                form.reset('captcha');
            });
    }
}

watchEffect(() => {
    alert.show = (props.code !== 'OK');

    switch (props.code) {
        case 'ErrCaptchaRequiredLauncher':
        case 'ErrInvalidChallengeCode':
            captcha.show = true;
            if (!captcha.lock) {
                captcha.lock = true;
                refreshCaptcha();
            }
            break;
        default:
            captcha.show = false;
            break;
    }
});

</script>

<template>
    <auth-layout>
        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <info-alert v-show="!alert.show" :title="$t('auth.sign_in.alert_check_hostname_title')">
                    <ul>
                        <li v-html="$t('auth.sign_in.alert_check_hostname_message')"></li>
                    </ul>
                </info-alert>
                <error-alert v-show="alert.show" title="ERROR">
                    <ul>
                        <li>{{ props.message }}</li>
                    </ul>
                </error-alert>
            </div>
            <div>
                <input-label for="email" :value="$t('auth.sign_in.form_email_label')"/>
                <div class="mt-1">
                    <text-input name="email" id="email" :placeholder="$t('auth.sign_in.form_email_placeholder')" v-model="form.username" required autofocus/>
                </div>
                <input-description class="mt-1" :is-error="props.errors?.username !== undefined" :message="props.errors?.username"/>
            </div>
            <div>
                <input-label for="password" :value="$t('auth.sign_in.form_password_label')"/>
                <div class="mt-1">
                    <text-input name="password" id="password" type="password" :placeholder="$t('auth.sign_in.form_password_placeholder')" v-model="form.password" required/>
                </div>
                <input-description class="mt-1" :is-error="props.errors?.password !== undefined" :message="props.errors?.password"/>
            </div>
            <div v-show="captcha.show">
                <input-label for="captcha" :value="$t('auth.sign_in.form_captcha_label')"/>
                <div class="mt-1 relative">
                    <div class="bg-black rounded-md w-full select-none flex justify-center shadow-sm">
                        <img class="h-28" :src="captcha.src" alt="captcha"/>
                    </div>
                    <div class="text-gray-500 hover:text-gray-300 md:text-sm absolute bottom-1 right-2 cursor-pointer mb-1" @click="refreshCaptcha">
                        <RefreshIcon class="h-5 w-5" :class="{'icon-spin': captcha.reload}"></RefreshIcon>
                        <p class="sr-only">
                            {{ $t('auth.sign_in.form_captcha_refresh_sr') }}
                        </p>
                    </div>
                </div>
                <div class="mt-1">
                    <text-input id="captcha" :placeholder="$t('auth.sign_in.form_captcha_placeholder')" v-model="form.captcha"/>
                </div>
                <input-description class="mt-1" :is-error="props.errors?.captcha !== undefined" :message="props.errors?.captcha"/>
            </div>
            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <a href="#" class="font-medium text-gray-600 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300" target="_blank">
                        {{ $t('auth.sign_in.link_what_is_this') }}
                    </a>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300" target="_blank">
                        {{ $t('auth.sign_in.link_account_recovery') }}
                    </a>
                </div>
            </div>
            <div>
                <primary-button>
                    {{ $t('auth.sign_in.form_sign_in_button') }}
                </primary-button>
            </div>
        </form>
    </auth-layout>
</template>

<style scoped>
.icon-spin {
    animation: icon-spin 1s linear infinite;
    animation-direction: reverse;
}

@keyframes icon-spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.accent-text {
    text-decoration-line: underline;
    text-decoration-style: dotted;
    font-weight: 600;
}


</style>


