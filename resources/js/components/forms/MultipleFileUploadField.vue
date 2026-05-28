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
                    label="Choose Files"
                    icon="pi pi-images"
                    size="small"
                    @click="openFileDialog"
                />

                <span class="app-text-muted text-sm">
                    {{ summaryText }}
                </span>
            </div>

            <input
                :id="inputId"
                ref="inputRef"
                :name="name"
                :accept="accept"
                type="file"
                multiple
                class="hidden"
                :data-testid="testId"
                @change="onFileChange"
            >

            <div
                v-if="previews.length"
                class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3"
            >
                <div
                    v-for="preview in previews"
                    :key="preview.key"
                    class="app-surface relative overflow-hidden rounded-2xl border"
                >
                    <img
                        v-if="preview.isImage"
                        :src="preview.url"
                        :alt="preview.name"
                        class="h-32 w-full object-cover"
                    >
                    <div
                        v-else
                        class="app-surface-muted flex h-32 w-full flex-col items-center justify-center gap-2 px-3 text-center"
                    >
                        <i class="pi pi-file app-brand-text text-2xl" />
                        <span class="app-text-secondary line-clamp-2 text-xs font-medium">
                            {{ preview.name }}
                        </span>
                    </div>

                    <button
                        type="button"
                        class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-black/70 text-white transition hover:bg-black/85"
                        :aria-label="`Remove ${preview.name}`"
                        @click="removeFile(preview.key)"
                    >
                        <i class="pi pi-times text-xs" />
                    </button>

                    <div class="app-surface-muted app-text-secondary border-t px-3 py-2 text-xs font-medium">
                        {{ preview.name }}
                    </div>
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
    default: () => [],
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
        default: 'No files selected',
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
const inputId = computed(() => props.id || `multiple-file-upload-field-${generatedId}`);
const inputRef = ref(null);
const previews = ref([]);

const summaryText = computed(() => {
    if (!model.value?.length) {
        return props.placeholder;
    }

    return `${model.value.length} file${model.value.length === 1 ? '' : 's'} selected`;
});

function revokePreviewUrls() {
    previews.value.forEach((preview) => {
        if (preview.url?.startsWith('blob:')) {
            URL.revokeObjectURL(preview.url);
        }
    });
}

function syncPreviews(files) {
    revokePreviewUrls();

    previews.value = (files ?? []).map((file, index) => ({
        key: `${file.name}-${file.lastModified}-${index}`,
        name: file.name,
        isImage: file.type.startsWith('image/'),
        url: file.type.startsWith('image/') ? URL.createObjectURL(file) : '',
    }));
}

function openFileDialog() {
    inputRef.value?.click();
}

function onFileChange(event) {
    const files = Array.from(event.target.files ?? []);
    model.value = files;
}

function removeFile(key) {
    model.value = model.value.filter((file, index) => `${file.name}-${file.lastModified}-${index}` !== key);

    if (inputRef.value && model.value.length === 0) {
        inputRef.value.value = '';
    }
}

watch(
    () => model.value,
    (files) => {
        syncPreviews(files);
    },
    { immediate: true, deep: true },
);

onBeforeUnmount(() => {
    revokePreviewUrls();
});
</script>
