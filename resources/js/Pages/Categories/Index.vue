<script setup>
import { onMounted } from 'vue';
import { useCategoryStore } from '@/stores/categories';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CategoryList from '@/Components/Categories/CategoryList.vue';

const store = useCategoryStore();

onMounted(async () => {
    await store.fetchCategories();
});
</script>

<template>
    <AppLayout>
        <Head title="Categories" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">
                                Categories
                            </h2>
                            <Link
                                href="/categories/create"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                            >
                                Create Category
                            </Link>
                        </div>

                        <div class="mb-6">
                            <input
                                type="text"
                                placeholder="Search categories..."
                                v-model="store.filters.search"
                                class="w-full sm:w-96 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <CategoryList
                            :categories="store.filteredCategories"
                            :is-loading="store.isLoading"
                            @delete="store.deleteCategory"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
