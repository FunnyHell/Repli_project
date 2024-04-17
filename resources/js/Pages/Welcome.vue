<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    products: Array
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <ul>
        <li>
            <div>
                Product: {{ products[0].name }} <br>
                Feature: {{ products[0].feature }} <br>
                Category: {{ products[0].category }} <br>
                Supply Request: {{ products[0].supply_request }} <br>
                Order: {{ products[0].order }} <br>
                Product Existence: {{ products[0].product_existence }} <br>
            </div>
        </li>
    </ul>
</template>
