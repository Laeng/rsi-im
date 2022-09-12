<script lang="ts" setup>
import UserLayout from "@/views/layouts/user.vue";

const props = defineProps({
    data: Object
});

</script>

<template>
    <user-layout page="device">
        <div>
            <h1 class="text-xl font-semibold">{{ $t('user.device.title') }}</h1>
            <p class="mt-2 text-sm">{{ $t('user.device.description') }}</p>
        </div>

        <div>
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Title</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">Email</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="data in props.data" :key="data.id">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ data.hash }}</td>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ data.ip }}</td>
                    <td class="hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 sm:table-cell">{{ data.expired_at }}</td>
                    <td class="hidden whitespace-nowrap px-3 py-4 text-sm text-gray-500 lg:table-cell">{{ data.lasted_at }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ data.created_at }}</td>
                    <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900"
                        >Edit<span class="sr-only">, {{ data.id }}</span></a
                        >
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </user-layout>
</template>

<style scoped>

</style>
