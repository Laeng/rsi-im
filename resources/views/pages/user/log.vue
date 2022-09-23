<script lang="ts" setup>
import UserLayout from "@/views/layouts/user.vue";
import DefaultHeading from "@/views/components/heading/default-heading.vue";
import DefaultTable from "@/views/components/table/default-table.vue";
import DefaultTooltip from "@/views/components/tooltip/default-table.vue";
import SecondaryButton from "@/views/components/button/secondary-button.vue";
import axios from "axios";
import {reactive} from "vue";
import {DateTime} from "luxon";

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
    }
});

const getData = (pages: number) => {
    pages = pages < 1 ? 1 : pages;

    axios.get(`${route('user.log.data')}?pages=${pages}`)
        .then((response) => {
            data.table = response.data;
        })
        .catch(() => {

        });
}

const getSimpleDateTime = (isoFormat: string) => {
    return DateTime.fromISO(isoFormat).toUTC().toLocal().toLocaleString(DateTime.DATE_SHORT);
}

const getDetailDateTime = (isoFormat: string) => {
    return DateTime.fromISO(isoFormat).toUTC().toLocal().toLocaleString(DateTime.DATETIME_MED);
}

getData(data.table.current_page);
setInterval(() => {
    getData(data.table.current_page)
}, 5000);

</script>

<template>
    <user-layout page="log">
        <default-heading>
            <template v-slot:title>
                {{ $t('user.log.title') }}
            </template>
            <template v-slot:buttons>
            </template>
        </default-heading>
        <default-table class="mt-4">
            <template v-slot:head>
                <tr>
                    <th scope="col"
                        class="py-3.5 pl-3.5 pr-1.5 uppercase tracking-wider whitespace-nowrap tabular-nums hidden lg:table-cell"
                    >
                        {{ $t('user.log.table_type_label') }}
                    </th>
                    <th scope="col"
                        class="py-3.5 px-1.5 uppercase tracking-wider whitespace-nowrap tabular-nums hidden lg:table-cell"
                    >
                        {{ $t('user.log.table_ip_label') }}
                    </th>
                    <th scope="col"
                        class="py-3.5 px-1.5 uppercase tracking-wider whitespace-nowrap tabular-nums hidden lg:table-cell"
                    >
                        {{ $t('user.log.table_created_date_label') }}
                    </th>
                </tr>
            </template>
            <template v-slot:body>
                <tr v-for="data in data.table.data" :key="data.id">
                    <td class="whitespace-nowrap  pl-3.5 pr-1.5 py-4">
                        {{ $t(`user.log.table_types_label.${data.type}`) }}
                        <dl class="font-normal lg:hidden">
                            <dt class="">{{ $t('user.device.table_ip_label') }}</dt>
                            <dd class="mt-1 truncate text-gray-700">{{ data.ip }}</dd>
                            <dt class="">{{ $t('user.device.table_expired_date_label') }}</dt>
                            <dd class="mt-1 truncate text-gray-500">{{ getDetailDateTime(data.created_at) }}</dd>
                        </dl>
                    </td>
                    <td class="whitespace-nowrap px-1.5 py-4 whitespace-nowrap tabular-nums hidden lg:table-cell">
                        {{ data.ip }}
                    </td>
                    <td class="whitespace-nowrap px-1.5 py-4 whitespace-nowrap tabular-nums hidden lg:table-cell">
                        <div class="flex">
                            <default-tooltip :message="getDetailDateTime(data.created_at)" :offset="4">
                                {{ getSimpleDateTime(data.created_at) }}
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
    </user-layout>
</template>

<style scoped>

</style>
