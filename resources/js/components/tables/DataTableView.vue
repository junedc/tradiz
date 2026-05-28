<template>
    <div class="space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3
                    v-if="title"
                    class="app-text-primary text-lg font-semibold"
                >
                    {{ title }}
                </h3>
                <p
                    v-if="description"
                    class="app-text-secondary mt-1 text-sm"
                >
                    {{ description }}
                </p>
            </div>

            <div class="w-full sm:max-w-xs">
                <IconField class="w-full">
                    <InputIcon class="pi pi-search" />
                    <InputText
                        v-model="searchTerm"
                        :placeholder="searchPlaceholder"
                        :data-testid="searchTestId"
                        class="w-full"
                    />
                </IconField>
            </div>
        </div>

        <div class="app-surface overflow-hidden rounded-[1.5rem] border">
            <DataTable
                :value="filteredRows"
                :data-key="rowKey"
                paginator
                :rows="rowsPerPage"
                :rows-per-page-options="rowsPerPageOptions"
                paginator-template="PrevPageLink PageLinks NextPageLink RowsPerPageDropdown"
                responsive-layout="scroll"
                class="app-data-table"
            >
                <Column
                    v-for="column in columns"
                    :key="column.field"
                    :field="column.field"
                    :header="column.header"
                >
                    <template
                        v-if="column.body"
                        #body="{ data }"
                    >
                        {{ column.body(data) }}
                    </template>
                </Column>

                <template #empty>
                    <div class="app-text-muted px-4 py-8 text-center text-sm">
                        No matching records found.
                    </div>
                </template>
            </DataTable>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    description: {
        type: String,
        default: '',
    },
    columns: {
        type: Array,
        required: true,
    },
    items: {
        type: Array,
        default: () => [],
    },
    rowKey: {
        type: String,
        default: 'id',
    },
    rowsPerPage: {
        type: Number,
        default: 5,
    },
    rowsPerPageOptions: {
        type: Array,
        default: () => [5, 10, 20],
    },
    searchPlaceholder: {
        type: String,
        default: 'Search records',
    },
    searchFields: {
        type: Array,
        default: () => [],
    },
    searchTestId: {
        type: String,
        default: '',
    },
});

const searchTerm = ref('');

const resolvedSearchFields = computed(() => {
    if (props.searchFields.length) {
        return props.searchFields;
    }

    return props.columns.map((column) => column.field);
});

const filteredRows = computed(() => {
    const term = searchTerm.value.trim().toLowerCase();

    if (!term) {
        return props.items;
    }

    return props.items.filter((item) =>
        resolvedSearchFields.value.some((field) => {
            const value = item[field];

            return String(value ?? '')
                .toLowerCase()
                .includes(term);
        }),
    );
});

watch(
    () => props.items,
    () => {
        if (!props.items.length) {
            searchTerm.value = '';
        }
    },
);
</script>
