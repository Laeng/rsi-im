<script setup lang="ts">
import axios from 'axios'
import {useForm} from "@inertiajs/inertia-vue3";
import TextInput from "@/views/components/input/text-input.vue";
import InputLabel from "@/views/components/label/input-label.vue";
import PrimaryButton from "@/views/components/button/primary-button.vue";
import InfoAlert from "@/views/components/alert/info-alert.vue";
import ErrorAlert from "@/views/components/alert/error-alert.vue";
import {onMounted, reactive, ref, watchEffect} from "vue";

const props = defineProps({
    code: String,
    message: String,
});

const alert = reactive({
    show: false,

});

const captcha = reactive({
    show: false,
    src: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=',
    lock: false
});

let code:String|undefined;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const form = useForm({
    username: '',
    password: '',
    captcha: ''
});

const submit = () => {
    form.post(route('login.submit'), {
        onFinish: () => {
            form.reset('password');
            form.reset('captcha');

            code = '';
        },
    });
};


const refreshCaptcha = () => {
    if (!captcha.lock) {
        captcha.lock = true;
        axios.post(route('login.captcha'))
            .then((response) => {
                captcha.src = response.data.image;
            })
            .catch(() => {

            })
            .finally(() => {
                captcha.lock = false;
            });
    }
}

watchEffect(() => {
    if (code !== props.code) {
        if (props.code !== 'OK') {
            alert.show = true;
        }

        switch (props.code) {
            case 'ErrCaptchaRequiredLauncher':
            case 'ErrInvalidChallengeCode':
                captcha.show = true;
                refreshCaptcha();
                break;
            default:
                break;
        }
        code = props.code;
    }
});

</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <info-alert v-show="!alert.show" title="Don't forget!">
                <ul>
                    <li>For the safety of your RSI account, be sure to check the web address. The correct URL for this service is <strong class="font-semibold underline decoration-dotted">rsi.im</strong>.</li>
                </ul>
            </info-alert>
            <error-alert v-show="alert.show" title="Oops... (⊙_⊙;)">
                <ul>
                    <li>{{ props.message }}</li>
                </ul>
            </error-alert>
        </div>
        <div>
            <input-label for="email" value="Email"/>
            <div class="mt-1">
                <text-input name="email" id="email" placeholder="someone@example.com" v-model="form.username" required autofocus/>
            </div>
        </div>
        <div>
            <input-label for="password" value="Password"/>
            <div class="mt-1">
                <text-input name="password" id="password" type="password" placeholder="••••••••••••••••" v-model="form.password" required/>
            </div>
        </div>
        <div v-show="captcha.show">
            <input-label for="captcha" value="Captcha"/>
            <div class="mt-1 relative">
                <div class="bg-black rounded-md w-full select-none flex justify-center">
                    <img class="h-28" :src="captcha.src" alt="captcha"/>
                </div>
                <div class="text-gray-500 hover:text-gray-300 md:text-sm absolute bottom-1 right-2 cursor-pointer" @click="refreshCaptcha" v-show="!captcha.lock">Refresh</div>
            </div>
            <div class="mt-1">
                <text-input name="captcha" id="captcha" placeholder="4 digits numbers" v-model="form.captcha"/>
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
