<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    tags: {
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

        <div v-else-if="tags.length === 0" class="text-center py-12">
            <p class="text-gray-500">No tags found.</p>
        </div>

        <div v-else class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="tag in tags"
                :key="tag.id"
                class="bg-white p-4 rounded-lg shadow"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ tag.name }}
                        </h3>
                        <p class="text-sm text-gray-500">{{ tag.resources_count ?? 0 }} resources</p>
                    </div>
                    <div class="flex space-x-2">
                        <Link
                            :href="`/tags/${tag.id}/edit`"
                            class="text-indigo-600 hover:text-indigo-900"
                        >
                            Edit
                        </Link>
                        <button
                            @click="emit('delete', tag.id)"
                            class="text-red-600 hover:text-red-900"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
