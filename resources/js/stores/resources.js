import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useResourceStore = defineStore('resources', {
    state: () => ({
        resources: [],
        categories: [],
        tags: [],
        filters: {
            search: '',
            category: null,
            status: null
        },
        isLoading: false,
        errors: null
    }),

    getters: {
        filteredResources: (state) => {
            let filtered = [...state.resources];

            if (state.filters.search) {
                filtered = filtered.filter(resource =>
                    resource.name.toLowerCase().includes(state.filters.search.toLowerCase())
                );
            }

            if (state.filters.category) {
                filtered = filtered.filter(resource =>
                    resource.category_id === state.filters.category
                );
            }

            if (state.filters.status) {
                filtered = filtered.filter(resource =>
                    resource.status === state.filters.status
                );
            }

            return filtered;
        }
    },

    actions: {
        async fetchResources() {
            this.isLoading = true;
            try {
                const response = await fetch('/api/v1/resources');
                const data = await response.json();
                this.resources = data.data;
            } catch (error) {
                this.errors = error;
            } finally {
                this.isLoading = false;
            }
        },

        async fetchCategories() {
            try {
                const response = await fetch('/api/v1/categories');
                const data = await response.json();
                this.categories = data.data;
            } catch (error) {
                this.errors = error;
            }
        },

        async fetchTags() {
            try {
                const response = await fetch('/api/v1/tags');
                const data = await response.json();
                this.tags = data.data;
            } catch (error) {
                this.errors = error;
            }
        },

        setFilters(filters) {
            this.filters = { ...this.filters, ...filters };
        },

        async createResource(resource) {
            try {
                const response = await fetch('/api/v1/resources', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(resource)
                });

                if (!response.ok) throw new Error('Failed to create resource');

                await this.fetchResources();
                router.visit('/resources');
            } catch (error) {
                this.errors = error;
            }
        },

        async updateResource(id, resource) {
            try {
                const response = await fetch(`/api/v1/resources/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(resource)
                });

                if (!response.ok) throw new Error('Failed to update resource');

                await this.fetchResources();
                router.visit('/resources');
            } catch (error) {
                this.errors = error;
            }
        },

        async deleteResource(id) {
            try {
                const response = await fetch(`/api/v1/resources/${id}`, {
                    method: 'DELETE'
                });

                if (!response.ok) throw new Error('Failed to delete resource');

                await this.fetchResources();
            } catch (error) {
                this.errors = error;
            }
        }
    }
});
