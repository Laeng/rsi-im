<script setup lang="ts">
import AppLayout from "@/views/layouts/app.vue";
import SecondaryButton from "@/views/components/button/secondary-button.vue";
import RsiImLogo from "@/views/components/logo/rsi-im.vue";
import LaengCoLogo from "@/views/components/logo/laeng-co.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';
import FooterLabel from "@/views/components/label/footer-label.vue";
</script>

<template>
    <app-layout>
        <template v-slot:sideMenu>

        </template>

        <template v-slot:aside>
            <div class="flex items-center h-full">
                <div class="space-y-6">
                    <rsi-im-logo class="fill-current h-12 w-auto" color-one="#6366f1" color-two="#72A1E0"/>
                    <div class="space-y-2">
                        <p class="text-gray-300">
                            본 서비스를 통해 Star Citizen 및 Squadron 42와 관련된 다양한 팬 메이드 서비스에서 여러분의 RSI 계정으로 간편하게 인증할 수 있습니다.
                        </p>
                    </div>
                    <footer-label class="text-gray-500 text-sm space-y-1"/>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Developed by</p>
                        <p>
                            <a href="https://github.com/laeng" target="_blank" rel="nofollow">
                                <laeng-co-logo class="h-6 w-auto fill-current text-gray-500 hover:text-rose-600"/>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:main>
            <div class="space-y-4">
                <div class="rounded-3xl bg-[#3062A0] dark:bg-[#1B2435] shadow-lg flex justify-between items-center p-8 space-x-4">
                    <div>
                        <h3 class="text-white font-bold text-xl">나의 데이터</h3>
                        <p class="text-white/60">
                            RSI.im에 저장된 데이터를 검토하고 서비스 이용 내역을 확인할 수 있습니다.
                        </p>
                    </div>
                    <div class="flex-none w-24">
                        <Link :href="route('connect.sign-in')">
                            <secondary-button type="button">
                                로그인
                            </secondary-button>
                        </Link>
                    </div>
                </div>
                <div class="rounded-3xl bg-[#3062A0] dark:bg-[#1B2435] shadow-lg flex justify-between items-center p-8 space-x-4">
                    <div>
                        <h3 class="text-white font-bold text-xl">커뮤니티</h3>
                        <p class="text-white/60">
                            RSI.im에 대한 여러분의 의견을 공유해 주십시오.
                        </p>
                    </div>
                    <div class="flex-none w-24">
                        <secondary-button type="button">
                            바로가기
                        </secondary-button>
                    </div>
                </div>
            </div>
        </template>

    </app-layout>
</template>
