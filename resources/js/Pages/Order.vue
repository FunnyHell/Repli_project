<script setup>
import {Inertia} from '@inertiajs/inertia';
import {Head, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {computed, onBeforeUnmount, ref} from "vue";

const props = defineProps({
    order: Object
})
</script>

<template>
    <Head :title="props.order.name"/>
    <AuthenticatedLayout>
        <h1 class="text-white"> {{props.order}} </h1>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
