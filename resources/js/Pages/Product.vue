<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import {Inertia} from '@inertiajs/inertia';

const props = defineProps({
    product: Object,
    categories: Array,
});

const product = props.product.original.product
const categories = props.product.original.categories

const orders = product.order;
const currentImage = ref(product.product_image[0] ? '/storage/' + product.product_image[0].source : '/storage/placeholder.png');
const isEditModalOpen = ref(false); // Состояние для управления модальным окном
const form = ref({
    name: product.name,
    category_id: product.category.id,
    description: product.description,
    price: product.price,
    image: null,
});

function setCurrentImage(imageSource) {
    currentImage.value = '/storage/' + imageSource;
}

const openEditModal = () => {
    isEditModalOpen.value = true;
};

const closeEditModal = () => {
    isEditModalOpen.value = false;
};

const submitEditForm = () => {
    const formData = new FormData();
    formData.append('name', form.value.name);
    formData.append('category_id', form.value.category_id);
    formData.append('description', form.value.description);
    formData.append('price', form.value.price);
    if (form.value.image) {
        formData.append('image', form.value.image);
    }
    Inertia.post(route('products.update', product.id), formData, {
        forceFormData: true,
    }).then(() => {
        closeEditModal();
    });
};
</script>

<template>
    <Head :title="product.name"/>
    <AuthenticatedLayout>
        <h1 v-for="el, key in product" class="text-white">
            {{key}}: {{el}}
        </h1>

        <div class="w-3/4 dark:bg-gray-800 bg-white-200 rounded-lg mt-10 mx-auto py-16">
            <div class="sm:flex">
                <div class="space-y-4 p-8 xl:m-12 lg:w-1/3">
                    <img :src="currentImage" alt="Main image" class="w-full object-contain rounded-lg">
                    <div class="flex flex-wrap">
                        <img v-for="(image, index) in product.product_image"
                             :key="index"
                             :src="'/storage/placeholder.png'"
                             class="w-1/3 m-4 object-contain rounded-lg cursor-pointer hover:opacity-75"
                             @click="setCurrentImage(image.source)">
                    </div>
                </div>
                <div class="mx-12 sm:m-12 text-white w-1/2">
                    <div class="flex justify-between">
                        <h1 class="sm:text-4xl uppercase ">
                            {{ product.name }}
                        </h1>
                        <h1 class="sm:text-4xl uppercase ">
                            {{ product.price }} $
                        </h1>
                    </div>
                    <h2 class="my-6 sm:text-2xl">
                        Category: {{ product.category.name }}
                    </h2>
                    <h3 class="my-4 sm:text-xl">
                        Category description: {{ product.category.description }}
                    </h3>
                </div>
            </div>
            <p class="mx-12 sm:m-12 text-white my-6">
                {{ product.description }}
            </p>
            <button @click="openEditModal" class="bg-blue-500 text-white mx-16 px-4 py-2 rounded">Edit Product</button>
        </div>

        <div v-if="product.order[0]"
             class="sm:block w-3/4 dark:bg-gray-800 bg-white-200 rounded-lg mt-10 mx-auto py-12">
            <h1 class="text-white text-4xl text-center my-10">
                Orders
            </h1>
            <table class="table-fixed dark:text-white w-5/6 mx-auto text-sm xl:text-base">
                <thead>
                <tr>
                    <th class="dark:border-amber-100 border-gray-950 border truncate text-xs lg:text-base md:table-cell md:w-3/12">
                        Client
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border truncate text-xs lg:text-base md:table-cell md:w-3/12">
                        Employee
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border truncate text-xs lg:text-base md:table-cell md:w-2/12">
                        Total
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border truncate text-xs lg:text-base md:table-cell md:w-1/12">
                        Order ID
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border truncate hidden lg:text-base md:table-cell md:w-1/12">
                        Payment method
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border truncate hidden lg:text-base md:table-cell text-wrap md:w-1/12">
                        Credit (?)
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border truncate hidden lg:text-base md:table-cell md:w-1/12">
                        Paid (?)
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders" :key="order.id">
                    <td class="dark:border-amber-100 border-gray-950 border p-2 truncate text-xs lg:text-xl md:table-cell text-pretty">
                        {{ order.client.surname }} {{ order.client.name }}
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 truncate text-xs lg:text-xl md:table-cell text-pretty">
                        <p class="underline decoration-solid">{{ order.employee.position }}</p>
                        <p>{{ order.employee.surname }} {{ order.employee.name }}</p>
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 truncate text-xs lg:text-xl md:table-cell text-pretty">
                        {{ order.total }} $
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 truncate text-xs lg:text-xl md:table-cell text-pretty hover:underline">
                        <a :href="route('orders.show', order.id)">{{ order.id }}</a>
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 truncate hidden md:table-cell text-pretty">
                        {{ order.payment_method }}
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 hidden md:table-cell text-pretty">
                        {{ order.is_credit ? "Yes" : "No" }}
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 hidden md:table-cell text-pretty">
                        {{ order.is_paid ? "Yes" : "No" }}
                    </td>

                </tr>
                </tbody>
            </table>
        </div>

        <!-- Модальное окно для редактирования продукта -->
        <div v-if="isEditModalOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-1/2">
                <h2 class="text-xl font-semibold mb-4">Edit Product</h2>
                <form @submit.prevent="submitEditForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200">Product Name</label>
                        <input type="text" v-model="form.name" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200">Category</label>
                        <select v-model="form.category_id" class="w-full mt-1 p-2 border rounded">
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200">Description</label>
                        <textarea v-model="form.description" class="w-full mt-1 p-2 border rounded"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200">Price</label>
                        <input type="number" v-model="form.price" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200">Image</label>
                        <input type="file" @change="e => form.image = e.target.files[0]" class="w-full mt-1 p-2 border rounded dark:text-white"/>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeEditModal" class="bg-gray-200 dark:bg-gray-700 px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
