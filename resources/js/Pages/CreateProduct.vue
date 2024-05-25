<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    categories: Array,
});

const form = ref({
    name: '',
    category_id: '',
    description: '',
    price: '',
});

const submitForm = () => {
    Inertia.post(route('products.store'), form.value);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create Product" />
        <div class="container mx-auto py-8">
            <h1 class="text-3xl mb-8 dark:text-gray-100">Create New Product</h1>
            <form @submit.prevent="submitForm" class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-100">Product Name</label>
                    <input type="text" v-model="form.name" class="w-full mt-1 p-2 border rounded" />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-100">Category</label>
                    <select v-model="form.category_id" class="w-full mt-1 p-2 border rounded">
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-100">Description</label>
                    <textarea v-model="form.description" class="w-full mt-1 p-2 border rounded"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-100">Price</label>
                    <input type="number" v-model="form.price" class="w-full mt-1 p-2 border rounded" />
                </div>
                <div class="flex justify-end">
                    <Link href="/" class="bg-gray-200 px-4 py-2 rounded mr-2">Cancel</Link>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
