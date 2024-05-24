<script setup>
import { onBeforeUnmount, ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    order: Object,
    categories: Array
});

const client = ref(props.order.client);
const employee = ref(props.order.employee);
const products = ref([...props.order.product]);
const categories = ref(props.categories);
const total = ref(props.order.total);
const isModalOpen = ref(false); // Состояние для управления модальным окном

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

// Функции для добавления и удаления продуктов
const addProduct = () => {
    products.value.push({ name: '', price: 0, category_id: null, pivot: { quantity: 0 } });
    calculateTotal();
};

const removeProduct = (index) => {
    products.value.splice(index, 1);
    calculateTotal();
};

// Функция для пересчета общей суммы
const calculateTotal = () => {
    total.value = products.value.reduce((sum, product) => {
        console.log(sum, product.price, product.pivot.quantity);
        return sum + (product.price * product.pivot.quantity);
    }, 0);
};

// Наблюдение за изменениями в продуктах для пересчета общей суммы
watch(products, calculateTotal, { deep: true });

// Функция для отправки формы
const submitForm = () => {
    const formData = {
        client: client.value,
        employee: employee.value,
        products: products.value.map(product => ({
            id: product.id,
            name: product.name,
            price: product.price,
            category_id: product.category_id,
            pivot: { quantity: product.pivot.quantity }
        })),
        total: total.value,
        order_date: props.order.order_date,
        status: props.order.status,
        payment_method: props.order.payment_method,
        is_credit: props.order.is_credit,
        is_paid: props.order.is_paid,
    };
    Inertia.put(route('orders.update', props.order.id), formData);
    closeModal();
};

onBeforeUnmount(() => {
    axios.post('/clear-session');
});
</script>

<template>
    <Head :title="props.order.name"/>
