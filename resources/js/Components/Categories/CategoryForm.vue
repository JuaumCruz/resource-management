<script setup>
import { ref } from 'vue';

const props = defineProps({
    category: {
        type: Object,
        default: () => ({
            name: '',
            description: ''
        })
    },
    isEdit: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['submit']);
const form = ref({ ...props.category });

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

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ isEdit ? 'Update' : 'Create' }} Category
            </button>
        </div>
    </form>
</template>
