<script setup lang="ts">
    import AuthLayout from "@/views/layouts/auth.vue";
    import {useForm} from "@inertiajs/inertia-vue3";
    import PrimaryButton from "@/views/components/button/primary-button.vue";
    import SecondaryButton from "@/views/components/button/secondary-button.vue";
    import InfoAlert from "@/views/components/alert/info-alert.vue";
    import axios from "axios";

    const props = defineProps({
        client: {
            type: Object,
            default: {
                id: '',
                name: ''
            }
        },
        request: {
            type: Object,
            default: {
                state: ''
            }
        },
        authToken: String,
        scopes: Array
    });

    const form = useForm({
        state: props.request?.state,
        client_id: props.client?.id,
        auth_token: props.authToken
    });

    const approvePermission = () => {
        form.post(route('oauth2.authorization.approve'), {});
    };

    const denyPermission = () => {
        form.delete(route('oauth2.authorization.deny'), {});
    };

</script>


<template>
    <auth-layout>
        <div class="space-y-4">
            <info-alert title="Requesting permission"><b class="font-semibold">{{ props.client.name }}</b> is requesting permission to access your account.</info-alert>
            <div class="p-4 border border border-gray-300 bg-white rounded-md shadow-md dark:shadow-gray-600/50 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                <ul role="list" class="-my-4 divide-y divide-gray-300 dark:divide-gray-600">
                    <li v-for="scope in props.scopes" class="py-4">
                        <div class="relative">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">
                                {{ scope.id }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                {{ scope.description }}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-x-2 sm:space-y-0">
                <primary-button @click="approvePermission">Authorize</primary-button>
                <secondary-button @click="denyPermission">Cancel</secondary-button>
            </div>
        </div>
    </auth-layout>
</template>

<style scoped>

</style>