<!--    <h1 v-for="el, key in props.order"> {{ key }}: {{ el }} </h1>-->
    <AuthenticatedLayout>
        <div v-if="$page.props.flash.message === 'Error'"
             class="flex items-center justify-center mx-auto p-4 my-4 w-1/2 text-balance text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
             role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                 fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">Order wasn't updated!</span>
            </div>
        </div>
        <div class="w-3/4 mt-10 mx-auto md:py-16">
            <div class="flex justify-between my-5">
                <h1 class="text-white md:text-3xl">Client: {{ client.surname }} {{ client.name }}</h1>
                <h1 class="text-white md:text-3xl">Sex: {{ client.sex ? 'Male' : 'Female' }} </h1>
            </div>
            <div class="flex justify-between my-5 flex-wrap">
                <h1 class="text-white md:text-3xl">Age: {{ client.age }}</h1>
                <h1 class="text-white md:text-3xl">Phone: {{ client.phone ? client.phone : 'No phone' }} </h1>
                <h1 class="text-white md:text-3xl">Email: {{ client.email ? client.email : 'No email' }} </h1>
            </div>
        </div>
        <div class="w-3/4 mt-10 mx-auto md:py-16">
            <h1 class="text-white md:text-3xl">Employee: {{ employee.surname }} {{ employee.name }}</h1>
            <h1 class="text-white md:text-3xl">{{ employee.position }} </h1>
        </div>
        <div class="w-3/4 mt-2 mx-auto py-8 flex gap-6 px-4">
            <h1 class="text-white md:text-3xl">
                Credit: <input type="checkbox" :checked="props.order.is_credit" disabled>
            </h1>
            <h1 class="text-white md:text-3xl">
                Paid: <input type="checkbox" :checked="props.order.is_paid" disabled>
            </h1>
            <h1 class="text-white md:text-3xl">
                Delivery: <input type="checkbox" :checked="props.order.delivery" disabled>
            </h1>
        </div>
        <div class="w-3/4 mt-10 mx-auto md:py-16" v-if="props.order.delivery">
            <h1 class="text-white md:text-3xl">Address: {{ client.address }}</h1>
            <h1 class="text-white md:text-3xl">Status: {{ props.order.delivery.status }}</h1>
            <h1 class="text-white md:text-3xl">Delivery date: {{ props.order.delivery.delivery_date }}</h1>
        </div>
        <div class="w-3/4 mt-10 mx-auto md:py-16">
            <h1 class="text-white md:text-3xl">Order:</h1>
            <ol class="my-5 list-decimal md:px-10">
                <li v-for="product in products" class="text-white text-sm md:text-3xl text-pretty"> {{ product.name }} -
                    {{ product.price }}$ - {{ product.pivot.quantity }}
                </li>
            </ol>
        </div>
        <div class="w-3/4 mt-10 mx-auto pb-16 md:py-16 flex flex-wrap gap-x-6">
            <h1 class="text-white md:text-3xl">Total: {{ props.order.total }} $</h1>
            <h1 class="text-white md:text-3xl">Method: {{ props.order.payment_method }}</h1>
            <h1 class="text-white md:text-3xl">Date: {{ props.order.order_date }}</h1>
            <button
                @click="openModal"
                class="md:ml-10 text-white md:text-3xl bg-gray-200 dark:bg-gray-800 py-2 px-4 rounded hover:rounded-xl hover:cursor-pointer">
                Change
            </button>
        </div>
    </AuthenticatedLayout>

    <!-- Модальное окно -->
    <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-1/2">
            <h2 class="text-xl font-semibold mb-4">Edit Order</h2>
            <form @submit.prevent="submitForm">
                <!-- Форма для изменения данных заказа -->
                <div class="mb-4 flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Client Surname</label>
                        <input type="text" v-model="client.surname" class="w-3/4 mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Client Name</label>
                        <input type="text" v-model="client.name" class="w-3/4 mt-1 p-2 border rounded"/>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-200">Client Sex:</label>
                        <select v-model="client.sex">
                            <option :value="1">Male</option>
                            <option :value="0">Female</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 flex gap-4">
                    <div class="w-1/6">
                        <label class="block text-gray-700 dark:text-gray-200">Client Age</label>
                        <input type="number" v-model="client.age" class="w-3/4 mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Client Phone</label>
                        <input type="text" v-model="client.phone" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Client Email</label>
                        <input type="text" v-model="client.email" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Client Address</label>
                    <input type="text" v-model="client.address" class="w-full mt-1 p-2 border rounded"/>
                </div>

                <div class="mt-12 mb-4 flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Employee Surname</label>
                        <input type="text" v-model="employee.surname" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Employee Name</label>
                        <input type="text" v-model="employee.name" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                </div>
                <div class="mt-12 mb-4">
                    <h3 class="block text-gray-700 dark:text-gray-200">Products</h3>
                    <div v-for="(product, index) in products" :key="index" class="mb-4 flex gap-4 items-center">
                        <input type="text" v-model="product.name" class="w-1/3 mt-1 p-2 border rounded"
                               placeholder="Product Name"/>
                        <input type="number" v-model="product.price" class="w-1/3 mt-1 p-2 border rounded"
                               placeholder="Price" @input="calculateTotal"/>
                        <input type="number" v-model="product.pivot.quantity" class="w-1/3 mt-1 p-2 border rounded"
                               placeholder="Quantity" @input="calculateTotal"/>
                        <select v-model="product.category_id" class="w-1/3 mt-1 p-2 border rounded">
                            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                        </select>
                        <button type="button" @click="removeProduct(index)"
                                class="bg-red-500 text-white px-4 py-2 rounded">Remove
                        </button>
                    </div>
                    <button type="button" @click="addProduct" class="bg-green-500 text-white px-4 py-2 rounded">Add
                        Product
                    </button>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Total</label>
                    <input type="number" v-model="total" class="w-full mt-1 p-2 border rounded" readonly/>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="closeModal"
                            class="bg-gray-200 dark:bg-gray-700 px-4 py-2 rounded mr-2">Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* Ваши стили... */
</style>
