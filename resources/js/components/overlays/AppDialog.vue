<template>
    <Dialog
        v-model:visible="visible"
        :header="title"
        :modal="modal"
        :dismissable-mask="dismissableMask"
        :draggable="draggable"
        :closable="closable"
        :style="dialogStyle"
        :breakpoints="resolvedBreakpoints"
        class="app-dialog"
    >
        <div
            v-if="description"
            class="app-text-secondary mb-5 text-sm leading-6"
        >
            {{ description }}
        </div>

        <div class="space-y-4">
            <slot />
        </div>

        <template
            v-if="$slots.footer || showDefaultFooter"
            #footer
        >
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <slot name="footer">
                    <Button
                        v-if="showCancelButton"
                        type="button"
                        :label="cancelLabel"
                        severity="secondary"
                        outlined
                        @click="closeDialog"
                    />
                    <Button
                        v-if="showConfirmButton"
                        type="button"
                        :label="confirmLabel"
                        @click="$emit('confirm')"
                    />
                </slot>
            </div>
        </template>
    </Dialog>
</template>

<script setup>
import { computed } from 'vue';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';

const visible = defineModel('visible', {
    type: Boolean,
    default: false,
});

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    description: {
        type: String,
        default: '',
    },
    width: {
        type: String,
        default: '42rem',
    },
    breakpoints: {
        type: Object,
        default: () => ({
            '1199px': '70vw',
            '767px': '92vw',
        }),
    },
    modal: {
        type: Boolean,
        default: true,
    },
    dismissableMask: {
        type: Boolean,
        default: true,
    },
    draggable: {
        type: Boolean,
        default: false,
    },
    closable: {
        type: Boolean,
        default: true,
    },
    showCancelButton: {
        type: Boolean,
        default: true,
    },
    showConfirmButton: {
        type: Boolean,
        default: true,
    },
    cancelLabel: {
        type: String,
        default: 'Cancel',
    },
    confirmLabel: {
        type: String,
        default: 'Save',
    },
});

defineEmits(['confirm']);

const dialogStyle = computed(() => ({
    width: props.width,
}));

const resolvedBreakpoints = computed(() => props.breakpoints);
const showDefaultFooter = computed(() => props.showCancelButton || props.showConfirmButton);

function closeDialog() {
    visible.value = false;
}
</script>
