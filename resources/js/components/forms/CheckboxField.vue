<template>
    <div class="space-y-2">
        <div class="flex items-start gap-3 rounded-2xl border px-4 py-3 transition" :class="wrapperClasses">
            <Checkbox
                :input-id="inputId"
                v-model="model"
                :name="name"
                binary
                :disabled="disabled"
                :invalid="hasError"
                :data-testid="testId"
            />

            <div class="space-y-1">
                <label
                    :for="inputId"
                    class="app-text-primary block text-sm font-semibold"
                >
                    {{ label }}
                </label>

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
        </div>
    </div>
</template>

<script setup>
import { computed, useId } from 'vue';
import Checkbox from 'primevue/checkbox';

const model = defineModel({
    type: Boolean,
    default: false,
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

const inputId = computed(() => props.id || `checkbox-field-${generatedId}`);
const hasError = computed(() => Boolean(props.errorMessage));
const wrapperClasses = computed(() => {
    if (hasError.value) {
        return 'app-error-surface';
    }

    return 'app-surface-muted';
});
</script>
