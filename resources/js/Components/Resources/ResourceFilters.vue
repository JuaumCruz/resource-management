<script setup>
import { ref } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['filter-change']);

const selectedCategory = ref(null);

const handleFilterChange = () => {
    emit('filter-change', {
        category: selectedCategory.value
    });
};
</script>

<template>
        <div class="mb-4 flex items-center space-x-4">
            <label class="text-sm font-medium text-gray-700">Filter by Category:</label>
            <select
                v-model="selectedCategory"
                @change="handleFilterChange"
                class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
                <option value="">All Categories</option>
                <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>
        </div>
</template>
