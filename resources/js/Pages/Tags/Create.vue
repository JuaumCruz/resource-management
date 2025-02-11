<script setup>
import { Head } from '@inertiajs/vue3';
import { useTagStore } from '@/stores/tags';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TagForm from '@/Components/Tags/TagForm.vue';

const store = useTagStore();

const handleSubmit = async (formData) => {
    await store.createTag(formData);
};
</script>

<template>
    <Head title="Create Tag" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">
                                Create Tag
                            </h2>
                        </div>

                        <TagForm @submit="handleSubmit" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
