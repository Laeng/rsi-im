<script lang="ts" setup>
import AppLayout from "@/views/layouts/app.vue";
import RsiImLogo from "@/views/components/logo/rsi-im.vue";
import {CalendarIcon, ChartBarIcon, FolderIcon, HomeIcon, InboxIcon, UsersIcon} from "@heroicons/vue/solid";
const navigation = [
    { name: 'Dashboard', href: '#', icon: HomeIcon, current: true },
    { name: 'Team', href: '#', icon: UsersIcon, current: false },
    { name: 'Projects', href: '#', icon: FolderIcon, current: false },
    { name: 'Calendar', href: '#', icon: CalendarIcon, current: false },
    { name: 'Documents', href: '#', icon: InboxIcon, current: false },
    { name: 'Reports', href: '#', icon: ChartBarIcon, current: false },
]
const userNavigation = [
    { name: 'Your Profile', href: '#' },
    { name: 'Settings', href: '#' },
    { name: 'Sign out', href: '#' },
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
                    <a v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'bg-black/30 text-white' : 'text-gray-300 hover:bg-black/20', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                        <component :is="item.icon" class="mr-3 h-6 w-6 flex-shrink-0 text-gray-200" aria-hidden="true" />
                        {{ item.name }}
                    </a>
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
                <a v-for="item in navigation" :key="item.name" :href="item.href"
                   :class="[item.current ? 'bg-black/30 text-white' : 'text-gray-300 hover:bg-black/20', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                    <component :is="item.icon" class="mr-3 h-6 w-6 flex-shrink-0 text-gray-200" aria-hidden="true"/>
                    {{ item.name }}
                </a>
            </nav>
            <div class="flex flex-shrink-0 bg-black/30 rounded-md p-4">
                <a href="#" class="group block w-full flex-shrink-0">
                    <div class="flex items-center">
                        <div>
                            <img class="inline-block h-9 w-9 rounded-full"
                                 src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                 alt=""/>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">Tom Cook</p>
                            <p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">View profile</p>
                        </div>
                    </div>
                </a>
            </div>
        </template>

        <template v-slot:main>
            <div class="">
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
            </div>
            <div class="">
                <!-- Replace with your content -->
                <div class="py-4">
                    <slot/>
                    <div class="h-96 rounded-lg border-4 border-dashed border-gray-200" />
                </div>
                <!-- /End replace -->
            </div>
        </template>



    </app-layout>
</template>

<style scoped>

</style>
