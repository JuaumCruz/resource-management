<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    categories: {
        type: Array,
        required: true
    },
    isLoading: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['delete']);
</script>

<template>
    <div class="mt-6">
        <div v-if="isLoading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else-if="categories.length === 0" class="text-center py-12">
            <p class="text-gray-500">No categories found.</p>
        </div>

        <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resources</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="category in categories" :key="category.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ category.name }}</div>
                        <div class="text-sm text-gray-500">{{ category.slug }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ category.description }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ category.resources_count ?? 0 }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <Link
                            :href="`/categories/${category.id}/edit`"
                            class="text-indigo-600 hover:text-indigo-900 mr-4"
                        >
                            Edit
                        </Link>
                        <button
                            @click="emit('delete', category.id)"
                            class="text-red-600 hover:text-red-900"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
