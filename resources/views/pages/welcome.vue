<script setup lang="ts">
import WelcomeLayout from "@/views/layouts/welcome.vue";
import SecondaryButton from "@/views/components/button/secondary-button.vue";
</script>

<template>
    <welcome-layout>
        <div class="w-full xl:w-4/5 2xl:w-2/3 space-y-6 lg:p-16">
            <div class="rounded-3xl bg-[#3062A0] dark:bg-[#1B2435] shadow-lg flex justify-between items-center p-8 space-x-4">
                <div>
                    <h3 class="text-white font-bold text-xl">나의 데이터</h3>
                    <p class="text-white/60">
                        RSI.im에 저장된 데이터를 검토하고 서비스 이용 내역을 확인할 수 있습니다.
                    </p>
                </div>
                <div class="flex-none w-24">
                    <secondary-button type="button">
                        로그인
                    </secondary-button>
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
    </welcome-layout>
</template>
