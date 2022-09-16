<script lang="ts" setup>
import UserLayout from "@/views/layouts/user.vue";
import DefaultHeading from "@/views/components/heading/default-heading.vue";
import {RefreshIcon, TrashIcon, PaperClipIcon} from "@heroicons/vue/solid";
import RedButton from "@/views/components/button/red-button.vue";
import axios from "axios";
import {reactive} from "vue";

const props = defineProps({
    data: {
        type: Object,
        default: {}
    }
});

const data = reactive({
    deleteData: {
        loading: false
    }
});

const deleteData = () => {
    if (data.deleteData.loading) return;

    data.deleteData.loading = true;

    axios.delete(route('user.data'))
        .then((response) => {
            location.href = route('welcome');
        })
        .catch(() => {

        })
        .finally(() => {
            data.deleteData.loading = false;
        });
}

</script>

<template>
    <user-layout page="account">
        <default-heading>
            <template v-slot:title>
                {{ $t('user.account.title') }}
            </template>
            <template v-slot:buttons>
            </template>
        </default-heading>
        <div class="mt-4 border border-gray-300 dark:border-gray-700 bg-gray-200 bg-white dark:bg-[#1B2435] rounded-md">
            <dl class="divide-y sm:divide-gray-300 dark:divide-gray-700">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ $t('user.account.dl_profile_username_label') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ props.data.data.username }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ $t('user.account.dl_profile_nickname_label') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ props.data.data.nickname }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ $t('user.account.dl_profile_display_name_label') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ props.data.data.displayname }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ $t('user.account.dl_profile_avatar_label') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <img class="w-20 h-20 rounded-md"
                             :src="props.data.data.avatar ?? 'https://robertsspaceindustries.com/rsi/static/tavern/d761352b9e3c2bf075630b791c4aca28.jpg'"
                             alt="avatar"
                        />
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ $t('user.account.dl_profile_badges_label') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <div class="grid grid-cols-1 lg:grid-cols-2">
                            <p v-for="badge in props.data.data.badges">
                                {{ badge }}
                            </p>
                        </div>
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ $t('user.account.dl_profile_organizations_label') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <ul class="space-x-4">
                            <template v-if="props.data.data.organizations > 0">
                                <li v-for="organization in props.data.data.organizations">
                                    <a class="relative" :href="`https://robertsspaceindustries.com/orgs/${organization.slug}`" target="_blank">
                                        <img
                                            class="w-full h-auto rounded-md"
                                            :src="organization.banner ?? 'https://robertsspaceindustries.com/rsi/static/tavern/d761352b9e3c2bf075630b791c4aca28.jpg'"
                                            alt="organization avatar"
                                        />
                                        <p class="absolute right-1 bottom-1 text-right p-2 rounded bg-black/50 rounded-md text-white font-medium text-sm">
                                            {{ organization.name }}
                                        </p>
                                    </a>
                                </li>
                            </template>
                            <template v-else>
                                {{ $t('user.account.dl_profile_organizations_empty_label') }}
                            </template>
                        </ul>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="mt-4 border border-gray-300 dark:border-gray-700 bg-gray-200 bg-white dark:bg-[#1B2435] rounded-md">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 dark:text-white">
                    {{ $t('user.account.div_delete_account_title_label') }}
                </h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>{{ $t('user.account.div_delete_account_description_label') }}</p>
                </div>
                <div class="mt-5">
                    <red-button type="button" @click="deleteData()">
                        <span class="mr-1">
                            <RefreshIcon class="h-4 w-4 icon-spin" v-show="data.deleteData.loading"/>
                            <TrashIcon class="h-4 w-4" v-show="!data.deleteData.loading"/>
                        </span>
                        {{ $t('user.account.div_delete_account_button') }}
                    </red-button>
                </div>
            </div>
        </div>
    </user-layout>
</template>

<style scoped>

</style>
