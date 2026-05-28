<template>
    <AppLayout>
        <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-6 lg:col-span-2">
                <div class="space-y-3">
                    <p class="app-brand-text text-sm font-semibold uppercase tracking-[0.3em]">
                        Form template
                    </p>
                    <h2 class="app-text-primary font-serif text-4xl leading-tight">
                        Reusable field components for builder and quote forms.
                    </h2>
                    <p class="app-text-secondary max-w-2xl text-base leading-7">
                        This page is a shared reference for future forms. It keeps labels, helper
                        text, error states, and PrimeVue input styling consistent across the app.
                    </p>
                </div>

                <div class="app-surface rounded-[2rem] border p-6 shadow-[0_20px_70px_-40px_rgba(8,47,73,0.45)] backdrop-blur">
                    <div class="grid gap-5 md:grid-cols-2">
                        <TextField
                            v-model="form.customerName"
                            label="Customer Name"
                            name="customer_name"
                            placeholder="Enter customer name"
                            helper-text="Use the full contact name for the quote."
                            :error-message="errors.customerName"
                            test-id="customer-name-input"
                        />

                        <TextField
                            v-model="form.projectName"
                            label="Project Name"
                            name="project_name"
                            placeholder="Living Room Refresh"
                            helper-text="Helpful when multiple quotes belong to one client."
                            test-id="project-name-input"
                        />

                        <SelectField
                            v-model="form.productType"
                            label="Product Type"
                            name="product_type"
                            :options="productOptions"
                            placeholder="Choose a blind type"
                            helper-text="This would usually come from the catalog API."
                            :error-message="errors.productType"
                            test-id="product-type-select"
                        />

                        <SelectField
                            v-model="form.controlSide"
                            label="Control Side"
                            name="control_side"
                            :options="controlSideOptions"
                            placeholder="Choose a control side"
                            helper-text="A simple example of a smaller select list."
                            test-id="control-side-select"
                        />

                        <NumberField
                            v-model="form.width"
                            label="Width (mm)"
                            name="width"
                            placeholder="1800"
                            helper-text="Builder measurements should stay API-validated."
                            :error-message="errors.width"
                            :min="300"
                            :max="5000"
                            test-id="width-input"
                        />

                        <NumberField
                            v-model="form.estimatedInstall"
                            label="Estimated Install"
                            name="estimated_install"
                            helper-text="Currency mode is useful for quote-level adjustments."
                            mode="currency"
                            currency="AUD"
                            :min-fraction-digits="2"
                            :max-fraction-digits="2"
                            test-id="estimated-install-input"
                        />

                        <DateField
                            v-model="form.installDate"
                            label="Install Date"
                            name="install_date"
                            helper-text="Helpful for scheduling and quote follow-up workflows."
                            :error-message="errors.installDate"
                            test-id="install-date-input"
                        />

                        <div class="md:col-span-2">
                            <TextareaField
                                v-model="form.notes"
                                label="Project Notes"
                                name="notes"
                                placeholder="Add room details, site notes, or customer preferences"
                                helper-text="A good place for installation notes or special requests."
                                test-id="project-notes-textarea"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <CheckboxField
                                v-model="form.requiresInstallation"
                                label="Requires Installation"
                                name="requires_installation"
                                helper-text="Use this pattern for simple toggles that affect pricing or workflow."
                                test-id="requires-installation-checkbox"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <FileUploadField
                                v-model="form.heroImage"
                                label="Hero Image"
                                name="hero_image"
                                helper-text="Single file upload with preview and remove state."
                                test-id="hero-image-upload"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <MultipleFileUploadField
                                v-model="form.galleryImages"
                                label="Gallery Images"
                                name="gallery_images"
                                helper-text="Use this for room references, site photos, or supporting images."
                                test-id="gallery-images-upload"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <div class="app-surface-muted rounded-[1.5rem] border p-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="app-text-primary text-sm font-semibold">
                                            Reusable Dialog
                                        </p>
                                        <p class="app-text-secondary mt-1 text-sm">
                                            PrimeVue already has a solid modal dialog, so we can wrap it once and reuse the same API everywhere.
                                        </p>
                                    </div>
                                    <Button
                                        type="button"
                                        label="Open Dialog"
                                        icon="pi pi-window-maximize"
                                        @click="isDialogOpen = true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="app-panel-strong rounded-[2rem] border border-slate-800 p-6 shadow-[0_20px_70px_-40px_rgba(15,23,42,0.7)]">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-300">
                        Live state
                    </p>
                    <pre class="mt-4 overflow-x-auto rounded-2xl bg-slate-900 p-4 text-sm leading-6 text-slate-200">{{ formattedForm }}</pre>
                </div>

                <div class="app-brand-surface rounded-[2rem] border p-6">
                    <p class="app-brand-text text-sm font-semibold uppercase tracking-[0.3em]">
                        Suggested usage
                    </p>
                    <ul class="app-text-secondary mt-4 space-y-3 text-sm leading-6">
                        <li class="flex gap-3">
                            <i class="pi pi-check-circle app-brand-text mt-1" />
                            Put shared inputs in `resources/js/components/forms`.
                        </li>
                        <li class="flex gap-3">
                            <i class="pi pi-check-circle app-brand-text mt-1" />
                            Keep pages thin and compose forms from small field wrappers.
                        </li>
                        <li class="flex gap-3">
                            <i class="pi pi-check-circle app-brand-text mt-1" />
                            Let Laravel handle final validation while Vue shows friendly state.
                        </li>
                    </ul>
                </div>
            </aside>

            <div class="lg:col-span-2">
                <div class="app-surface rounded-[2rem] border p-6 shadow-[0_20px_70px_-40px_rgba(8,47,73,0.45)] backdrop-blur">
                    <DataTableView
                        title="Quote Line Items"
                        description="A reusable table wrapper with built-in search and pagination."
                        :columns="tableColumns"
                        :items="quoteItems"
                        :rows-per-page="5"
                        :search-fields="['room', 'product', 'material', 'status']"
                        search-placeholder="Search line items"
                        search-test-id="quote-items-search"
                    />
                </div>
            </div>
        </section>
    </AppLayout>

    <AppDialog
        v-model:visible="isDialogOpen"
        title="Edit Quote Details"
        description="This reusable dialog is a good fit for quick-edit forms, confirmations, and focused workflows."
        confirm-label="Save Changes"
        @confirm="isDialogOpen = false"
    >
        <div class="grid gap-4 md:grid-cols-2">
            <TextField
                v-model="dialogForm.customer"
                label="Customer"
                placeholder="Jane Smith"
                test-id="dialog-customer-input"
            />
            <TextField
                v-model="dialogForm.reference"
                label="Reference"
                placeholder="Q-1024"
                test-id="dialog-reference-input"
            />
            <div class="md:col-span-2">
                <TextareaField
                    v-model="dialogForm.notes"
                    label="Internal Notes"
                    placeholder="Add a quick note for the quoting team"
                    test-id="dialog-notes-textarea"
                />
            </div>
        </div>
    </AppDialog>
