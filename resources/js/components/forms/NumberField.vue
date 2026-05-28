<template>
    <div class="space-y-2">
        <label
            v-if="label"
            :for="inputId"
            class="app-text-primary block text-sm font-semibold"
        >
            {{ label }}
        </label>

        <InputNumber
            :id="inputId"
            v-model="model"
            :name="name"
            :placeholder="placeholder"
            :disabled="disabled"
            :invalid="hasError"
            :data-testid="testId"
            :min="min"
            :max="max"
            :min-fraction-digits="minFractionDigits"
            :max-fraction-digits="maxFractionDigits"
            :mode="mode"
            :currency="currency"
            fluid
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
import InputNumber from 'primevue/inputnumber';

const model = defineModel({
    type: Number,
    default: null,
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
    min: {
        type: Number,
        default: null,
    },
    max: {
        type: Number,
        default: null,
    },
    minFractionDigits: {
        type: Number,
        default: 0,
    },
    maxFractionDigits: {
        type: Number,
        default: 2,
    },
    mode: {
        type: String,
        default: 'decimal',
    },
    currency: {
        type: String,
        default: 'AUD',
    },
    testId: {
        type: String,
        default: '',
    },
});

const generatedId = useId();

const inputId = computed(() => props.id || `number-field-${generatedId}`);
const hasError = computed(() => Boolean(props.errorMessage));
</script>
