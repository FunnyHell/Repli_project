<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed, ref} from "vue";

const props = defineProps({
    products: Array
});

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
</script>

<template>
    <Head title="Welcome"/>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="flex justify-center">
                <input type="text" v-model="searchQuery" placeholder="Поиск по продуктам..."
                       class="mb-4 p-2 w-1/3 text-black mx-auto"/>
            </div>
            <table class="table-fixed text-white w-3/4 mx-auto">
                <tr>
                    <th class="border-amber-100 border">
                        Product name
                    </th>
                    <th class="border-amber-100 border">
                        Category
                    </th>
                    <th class="border-amber-100 border">
                        Price
                    </th>
                    <th class="border-amber-100 border">
                        Quantity
                    </th>
                </tr>
                <tr v-for="product in filteredProducts">
                    <td class="border-amber-100 border p-2">
                        {{ product.name }}
                    </td>
                    <td class="border-amber-100 border p-2">
                        {{ product.category.name }}
                    </td>
                    <td class="border-amber-100 border p-2">
                        {{ product.price }}
                    </td>
                    <td class="border-amber-100 border p-2">
                        {{ product.product_existence ?  product.product_existence.quantity : 'Out of stock' }}
                    </td>
                </tr>
            </table>
        </div>
    </AuthenticatedLayout>

</template>
