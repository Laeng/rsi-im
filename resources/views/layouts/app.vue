<script lang="ts" setup>
import { ref } from 'vue'
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    MenuIcon,
    XIcon,
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    UsersIcon,
} from "@heroicons/vue/solid";
import RsiImLogo from "@/views/components/logo/rsi-im.vue";
import FooterLabel from "@/views/components/label/footer-label.vue";


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

const sidebarOpen = ref(false)
</script>


<template>
    <TransitionRoot as="template" :show="sidebarOpen">
        <Dialog as="div" class="relative z-40 lg:hidden" @close="sidebarOpen = false">
            <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
            </TransitionChild>

            <div class="fixed inset-0 z-40 flex">
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                    <DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-[#273247] dark:bg-[#1B2435] pt-5 pb-4">
                        <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                                    <span class="sr-only">Close sidebar</span>
                                    <XIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                </button>
                            </div>
                        </TransitionChild>
                        <slot name="sideMenu"/>
                    </DialogPanel>
                </TransitionChild>
                <div class="w-14 flex-shrink-0" aria-hidden="true">
                    <!-- Dummy element to force sidebar to shrink to fit close icon -->
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <div class="grid grid-cols-1 lg:grid-cols-5">
        <aside class="hidden lg:col-span-2 lg:flex lg:justify-end lg:h-screen lg:shadow-2xl bg-[#273247] dark:bg-[#1B2435]">
            <div class="w-full xl:w-2/3 2xl:w-1/2 flex flex-col p-8" style="max-width: 400px">
                <slot name="aside"/>
            </div>
        </aside>
        <div class="lg:col-span-3 block lg:flex lg:h-screen lg:justify-start lg:overflow-auto lg:grid lg:grid-cols-3">
            <div class="lg:col-span-3 xl:col-span-2">
                <div class="flex flex-col h-screen">
                    <header class="sticky lg:hidden top-0 z-10 flex justify-between h-16 flex-shrink-0 bg-[#273247] dark:bg-[#1B2435] shadow">
                        <div class="flex items-center px-4">
                            <rsi-im-logo class="fill-current h-6 w-auto" color-one="#6366f1" color-two="#72A1E0"/>
                        </div>
                        <button type="button" class="border-l border-gray-500 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white/40 lg:hidden" @click="sidebarOpen = true">
                            <span class="sr-only">Open sidebar</span>
                            <MenuIcon class="h-6 w-6" aria-hidden="true"></MenuIcon>
                        </button>
                    </header>

                    <main class="grow p-4 sm:p-6 md:p-8">
                        <slot name="main"/>
                    </main>
                    <footer class="p-4 sm:p-6 md:p-8">
                        <footer-label class="text-gray-500 text-sm space-y-1"/>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
