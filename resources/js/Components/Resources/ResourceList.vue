<script setup>
import {Link} from '@inertiajs/vue3';

defineProps({
    resources: {
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

        <div v-else-if="resources.length === 0" class="text-center py-12">
            <p class="text-gray-500">No resources found.</p>
        </div>

        <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="resource in resources"
                :key="resource.id"
                class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow"
            >
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ resource.name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ resource.description }}
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            :class="{
                                'bg-green-100 text-green-800': resource.status === 'published',
                                'bg-yellow-100 text-yellow-800': resource.status === 'draft',
                                'bg-gray-100 text-gray-800': resource.status === 'archived'
                            }"
                        >
                            {{ resource.status }}
                        </span>
                    </div>

                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <span>Category:</span>
                            <span class="ml-2 font-medium text-gray-900">
                                {{ resource.category?.name }}
                            </span>
                        </div>

                        <div v-if="resource.tags?.length" class="mt-2 flex flex-wrap gap-2">
                            <span
                                v-for="tag in resource.tags"
                                :key="tag.id"
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800"
                            >
                                {{ tag.name }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <Link
                            :href="`/resources/${resource.id}/edit`"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Edit
                        </Link>
                        <button
                            @click="emit('delete', resource.id)"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
