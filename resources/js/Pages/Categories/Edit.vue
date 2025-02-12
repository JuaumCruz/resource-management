<script setup>
import { Head } from '@inertiajs/vue3';
import { useCategoryStore } from '@/stores/categories';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CategoryForm from '@/Components/Categories/CategoryForm.vue';

const props = defineProps({
    category: {
        type: Object,
        required: true
    }
});

const store = useCategoryStore();

const handleSubmit = async (formData) => {
    await store.updateCategory(props.category.id, formData);
};
</script>

<template>
    <Head title="Edit Category" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">
                                Edit Category: {{ category.name }}
                            </h2>
                        </div>

                        <CategoryForm
                            :category="category"
                            :is-edit="true"
                            @submit="handleSubmit"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
