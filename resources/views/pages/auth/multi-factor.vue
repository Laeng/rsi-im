<script setup lang="ts">
import {useForm} from "@inertiajs/inertia-vue3";
import AuthLayout from "@/views/layouts/auth.vue";
import TextInput from "@/views/components/input/text-input.vue";
import InputLabel from "@/views/components/label/input-label.vue";
import PrimaryButton from "@/views/components/button/primary-button.vue";
import NativeSelect from "@/views/components/select/native-select.vue";
import {reactive, watchEffect} from "vue";
import ErrorAlert from "@/views/components/alert/error-alert.vue";
import InputDescription from "@/views/components/input/input-description.vue";

const props = defineProps({
    code: String,
    message: String,
    errors: Object,
});

const form = useForm({
    code: '',
    duration: 'session'
});

const alert = reactive({
    show: false,

});

const submit = () => {
    form.post(route('login.multi-factor'), {
        onFinish: () => {
            form.code = '';
            form.duration = 'session';
        },
    });
};

watchEffect(() => {
    if (props.code !== 'OK') {
        alert.show = true;
    }
});
</script>

<template>
    <auth-layout>
        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <error-alert v-show="alert.show" title="ERROR">
                    <ul>
                        <li>{{ props.message }}</li>
                    </ul>
                </error-alert>
            </div>
            <div>
                <input-label for="code" :value="$t('auth.multi_factor.form_code_label')"/>
                <div class="mt-1">
                    <text-input id="code" placeholder="code" v-model="form.code" required/>
                </div>
                <input-description class="mt-1" :is-error="props.errors?.code !== undefined" :message="props.errors?.code"/>
            </div>
            <div>
                <input-label for="duration" :value="$t('auth.multi_factor.form_duration_label')"/>
                <div class="mt-1">
                    <native-select v-model="form.duration">
                        <option value="session">{{ $t('auth.multi_factor.form_duration_options.session') }}</option>
                        <option value="day">{{ $t('auth.multi_factor.form_duration_options.day') }}</option>
                        <option value="week">{{ $t('auth.multi_factor.form_duration_options.week') }}</option>
                        <option value="month">{{ $t('auth.multi_factor.form_duration_options.month') }}</option>
                        <option value="year">{{ $t('auth.multi_factor.form_duration_options.year') }}</option>
                    </native-select>
                </div>
                <input-description class="mt-1" :is-error="props.errors?.duration !== undefined" :message="props.errors?.duration"/>
            </div>
            <div>
                <primary-button>
                    {{ $t('auth.multi_factor.form_authenticate_button') }}
                </primary-button>
            </div>
        </form>
    </auth-layout>
</template>

<style scoped>

</style>
