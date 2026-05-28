<template>
    <div class="min-h-screen app-text-primary">
        <header class="app-surface border-b backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
                <div>
                    <p class="app-brand-text text-xs font-semibold uppercase tracking-[0.3em]">
                        Tradiz
                    </p>
                    <h1 class="app-text-primary font-serif text-2xl">
                        Blinds Builder Platform
                    </h1>
                </div>
                <nav class="flex items-center gap-3">
                    <RouterLink
                        to="/products"
                        class="app-brand-surface app-brand-text rounded-full border px-4 py-2 text-sm font-medium transition hover:opacity-90"
                    >
                        Products
                    </RouterLink>
                    <RouterLink
                        to="/template"
                        class="app-surface-muted app-text-secondary rounded-full border px-4 py-2 text-sm font-medium transition hover:opacity-90"
                    >
                        Template
                    </RouterLink>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-10">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>
