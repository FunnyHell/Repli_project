<script setup>
import {Inertia} from '@inertiajs/inertia';
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed, ref} from "vue";

const props = defineProps({
    orders: Array
});

const deleteOrder = async (id) => {
    if (confirm('Are you sure you want to delete this order?')) {
        Inertia.delete(route('orders.destroy', {id: id}));
    }
};

const searchQuery = ref('');

const filteredOrders = computed(() => {
    return props.orders.filter(order =>
        `${order.client.name} ${order.client.surname}`.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const getCurrentTheme = () => {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};
</script>

<template>
    <Head title="Orders"/>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="flex justify-center items-center mb-4">
                <input type="text" v-model="searchQuery" placeholder="Search by client..."
                       class="p-2 w-2/3 md:w-1/3 text-black mx-6"/>
                <form :action="route('orders.create')" method="get">
                    <button type="submit">
                        <img v-if="getCurrentTheme() === 'dark'" :src="'storage/icons/add-dark.png'"
                             alt="Add new order" class="w-8 h-8 hover:scale-125 transition-all">
                        <img v-else :src="'storage/icons/add-light.png'" alt="Add new order"
                             class="w-8 h-8 hover:scale-125 transition-all">
                    </button>
                </form>
            </div>

            <table class="table-fixed dark:text-white w-3/4 mx-auto text-sm xl:text-base">
                <thead>
                <tr>
                    <th class="dark:border-amber-100 border-gray-950 border">Order ID</th>
                    <th class="dark:border-amber-100 border-gray-950 border">Client Name</th>
                    <th class="dark:border-amber-100 border-gray-950 border">Employee Name</th>
                    <th class="dark:border-amber-100 border-gray-950 border">Products</th>
                    <th class="w-2/12 md:w-1/12"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in filteredOrders" :key="order.id" class="h-24">
                    <a :href="route('orders.show', {id: order.id})" class="contents">
                        <td class="dark:border-amber-100 border-gray-950 border p-2">{{ order.id }}</td>
                        <td class="dark:border-amber-100 border-gray-950 border p-2">
                            {{ order.client.name }} {{ order.client.surname }}
                        </td>
                        <td class="dark:border-amber-100 border-gray-950 border p-2">
                            {{ order.employee.name }} {{ order.employee.surname }}
                        </td>
                        <td class="dark:border-amber-100 border-gray-950 border p-2">
                            <ul>
                                <li v-for="product in order.product" :key="product.id">{{ product.name }}</li>
                            </ul>
                        </td>
                    </a>
                    <td class="dark:border-amber-100 border-gray-950 border md:p-2 text-center">
                        <button @click="deleteOrder(order.id)" class="mx-2">
                            <img v-if="getCurrentTheme() === 'dark'" :src="'storage/icons/trash-dark.png'"
                                 alt="Delete order" class="w-6 h-6 hover:scale-125 transition-all">
                            <img v-else :src="'storage/icons/trash-light.png'" alt="Delete order"
                                 class="w-6 h-6 hover:scale-125 transition-all">
                        </button>
                    </td>

                </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
