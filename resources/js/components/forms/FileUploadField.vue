<template>
    <div class="space-y-3">
        <label
            v-if="label"
            :for="inputId"
            class="app-text-primary block text-sm font-semibold"
        >
            {{ label }}
        </label>

        <div class="app-surface-muted rounded-[1.5rem] border p-4">
            <div class="flex flex-wrap items-center gap-3">
                <Button
                    type="button"
                    label="Choose File"
                    icon="pi pi-upload"
                    size="small"
                    @click="openFileDialog"
                />

                <span class="app-text-muted text-sm">
                    {{ fileName || placeholder }}
                </span>
            </div>

            <input
                :id="inputId"
                ref="inputRef"
                :name="name"
                :accept="accept"
                type="file"
                class="hidden"
                :data-testid="testId"
                @change="onFileChange"
            >

            <div
                v-if="preview"
                class="mt-4"
            >
                <div class="app-surface relative inline-flex overflow-hidden rounded-2xl border">
                    <img
                        v-if="preview.isImage"
                        :src="preview.url"
                        :alt="preview.name"
                        class="h-32 w-32 object-cover"
                    >
                    <div
                        v-else
                        class="app-surface-muted flex h-32 w-32 flex-col items-center justify-center gap-2 border-0 px-3 text-center"
                    >
                        <i class="pi pi-file app-brand-text text-2xl" />
                        <span class="app-text-secondary line-clamp-2 text-xs font-medium">
                            {{ preview.name }}
                        </span>
                    </div>

                    <button
                        type="button"
                        class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-black/70 text-white transition hover:bg-black/85"
                        aria-label="Remove file"
                        @click="clearFile"
                    >
                        <i class="pi pi-times text-xs" />
                    </button>
                </div>
            </div>
        </div>

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
import { computed, onBeforeUnmount, ref, useId, watch } from 'vue';
import Button from 'primevue/button';

const model = defineModel({
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
        default: 'No file selected',
    },
    helperText: {
        type: String,
        default: '',
    },
    errorMessage: {
        type: String,
        default: '',
    },
    accept: {
        type: String,
        default: 'image/*',
    },
    testId: {
        type: String,
        default: '',
    },
});

const generatedId = useId();
const inputId = computed(() => props.id || `file-upload-field-${generatedId}`);
const inputRef = ref(null);
const preview = ref(null);

const fileName = computed(() => model.value?.name ?? '');

function revokePreview() {
    if (preview.value?.url?.startsWith('blob:')) {
        URL.revokeObjectURL(preview.value.url);
    }
}

function buildPreview(file) {
    if (!file) {
        return null;
    }

    const isImage = file.type.startsWith('image/');

    return {
        name: file.name,
        isImage,
        url: isImage ? URL.createObjectURL(file) : '',
    };
}

function syncPreview(file) {
    revokePreview();
    preview.value = buildPreview(file);
}

function openFileDialog() {
    inputRef.value?.click();
}

function onFileChange(event) {
    const [file] = event.target.files ?? [];
    model.value = file ?? null;
}

function clearFile() {
    model.value = null;

    if (inputRef.value) {
        inputRef.value.value = '';
    }
}

watch(
    () => model.value,
    (file) => {
        syncPreview(file);
    },
    { immediate: true },
);

onBeforeUnmount(() => {
    revokePreview();
});
</script>
