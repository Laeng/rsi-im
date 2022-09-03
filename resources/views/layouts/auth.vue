<script setup lang="ts">
import AuthHeader from "@/views/components/auth/auth-header.vue";
import AuthFooter from "@/views/components/auth/auth-footer.vue";
import BasicLayout from "@/views/layouts/basic.vue";
import LocaleSelect from "@/views/components/select/locale-select.vue";

</script>

<template>
    <basic-layout>
        <div class="flex flex-col h-screen">
            <header class="w-full">
                <div class="max-w-3xl mx-auto p-4 sm:px-6 lg:px-8">
                    <div class="flex justify-end">
                        <div>
                            <locale-select/>
                        </div>
                    </div>
                </div>
            </header>
            <main class="h-full grow grid place-items-start">
                <div class="w-full">
                    <div class="max-w-3xl mx-auto p-4 sm:px-6 lg:px-8">
                        <auth-header/>
                        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                            <slot/>
                        </div>
                        <auth-footer/>
                    </div>
                </div>
            </main>
        </div>
    </basic-layout>
</template>



