<script setup>
import { onMounted } from 'vue';
import { useResourceStore } from '@/stores/resources';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ResourceFilters from '@/Components/Resources/ResourceFilters.vue';
import ResourceList from '@/Components/Resources/ResourceList.vue';

const store = useResourceStore();

onMounted(async () => {
    await store.fetchResources();
    await store.fetchCategories();
    await store.fetchTags();
});
</script>

<template>
    <AppLayout>
        <Head title="Resources" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">
                                Resources
                            </h2>
                            <Link
                                href="/resources/create"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                            >
                                Create Resource
                            </Link>
                        </div>

                        <ResourceFilters
                            :categories="store.categories"
                            @filter-change="store.setFilters"
                        />

                        <ResourceList
                            :resources="store.filteredResources"
                            :is-loading="store.isLoading"
                            @delete="store.deleteResource"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
