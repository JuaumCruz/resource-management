<script setup>
import { ref, onMounted } from 'vue';
import { useResourceStore } from '@/stores/resources.js';

const props = defineProps({
    resource: {
        type: Object,
        default: () => ({
            name: '',
            description: '',
            category_id: '',
            status: 'draft',
            tags: [],
            metadata: {}
        })
    },
    isEdit: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['submit']);
const store = useResourceStore();
const form = ref({ ...props.resource });

const statusOptions = [
    { value: 'draft', label: 'Draft' },
    { value: 'published', label: 'Published' },
    { value: 'archived', label: 'Archived' }
];

onMounted(async () => {
    if (!store.categories.length) {
        await store.fetchCategories();
    }
    if (!store.tags.length) {
        await store.fetchTags();
    }
});

const handleSubmit = () => {
    emit('submit', form.value);
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            />
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
                id="description"
                v-model="form.description"
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            />
        </div>

        <!-- Category -->
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select
                id="category"
                v-model="form.category_id"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            >
                <option value="">Select a category</option>
                <option
                    v-for="category in store.categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select
                id="status"
                v-model="form.status"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            >
                <option
                    v-for="option in statusOptions"
                    :key="option.value"
                    :value="option.value"
                >
                    {{ option.label }}
                </option>
            </select>
        </div>

        <!-- Tags -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Tags</label>
            <div class="mt-2 space-y-2">
                <div
                    v-for="tag in store.tags"
                    :key="tag.id"
                    class="flex items-center"
                >
                    <input
                        :id="'tag-' + tag.id"
                        type="checkbox"
                        :value="tag.id"
                        v-model="form.tags"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    />
                    <label
                        :for="'tag-' + tag.id"
                        class="ml-2 text-sm text-gray-700"
                    >
                        {{ tag.name }}
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ isEdit ? 'Update' : 'Create' }} Resource
            </button>
        </div>
    </form>
</template>
