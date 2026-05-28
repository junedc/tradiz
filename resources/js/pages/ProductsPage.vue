<template>
    <AppLayout>
        <section class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-6">
                <div class="space-y-4">
                    <p class="app-brand-text text-sm font-semibold uppercase tracking-[0.3em]">
                        Phase 1 foundation
                    </p>
                    <div class="space-y-3">
                        <h2 class="app-text-primary max-w-2xl font-serif text-4xl leading-tight">
                            An API-first starter for dynamic blinds quoting.
                        </h2>
                        <p class="app-text-secondary max-w-2xl text-base leading-7">
                            This first slice proves the stack is wired correctly: Laravel owns the
                            product catalog, Vue loads it through `/api/products`, and the UI is ready
                            for the configuration-driven builder that comes next.
                        </p>
                    </div>
                </div>

                <div
                    v-if="catalogStore.errorMessage"
                    class="app-error-surface app-error-text rounded-3xl border px-5 py-4 text-sm"
                >
                    {{ catalogStore.errorMessage }}
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <Card
                        v-for="product in catalogStore.products"
                        :key="product.id"
                        class="app-surface overflow-hidden rounded-3xl border shadow-[0_20px_60px_-35px_rgba(15,23,42,0.45)]"
                    >
                        <template #content>
                            <div class="space-y-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="app-brand-text text-xs font-semibold uppercase tracking-[0.25em]">
                                            {{ product.product_type }}
                                        </p>
                                        <h3 class="app-text-primary mt-2 text-2xl font-semibold">
                                            {{ product.name }}
                                        </h3>
                                    </div>
                                    <span class="app-surface-muted app-text-secondary rounded-full border px-3 py-1 text-xs font-medium">
                                        {{ product.builder.steps.length }} steps
                                    </span>
                                </div>

                                <p class="app-text-secondary text-sm leading-6">
                                    {{ product.description }}
                                </p>

                                <div class="app-surface-muted app-text-secondary grid gap-3 rounded-2xl border p-4 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span>Fields</span>
                                        <span class="app-text-primary font-semibold">
                                            {{ product.builder.fields.length }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Options</span>
                                        <span class="app-text-primary font-semibold">
                                            {{ product.builder.options.length }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>

            <aside class="app-surface rounded-[2rem] border p-6 shadow-[0_20px_70px_-40px_rgba(8,47,73,0.5)] backdrop-blur">
                <div class="space-y-5">
                    <div>
                        <p class="app-brand-text text-sm font-semibold uppercase tracking-[0.3em]">
                            Platform checklist
                        </p>
                        <h3 class="app-text-primary mt-2 text-2xl font-semibold">
                            What Phase 1 establishes
                        </h3>
                    </div>

                    <ul class="app-text-secondary space-y-3 text-sm leading-6">
                        <li class="flex gap-3">
                            <i class="pi pi-check-circle app-brand-text mt-1" />
                            Normalized product, material, quote, and pricing tables.
                        </li>
                        <li class="flex gap-3">
                            <i class="pi pi-check-circle app-brand-text mt-1" />
                            A consistent `/api` response contract for the SPA.
                        </li>
                        <li class="flex gap-3">
                            <i class="pi pi-check-circle app-brand-text mt-1" />
                            Seeded product builder metadata for the four supported blind types.
                        </li>
                        <li class="flex gap-3">
                            <i class="pi pi-check-circle app-brand-text mt-1" />
                            A starter `PricingService` ready for deeper pricing rules in Phase 2.
                        </li>
                    </ul>

                    <div class="app-panel-strong rounded-3xl px-5 py-4 text-sm">
                        <p class="font-medium">
                            Catalog status
                        </p>
                        <p class="mt-2 text-slate-300 dark:text-slate-400">
                            <span v-if="catalogStore.isLoading">Loading seeded products from the API...</span>
                            <span v-else>{{ catalogStore.products.length }} products available for builder setup.</span>
                        </p>
                    </div>
                </div>
            </aside>
        </section>
    </AppLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import Card from 'primevue/card';

import AppLayout from '../layouts/AppLayout.vue';
import { useCatalogStore } from '../store/catalog';

const catalogStore = useCatalogStore();

onMounted(() => {
    catalogStore.loadProducts();
});
</script>
