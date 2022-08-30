<script lang="ts" setup>
import {reactive} from 'vue';

const props = defineProps({
    status: Number
});

const page = reactive({
    title: () => {
        switch (props.status) {
            case 503: return '503: Service Unavailable';
            case 500: return '500: Server Error';
            case 404: return '404: Page Not Found';
            case 403: return '403: Forbidden';
            default: return '';
        }
    },
    description: () => {
        switch (props.status) {
            case 503: return 'Sorry, we are doing some maintenance. Please check back soon.';
            case 500: return 'Whoops, something went wrong on our servers.';
            case 404: return 'Sorry, the page you are looking for could not be found.';
            case 403: return 'Sorry, you are forbidden from accessing this page.';
            default: return '';
        }
    }
})
</script>
<template>
    <div>
        <H1>{{ page.title }}</H1>
        <div>{{ page.description }}</div>
    </div>
</template>
<style scoped>

</style>
