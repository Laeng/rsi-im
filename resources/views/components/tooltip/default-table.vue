<script lang="ts" setup>
import {onMounted, ref} from "vue";
import Tooltip from "@/scripts/tooltip";
import {Placement} from "@floating-ui/dom";

const props = defineProps({
    message: {
        type: String,
        required: true
    },
    placement: {
        type: String,
        default: 'top'
    },
    offset: {
        type:Number,
        default: 0
    }
});

const ttTarget = ref<HTMLElement|null>(null);
const ttTrigger = ref<HTMLElement|null>(null);

onMounted(() => {
    new Tooltip(
        ttTarget.value as HTMLElement,
        ttTrigger.value as HTMLElement,
        props.offset,
        props.placement as Placement
    );
});
</script>

<template>
    <div class="relative">
        <div ref="ttTrigger">
            <slot/>
        </div>
        <div ref="ttTarget" class="tooltip absolute hidden inline-block z-10 py-2 px-3 bg-black whitespace-nowrap">
            <p class="text-sm text-gray-100">{{ props.message }}</p>
            <div class="tooltip-arrow bg-black"></div>
        </div>
    </div>
</template>

<style scoped>
.tooltip-arrow,
.tooltip-arrow::before {
    position: absolute;
    width: 8px;
    height: 8px;
    transform: rotate(45deg);
    z-index: -1;
}

.tooltip-arrow::before {
    content: '';
    transform: rotate(45deg);
}

.tooltip[data-popper-placement^='top'] > .tooltip-arrow {
    bottom: -4px;
}

.tooltip[data-popper-placement^='bottom'] > .tooltip-arrow {
    top: -4px;
}

.tooltip[data-popper-placement^='left'] > .tooltip-arrow {
    right: -4px;
}

.tooltip[data-popper-placement^='right'] > .tooltip-arrow {
    left: -4px;
}
</style>
