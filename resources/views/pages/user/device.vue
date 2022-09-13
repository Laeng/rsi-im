<script lang="ts" setup>
import UserLayout from "@/views/layouts/user.vue";
import DefaultHeading from "@/views/components/heading/default-heading.vue";
import {computed, reactive, ref} from 'vue';
import SecondaryButton from "@/views/components/button/secondary-button.vue";
import axios from "axios";
import { DateTime } from 'luxon';
import DefaultTable from "@/views/components/table/default-table.vue";
import {RefreshIcon, TrashIcon} from "@heroicons/vue/solid";
import DefaultTooltip from "@/views/components/tooltip/default-table.vue";


const data = reactive({
    table: {
        current_page: 1,
        data: [],
        first_page_url: '',
        from: 0,
        last_page: 1,
        last_page_url: '',
        links: [],
        next_page_url: '',
        path: '',
        per_page: 0,
        prev_page_url: '',
        to: 0,
        total: 0
    },
    deleteDevice: {
        loading: false
    }
});

const selected = ref([]);
const checked = ref(false);
const indeterminate = computed(() => selected.value.length > 0 && selected.value.length < data.table.data.length);

const getData = (pages: number) => {
    pages = pages < 1 ? 1 : pages;

    axios.get(`${route('user.device.data')}?pages=${pages}`)
        .then((response) => {
            data.table = response.data;
        })
        .catch(() => {

        });
}

const deleteDevice = (ids: Array<any>) => {
    if (data.deleteDevice.loading && selected.value.length <= 0) return;

    data.deleteDevice.loading = true;

    let query = '';
    ids.map((value, index, array) => {
       query += `ids[]=${value}`;
    });

    axios.delete(`${route('user.device.data')}?${query}`)
        .then((response) => {
            getData(data.table.current_page);
            selected.value.splice(0, selected.value.length);
        })
        .catch(() => {

        })
        .finally(() => {
            data.deleteDevice.loading = false;
        });
};

const convertDateTime = (isoFormat: string) => {
    const now = DateTime.now().toUTC();
    const dt = DateTime.fromISO(isoFormat).toUTC();

    console.log(now.diff(dt, 'hours').hours);


    if (now.diff(dt, 'hours').hours > 24) {
        return dt.toLocal().toLocaleString(DateTime.DATE_SHORT);
    } else {
        return dt.toLocal().toLocaleString(DateTime.TIME_24_SIMPLE)
    }
}

const getDateTime = (isoFormat: string) => {
    return DateTime.fromISO(isoFormat).toUTC().toLocal().toLocaleString(DateTime.DATETIME_MED);
}

getData(1);


</script>