</template>

<script setup>
import { computed, reactive, ref } from 'vue';

import CheckboxField from '../components/forms/CheckboxField.vue';
import AppDialog from '../components/overlays/AppDialog.vue';
import DateField from '../components/forms/DateField.vue';
import DataTableView from '../components/tables/DataTableView.vue';
import FileUploadField from '../components/forms/FileUploadField.vue';
import MultipleFileUploadField from '../components/forms/MultipleFileUploadField.vue';
import NumberField from '../components/forms/NumberField.vue';
import SelectField from '../components/forms/SelectField.vue';
import TextareaField from '../components/forms/TextareaField.vue';
import TextField from '../components/forms/TextField.vue';
import AppLayout from '../layouts/AppLayout.vue';
import Button from 'primevue/button';

const form = reactive({
    customerName: '',
    projectName: '',
    productType: null,
    controlSide: null,
    width: null,
    estimatedInstall: 120,
    installDate: null,
    notes: '',
    requiresInstallation: true,
    heroImage: null,
    galleryImages: [],
});

const isDialogOpen = ref(false);

const dialogForm = reactive({
    customer: '',
    reference: '',
    notes: '',
});

const productOptions = [
    { label: 'Roller Blinds', value: 'roller' },
    { label: 'Venetian Blinds', value: 'venetian' },
    { label: 'Vertical Blinds', value: 'vertical' },
    { label: 'Roman Blinds', value: 'roman' },
];

const controlSideOptions = [
    { label: 'Left', value: 'left' },
    { label: 'Right', value: 'right' },
];

const tableColumns = [
    { field: 'room', header: 'Room' },
    { field: 'product', header: 'Product' },
    { field: 'material', header: 'Material' },
    { field: 'width', header: 'Width (mm)' },
    { field: 'drop', header: 'Drop (mm)' },
    { field: 'status', header: 'Status' },
];

const quoteItems = [
    { id: 1, room: 'Living Room', product: 'Roller Blinds', material: 'Blockout Linen', width: 1800, drop: 2100, status: 'Draft' },
    { id: 2, room: 'Kitchen', product: 'Venetian Blinds', material: 'Sunscreen Weave', width: 1200, drop: 1500, status: 'Reviewed' },
    { id: 3, room: 'Master Bedroom', product: 'Roman Blinds', material: 'Blockout Linen', width: 1600, drop: 2200, status: 'Draft' },
    { id: 4, room: 'Study', product: 'Roller Blinds', material: 'Sunscreen Weave', width: 1400, drop: 1700, status: 'Approved' },
    { id: 5, room: 'Dining Area', product: 'Vertical Blinds', material: 'Blockout Linen', width: 2400, drop: 2100, status: 'Draft' },
    { id: 6, room: 'Guest Room', product: 'Venetian Blinds', material: 'Sunscreen Weave', width: 1100, drop: 1400, status: 'Quoted' },
];

const errors = computed(() => ({
    customerName: form.customerName ? '' : 'Customer name is required.',
    productType: form.productType ? '' : 'Choose a product type.',
    width: form.width ? '' : 'Enter a width value.',
    installDate: form.installDate ? '' : 'Choose a preferred install date.',
}));

const formattedForm = computed(() => JSON.stringify(form, null, 2));
</script>
