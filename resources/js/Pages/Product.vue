<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";

const props = defineProps({
    product: Object
})
const orders = props.product.order
const currentImage = ref(props.product.product_image[0] ? '/storage/' + props.product.product_image[0].source : '/storage/placeholder.png');

function setCurrentImage(imageSource) {
    currentImage.value = '/storage/' + imageSource;
}

</script>

<template>
    <Head :title="props.product.name"/>

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
        </div>

        <div v-if="product.order[0]"
                 class="hidden sm:block w-3/4 dark:bg-gray-800 bg-white-200 rounded-lg mt-10 mx-auto py-12">
            <h1 class="text-white text-4xl text-center my-10">
                Orders
            </h1>
            <table class="table-fixed dark:text-white w-5/6 mx-auto text-sm xl:text-base">
                <thead>
                <tr>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell md:w-3/12">
                        Client
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell md:w-3/12">
                        Employee
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell md:w-2/12">
                        Total
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell md:w-1/12">
                        Order ID
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell md:w-1/12">
                        Payment method
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell text-wrap md:w-1/12">
                        Credit (?)
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell md:w-1/12">
                        Paid (?)
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in orders">
                    <td class="dark:border-amber-100 border-gray-950 border p-2 hidden md:table-cell text-pretty">
                        {{ order.client.surname }} {{ order.client.name }}
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 hidden md:table-cell text-pretty">
                        <p class="underline decoration-solid">{{ order.employee.position }}</p>
                        <p>{{ order.employee.surname }} {{ order.employee.name }}</p>
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 hidden md:table-cell text-pretty">
                        {{ order.total }} $
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 hidden md:table-cell text-pretty hover:underline">
                        <a :href="route('orders.show', order.id)">{{ order.id }}</a>
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 hidden md:table-cell text-pretty">
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

    </AuthenticatedLayout>
</template>