<template>
    <user-layout page="device">
        <default-heading>
            <template v-slot:title>
                {{ $t('user.device.title') }}
            </template>
            <template v-slot:buttons>
            </template>
        </default-heading>

        <default-table class="mt-4">
            <template v-slot:head>
                <tr>
                    <th scope="col" class="relative py-3.5 px-1.5 hidden lg:table-cell">
                        <input type="checkbox"
                               class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               :checked="indeterminate || selected.length === data.table.data.length"
                               :indeterminate="indeterminate" @change="selected = $event.target.checked ? data.table.data.map((p) => p.id) : []"
                        />
                    </th>
                    <th scope="col" class="py-3.5 px-1.5 uppercase tracking-wider whitespace-nowrap tabular-nums hidden lg:table-cell">
                        {{ $t('user.device.table_id_label') }}
                    </th>
                    <th scope="col" class="py-3.5 px-1.5 uppercase tracking-wider whitespace-nowrap tabular-nums hidden lg:table-cell">
                        {{ $t('user.device.table_ip_label') }}
                    </th>
                    <th scope="col" class="py-3.5 px-1.5 uppercase tracking-wider whitespace-nowrap tabular-nums hidden lg:table-cell">
                        {{ $t('user.device.table_expired_date_label') }}
                    </th>
                    <th scope="col" class="py-3.5 px-1.5 uppercase tracking-wider whitespace-nowrap tabular-nums hidden lg:table-cell">
                        {{ $t('user.device.table_latest_date_label') }}
                    </th>
                </tr>
            </template>
            <template v-slot:body>
                <tr v-for="data in data.table.data" :key="data.id" :class="[selected.includes(data.id) && 'bg-dark/20']">
                    <td class="relative w-12 px-1.5">
                        <div v-if="selected.includes(data.id)" class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"></div>
                        <input type="checkbox" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" :value="data.id" v-model="selected" />
                    </td>
                    <td :class="['whitespace-nowrap  px-1.5 py-4', selected.includes(data.id) ? 'text-indigo-600' : '']">
                        {{ data.id }}
                        <dl class="font-normal lg:hidden">
                            <dt class="">{{ $t('user.device.table_ip_label') }}</dt>
                            <dd class="mt-1 truncate text-gray-700">{{ data.ip }}</dd>
                            <dt class="">{{ $t('user.device.table_expired_date_label') }}</dt>
                            <dd class="mt-1 truncate text-gray-500">{{ DateTime.fromISO(data.created_at).toLocaleString(DateTime.DATETIME_MED) }}</dd>
                            <dt class="">{{ $t('user.device.table_latest_date_label') }}</dt>
                            <dd class="mt-1 truncate text-gray-500">{{ DateTime.fromISO(data.updated_at).toLocaleString(DateTime.DATETIME_MED) }}</dd>
                        </dl>
                    </td>
                    <td class="whitespace-nowrap px-1.5 py-4 whitespace-nowrap tabular-nums hidden lg:table-cell">
                        {{ data.ip }}
                    </td>
                    <td class="whitespace-nowrap px-1.5 py-4 whitespace-nowrap tabular-nums hidden lg:table-cell">
                        <div class="flex">
                            <default-tooltip :message="getDateTime(data.created_at)" :offset="4">
                                {{ convertDateTime(data.created_at) }}
                            </default-tooltip>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-1.5 py-4 whitespace-nowrap tabular-nums hidden lg:table-cell">
                        <div class="flex">
                            <default-tooltip :message="getDateTime(data.updated_at)" :offset="4">
                                {{ convertDateTime(data.updated_at) }}
                            </default-tooltip>
                        </div>
                    </td>
                </tr>
            </template>
            <template v-slot:paginateMessage>
                <p class="hidden lg:block text-sm text-gray-700 dark:text-gray-300">
                    {{ $t('common.table.table_paginate_message_label', {from: data.table.from, to: data.table.to, total: data.table.total})}}
                </p>
            </template>
            <template v-slot:paginateButton>
                <secondary-button type="button"
                                  :disabled="data.table.current_page <= 1"
                                  @click="getData(data.table.current_page - 1)"
                >
                    {{ $t('common.table.table_previous_button') }}
                </secondary-button>
                <p class="flex items-center md:block md:hidden text-gray-700 dark:text-gray-300 font-medium">
                    {{ data.table.current_page }}/{{ data.table.last_page }}
                </p>
                <secondary-button type="button"
                                  :disabled="data.table.current_page >= data.table.last_page "
                                  @click="getData(data.table.current_page + 1)"
                >
                    {{ $t('common.table.table_next_button') }}
                </secondary-button>
            </template>
        </default-table>

        <div class="flex space-x-2 mt-4">
            <secondary-button type="button" @click="deleteDevice(selected)" :disabled="data.deleteDevice.loading">
                <span class="mr-1">
                    <RefreshIcon class="h-4 w-4 icon-spin" v-show="data.deleteDevice.loading"/>
                    <TrashIcon class="h-4 w-4" v-show="!data.deleteDevice.loading"/>
                </span>
                삭제
            </secondary-button>
        </div>

    </user-layout>
</template>

<style scoped>

</style>
