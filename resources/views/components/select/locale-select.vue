<script lang="ts" setup>
import NativeSelect from "@/views/components/select/native-select.vue";
</script>

<template>
    <native-select v-model="$i18n.locale">
        <option v-for="locale in $i18n.availableLocales" :key="`locale-${locale}`" :value="locale">{{ locale.toUpperCase() }}</option>
    </native-select>
</template>


<style scoped>

</style>
