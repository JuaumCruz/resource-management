<script setup>
import { Head } from '@inertiajs/vue3';
import { useResourceStore } from '@/stores/resources';
import AppLayout from '@/Layouts/AppLayout.vue';
import ResourceForm from '@/Components/Resources/ResourceForm.vue';

const props = defineProps({
    resource: {
        type: Object,
        required: true
    }
});

const store = useResourceStore();

const handleSubmit = async (formData) => {
    await store.updateResource(props.resource.id, formData);
};
</script>

<template>
    <AppLayout>
        <Head title="Edit Resource" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">
                                Edit Resource: {{ resource.name }}
                            </h2>
                        </div>

                        <ResourceForm
                            :resource="resource"
                            :is-edit="true"
                            @submit="handleSubmit"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
