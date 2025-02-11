<script setup>
import { onMounted } from 'vue';
import { useTagStore } from '@/stores/tags';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import TagList from '@/Components/Tags/TagList.vue';

const store = useTagStore();

onMounted(async () => {
    await store.fetchTags();
});
</script>

<template>
    <AppLayout>
        <Head title="Tags" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">
                                Tags
                            </h2>
                            <Link
                                href="/tags/create"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                            >
                                Create Tag
                            </Link>
                        </div>

                        <div class="mb-6">
                            <input
                                type="text"
                                placeholder="Search tags..."
                                v-model="store.filters.search"
                                class="w-full sm:w-96 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <TagList
                            :tags="store.filteredTags"
                            :is-loading="store.isLoading"
                            @delete="store.deleteTag"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
