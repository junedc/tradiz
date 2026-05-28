import { defineStore } from 'pinia';

import { fetchProducts } from '../services/products';

export const useCatalogStore = defineStore('catalog', {
    state: () => ({
        products: [],
        isLoading: false,
        errorMessage: '',
    }),
    actions: {
        async loadProducts() {
            this.isLoading = true;
            this.errorMessage = '';

            try {
                const payload = await fetchProducts();
                this.products = payload.data;
            } catch (error) {
                this.errorMessage = error.response?.data?.message ?? 'Unable to load products.';
            } finally {
                this.isLoading = false;
            }
        },
    },
});
