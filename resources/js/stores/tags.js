// resources/js/stores/tags.js
import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

export const useTagStore = defineStore('tags', {
    state: () => ({
        tags: [],
        filters: {
            search: '',
        },
        isLoading: false,
        errors: null
    }),

    getters: {
        filteredTags: (state) => {
            if (!state.filters.search) return state.tags;
            
            return state.tags.filter(tag => 
                tag.name.toLowerCase().includes(state.filters.search.toLowerCase())
            );
        }
    },

    actions: {
        async fetchTags() {
            this.isLoading = true;
            try {
                const response = await fetch('/api/v1/tags');
                const data = await response.json();
                this.tags = data.data;
            } catch (error) {
                this.errors = error;
            } finally {
                this.isLoading = false;
            }
        },

        setFilters(filters) {
            this.filters = { ...this.filters, ...filters };
        },

        async createTag(tag) {
            try {
                const response = await fetch('/api/v1/tags', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(tag)
                });
                
                if (!response.ok) throw new Error('Failed to create tag');
                
                await this.fetchTags();
                router.visit('/tags');
            } catch (error) {
                this.errors = error;
            }
        },

        async updateTag(id, tag) {
            try {
                const response = await fetch(`/api/v1/tags/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(tag)
                });
                
                if (!response.ok) throw new Error('Failed to update tag');
                
                await this.fetchTags();
                router.visit('/tags');
            } catch (error) {
                this.errors = error;
            }
        },

        async deleteTag(id) {
            try {
                const response = await fetch(`/api/v1/tags/${id}`, {
                    method: 'DELETE'
                });
                
                if (!response.ok) throw new Error('Failed to delete tag');
                
                await this.fetchTags();
            } catch (error) {
                this.errors = error;
            }
        }
    }
});
