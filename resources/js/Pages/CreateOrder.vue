<script setup>
import {Inertia} from '@inertiajs/inertia';
import {ref, watch} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

// Получение текущей даты в формате YYYY-MM-DD
const getCurrentDate = () => {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months start at 0!
    const dd = String(today.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
};

const props = defineProps({
    categories: Array
});

const form = ref({
    client: {
        surname: '',
        name: '',
        sex: null,
        age: null,
        phone: '',
        email: '',
        address: ''
    },
    employee: {
        surname: '',
        name: ''
    },
    products: [],
    total: 0,
    order_date: getCurrentDate(), // Устанавливаем текущую дату
    status: '',
    payment_method: '',
    is_credit: false,
    is_paid: false
});

const products = ref([]);
const total = ref(0);
const categories = ref(props.categories);

const addProduct = () => {
    products.value.push({name: '', price: 0, category_id: null, pivot: {quantity: 0}});
    calculateTotal();
};

const removeProduct = (index) => {
    products.value.splice(index, 1);
    calculateTotal();
};

const calculateTotal = () => {
    total.value = products.value.reduce((sum, product) => {
        return sum + (product.price * product.pivot.quantity);
    }, 0);
};

watch(products, calculateTotal, {deep: true});

const submitForm = () => {
    const formData = {
        client: form.value.client,
        employee: form.value.employee,
        products: products.value.map(product => ({
            name: product.name,
            price: product.price,
            category_id: product.category_id,
            pivot: {quantity: product.pivot.quantity}
        })),
        total: total.value,
        order_date: form.value.order_date,
        status: form.value.status,
        payment_method: form.value.payment_method,
        is_credit: form.value.is_credit,
        is_paid: form.value.is_paid,
    };
    Inertia.post(route('orders.store'), formData);
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="bg-white dark:bg-gray-800 m-8 p-16 rounded-lg shadow-lg w-11/12 mx-auto">
            <h2 class="text-xl font-semibold dark:text-gray-100 mb-4">Create Order</h2>
            <form @submit.prevent="submitForm">
                <!-- Client Information -->
                <div class="mb-4 flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Client Surname</label>
                        <input type="text" v-model="form.client.surname" class="w-3/4 mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Client Name</label>
                        <input type="text" v-model="form.client.name" class="w-3/4 mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/12">
                        <label class="block text-gray-700 dark:text-gray-200">Client Sex</label>
                        <select v-model="form.client.sex" class="w-full mt-1 p-2 border rounded">
                            <option :value="1">Male</option>
                            <option :value="0">Female</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4 flex gap-4">
                    <div class="w-1/6">
                        <label class="block text-gray-700 dark:text-gray-200">Client Age</label>
                        <input type="number" v-model="form.client.age" class="w-3/4 mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Client Phone</label>
                        <input type="text" v-model="form.client.phone" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Client Email</label>
                        <input type="text" v-model="form.client.email" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Client Address</label>
                    <input type="text" v-model="form.client.address" class="w-full mt-1 p-2 border rounded"/>
                </div>

                <!-- Employee Information -->
                <div class="mt-12 mb-4 flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Employee Surname</label>
                        <input type="text" v-model="form.employee.surname" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Employee Name</label>
                        <input type="text" v-model="form.employee.name" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                </div>

                <!-- Order Information -->

                <div class="flex gap-6 w-full">
                    <div class="flex gap-6 w-3/4">
                        <div class="mb-4 w-1/2">
                            <label class="block text-gray-700 dark:text-gray-200">Status</label>
                            <input type="text" v-model="form.status" class="w-full mt-1 p-2 border rounded"/>
                        </div>
                        <div class="mb-4 w-1/2">
                            <label class="block text-gray-700 dark:text-gray-200">Payment Method</label>
                            <input type="text" v-model="form.payment_method" class="w-full mt-1 p-2 border rounded"/>
                        </div>
                    </div>
                    <div class="flex gap-6 w-1/4">
                        <div class="mb-4 w-1/2">
                            <label class="block text-gray-700 dark:text-gray-200">Is Credit</label>
                            <select v-model="form.is_credit" class="w-full mt-1 p-2 border rounded">
                                <option :value="false">No</option>
                                <option :value="true">Yes</option>
                            </select>
                        </div>
                        <div class="mb-4 w-1/2">
                            <label class="block text-gray-700 dark:text-gray-200">Is Paid</label>
                            <select v-model="form.is_paid" class="w-full mt-1 p-2 border rounded">
                                <option :value="false">No</option>
                                <option :value="true">Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Products Section -->
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
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <button type="button" @click="removeProduct(index)"
                                class="bg-red-500 text-white px-4 py-2 rounded">Remove
                        </button>
                    </div>
                    <button type="button" @click="addProduct" class="bg-green-500 text-white px-4 py-2 rounded">Add
                        Product
                    </button>
                </div>

                <!-- Total Section -->
                <div class="flex gap-6">
                    <div class="mb-4 w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Total</label>
                        <input type="number" v-model="total" class="w-full mt-1 p-2 border rounded" readonly/>
                    </div>
                    <div class="mb-4 w-1/2">
                        <label class="block text-gray-700 dark:text-gray-200">Order Date</label>
                        <input type="date" v-model="form.order_date" class="w-full mt-1 p-2 border rounded"/>
                    </div>
                </div>
                <!-- Form Actions -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
