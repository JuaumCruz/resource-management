import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useCategoryStore = defineStore('categories', {
    state: () => ({
        categories: [],
        filters: {
            search: '',
        },
        isLoading: false,
        errors: null
    }),

    getters: {
        filteredCategories: (state) => {
            if (!state.filters.search) return state.categories;

            return state.categories.filter(category =>
                category.name.toLowerCase().includes(state.filters.search.toLowerCase())
            );
        }
    },

    actions: {
        async fetchCategories() {
            this.isLoading = true;
            try {
                const response = await fetch('/api/v1/categories');
                const data = await response.json();
                this.categories = data.data;
            } catch (error) {
                this.errors = error;
            } finally {
                this.isLoading = false;
            }
        },

        setFilters(filters) {
            this.filters = { ...this.filters, ...filters };
        },

        async createCategory(category) {
            try {
                const response = await fetch('/api/v1/categories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(category)
                });

                if (!response.ok) throw new Error('Failed to create category');

                await this.fetchCategories();
                router.visit('/categories');
            } catch (error) {
                this.errors = error;
            }
        },

        async updateCategory(id, category) {
            try {
                const response = await fetch(`/api/v1/categories/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(category)
                });

                if (!response.ok) throw new Error('Failed to update category');

                await this.fetchCategories();
                router.visit('/categories');
            } catch (error) {
                this.errors = error;
            }
        },

        async deleteCategory(id) {
            try {
                const response = await fetch(`/api/v1/categories/${id}`, {
                    method: 'DELETE'
                });

                if (!response.ok) throw new Error('Failed to delete category');

                await this.fetchCategories();
            } catch (error) {
                this.errors = error;
            }
        }
    }
});
