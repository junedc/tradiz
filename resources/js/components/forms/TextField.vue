<template>
    <div class="space-y-2">
        <label
            v-if="label"
            :for="inputId"
            class="app-text-primary block text-sm font-semibold"
        >
            {{ label }}
        </label>

        <InputText
            :id="inputId"
            v-model="model"
            :name="name"
            :placeholder="placeholder"
            :disabled="disabled"
            :invalid="hasError"
            :data-testid="testId"
            class="w-full"
        />

        <p
            v-if="helperText && !errorMessage"
            class="app-text-muted text-sm"
        >
            {{ helperText }}
        </p>

        <p
            v-if="errorMessage"
            class="app-error-text text-sm font-medium"
        >
            {{ errorMessage }}
        </p>
    </div>
</template>

<script setup>
import { computed, useId } from 'vue';
import InputText from 'primevue/inputtext';

const model = defineModel({
    type: String,
    default: '',
});

const props = defineProps({
    id: {
        type: String,
        default: '',
    },
    name: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    helperText: {
        type: String,
        default: '',
    },
    errorMessage: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    testId: {
        type: String,
        default: '',
    },
});

const generatedId = useId();

const inputId = computed(() => props.id || `text-field-${generatedId}`);
const hasError = computed(() => Boolean(props.errorMessage));
</script>
