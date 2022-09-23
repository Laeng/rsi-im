<script lang="ts" setup>
import AppLayout from "@/views/layouts/app.vue";
import RsiImLogo from "@/views/components/logo/rsi-im.vue";
import {
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    UsersIcon,
    LogoutIcon
} from "@heroicons/vue/solid";
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import {computed} from "vue";

const props = defineProps({
    page: String
});

const user = computed(() => usePage().props.value.user);

const navigation = [
    { name: 'index', href: route('user.index'), icon: HomeIcon},
    { name: 'account', href: route('user.account'), icon: UsersIcon },
    { name: 'device', href: route('user.device.index'), icon: FolderIcon},
    { name: 'log', href: route('user.log.index'), icon: CalendarIcon },
    { name: 'sign_out', href: route('disconnect.sign-out'), icon: LogoutIcon}
]
</script>

<template>
    <app-layout>
        <template v-slot:sideMenu>
            <div class="flex flex-shrink-0 items-center px-4">
                <rsi-im-logo class="fill-current h-8 w-auto" color-one="#6366f1" color-two="#72A1E0"/>
            </div>
            <div class="mt-5 h-0 flex-1 overflow-y-auto mx-4">
                <nav class="space-y-2 mb-auto">
                    <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[props.page === item.name ? 'bg-black/30 text-white' : 'text-gray-300 hover:bg-black/20', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                        <component :is="item.icon" class="mr-3 h-6 w-6 flex-shrink-0 text-gray-200" aria-hidden="true" />
                        {{ $t(`user.${item.name}.title`) }}
                    </Link>
                </nav>
            </div>
            <div class="mt-auto flex flex-shrink-0 bg-black/30 rounded-md p-4 mx-4">
                <a href="#" class="group block w-full flex-shrink-0">
                    <div class="flex items-center">
                        <div>
                            <img class="inline-block h-9 w-9 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">Tom Cook</p>
                            <p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">View profile</p>
                        </div>
                    </div>
                </a>
            </div>
        </template>
        <template v-slot:aside>
            <div>
                <rsi-im-logo class="fill-current h-8 w-auto mb-8" color-one="#6366f1" color-two="#72A1E0"/>
            </div>
            <nav class="space-y-2 mb-auto">
                <Link v-for="item in navigation" :key="item.name" :href="item.href"
                   :class="[props.page === item.name ? 'bg-black/30 text-white' : 'text-gray-300 hover:bg-black/20', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                    <component :is="item.icon" class="mr-3 h-6 w-6 flex-shrink-0 text-gray-200" aria-hidden="true"/>
                    {{ $t(`user.${item.name}.title`) }}
                </Link>
            </nav>
            <div class="flex flex-shrink-0 bg-black/30 rounded-md p-4">
                <a href="https://robertsspaceindustries.com/connect?jumpto=/account/profile" class="group block w-full flex-shrink-0" target="_blank">
                    <div class="flex items-center">
                        <div>
                            <img class="inline-block h-9 w-9 rounded-full"
                                 :src="user.avatar ?? 'https://robertsspaceindustries.com/rsi/static/tavern/d761352b9e3c2bf075630b791c4aca28.jpg'"
                                 alt="profile image"/>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ user.nickname }}</p>
                            <p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">
                                {{ $t('common.text_edit_profile')}}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </template>

        <template v-slot:main>
            <slot/>
        </template>



    </app-layout>
</template>

<style scoped>

</style>
