<script setup lang="ts">
import {useForm} from "@inertiajs/inertia-vue3";
import TextInput from "@/views/components/input/text-input.vue";
import InputLabel from "@/views/components/label/input-label.vue";
import PrimaryButton from "@/views/components/button/primary-button.vue";
import NativeSelect from "@/views/components/select/native-select.vue";
import {reactive, watchEffect} from "vue";
import ErrorAlert from "@/views/components/alert/error-alert.vue";
const props = defineProps({
    code: String,
    message: String,
});

const form = useForm({
    code: '',
    duration: ''
});

const alert = reactive({
    show: false,

});

watchEffect(() => {
    if (props.code !== 'OK') {
        alert.show = true;
    }
});


</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <error-alert v-show="alert.show" title="Oops... (⊙_⊙;)">
                <ul>
                    <li>{{ props.message }}</li>
                </ul>
            </error-alert>
        </div>
        <div>
            <input-label for="code" value="Authentication Code"/>
            <div class="mt-1">
                <text-input id="code" placeholder="Code" v-model="form.code" required/>
            </div>
        </div>
        <div>
            <input-label for="duration" value="Trust this device for"/>
            <div class="mt-1">
                <native-select v-model="form.duration">
                    <option value="session">This session only</option>
                </native-select>
            </div>
        </div>
        <div>
            <primary-button>
                Authenticate
            </primary-button>
        </div>
    </form>
</template>

<style scoped>

</style>
