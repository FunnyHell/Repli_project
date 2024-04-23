<script setup>
import {Inertia} from '@inertiajs/inertia';
import {Head, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed, onBeforeUnmount, ref} from "vue";

const props = defineProps({
    products: Array
});

const deleteProduct = async (id) => {
    console.log(usePage().props.auth.user);
    if (confirm('Are you sure you want to delete this product?')) {
        Inertia.delete(route('products.destroy', {id: id}));
    }
}

const searchQuery = ref('');

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

const filteredProducts = computed(() => {
    return props.products.filter(product =>
        product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        product.category.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const getCurrentTheme = () => {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

onBeforeUnmount(() => {
    axios.post('/clear-session');
});
</script>

<template>
    <Head title="Welcome"/>
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
                <span class="font-medium">Product wasn't deleted!</span>
            </div>
        </div>

        <div class="py-12">
            <div class="flex justify-center items-center">
                <input type="text" v-model="searchQuery" placeholder="Поиск по продуктам..."
                       class="mb-4 p-2 w-2/3 md:w-1/3 text-black mx-6
                        md:w-1/2"/>
                <form :action="route('products.index')" method="get" class="mb-4">
                    <button type="submit">
                        <img v-if="getCurrentTheme() === 'dark'" :src="'storage/icons/add-dark.png'"
                             alt="Add new product" class="w-8 h-8 hover:scale-125 transition-all">
                        <img v-else :src="'storage/icons/add-light.png'" alt="Add new product"
                             class="w-8 h-8 hover:scale-125 transition-all">
                    </button>
                </form>
            </div>

            <table class="table-fixed dark:text-white w-3/4 mx-auto text-sm xl:text-base">
                <thead>
                <tr>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell md:w-2/12">
                        Image
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border w-3/12 md:w-3/12">
                        Product name
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border hidden md:table-cell md:w-2/12">
                        Category
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border w-2/12 md:w-2/12">
                        Price
                    </th>
                    <th class="dark:border-amber-100 border-gray-950 border w-2/12 md:w-1/12 truncate">
                        Quantity
                    </th>
                    <th class="w-2/12 md:w-1/12"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in filteredProducts">
                    <td class="dark:border-amber-100 border-gray-950 border hidden md:table-cell">
                        <img v-if="product.product_image[0]" :src="'storage/'+product.product_image[0].source"
                             :alt="product.name+' Image'" class="w-full ">
                        <img v-else :src="'storage/placeholder.png'" :alt="product.name+' Image'"
                             class="rounded m-auto my-2">
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border truncate text-pretty relative">
                        <a :href="route('products.show', product.id)"
                           class="p-6 rounded hover:bg-gray-300 dark:hover:bg-gray-700 hover:underline flex items-center
                           absolute left-0 right-0 top-0 bottom-0">
                            {{ product.name }}
                        </a>
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 hidden md:table-cell text-pretty">
                        {{ product.category.name }}
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 text-center md:text-left text-pretty">
                        {{ product.price }} $
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border p-2 truncate text-pretty">
                        {{ product.product_existence ? product.product_existence.quantity : 'Out of stock' }}
                    </td>
                    <td class="dark:border-amber-100 border-gray-950 border md:p-2 text-center">
                        <button @click="deleteProduct(product.id)" class="mx-auto my-2.5">
                            <img v-if="getCurrentTheme() === 'dark'" :src="'storage/icons/trash-dark.png'"
                                 alt="Add new product" class="w-8 h-8 hover:scale-125 transition-all">
                            <img v-else :src="'storage/icons/trash-light.png'" alt="Add new product"
                                 class="w-8 h-8 hover:scale-125 transition-all">
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>

</template>
