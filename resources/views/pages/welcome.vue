<script setup lang="ts">
import AppLayout from "@/views/layouts/app.vue";
import SecondaryButton from "@/views/components/button/secondary-button.vue";
import RsiImLogo from "@/views/components/logo/rsi-im.vue";
import LaengCoLogo from "@/views/components/logo/laeng-co.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';
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
                        <ul class="text-gray-500 text-sm">
                            <li>RSI.im은 Cloud Imperium 또는 Roberts Space Industries 그룹 자회사에서 보증과 제휴 하지 않은 제3자 비영리 서비스입니다.</li>
                            <li>Star Citizen®, Squadron 42®, Roberts Space Industries® 및 Cloud Imperium®은 Cloud Imperium Rights LLC의 등록 상표이며 모든 권리를 보유하고 있습니다.</li>
                        </ul>
                        <div class="flex space-x-2 text-gray-500 text-sm">
                            <p>개인정보취급방침</p>
                            <div class="flex space-x-1 items-center">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
                                    <!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                    <path d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/>
                                </svg>
                                <p>GitHub</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Developed by</p>
                        <a href="https://github.com/laeng" target="_blank" rel="nofollow"><laeng-co-logo class="h-6 w-auto fill-current text-gray-500 hover:text-rose-600"/></a>
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:main>
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
        </template>

    </app-layout>
</template>
