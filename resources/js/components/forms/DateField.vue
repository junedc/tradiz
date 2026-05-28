<template>
    <div class="space-y-2">
        <label
            v-if="label"
            :for="inputId"
            class="app-text-primary block text-sm font-semibold"
        >
            {{ label }}
        </label>

        <DatePicker
            :id="inputId"
            v-model="model"
            :name="name"
            :placeholder="placeholder"
            :disabled="disabled"
            :invalid="hasError"
            :data-testid="testId"
            :show-icon="showIcon"
            date-format="dd/mm/yy"
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
import DatePicker from 'primevue/datepicker';

const model = defineModel({
    type: [Date, null],
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
        default: 'Select a date',
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
    showIcon: {
        type: Boolean,
        default: true,
    },
    testId: {
        type: String,
        default: '',
    },
});

const generatedId = useId();

const inputId = computed(() => props.id || `date-field-${generatedId}`);
const hasError = computed(() => Boolean(props.errorMessage));
</script>
