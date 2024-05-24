<script setup>
import {computed, ref} from 'vue';
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip} from 'chart.js';
import {Inertia} from "@inertiajs/inertia";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const props = defineProps({
    salesData: Array,
    refundsData: Array,
    transferStatuses: Array,
    startDate: String,
    endDate: String,
});

const columns = computed(() => {
    return props.transferStatuses.map(status => ({
        status: status.status,
        total: status.total,
        items: status.items,
    }));
});


const form = ref({
    startDate: props.startDate,
    endDate: props.endDate,
});

const salesChartData = computed(() => {
    const labels = props.salesData.map(item => item.date);
    const sales = props.salesData.map(item => item.total_sales);
    const refunds = props.refundsData.map(item => item.total_refunds);

    return {
        labels,
        datasets: [
            {
                label: 'Sales',
                data: sales,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
            },
            {
                label: 'Refunds',
                data: refunds,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
            },
        ],
    };
});

const salesCountChartData = computed(() => {
    const labels = props.salesData.map(item => item.date);
    const sales = props.salesData.map(item => item.total_orders);
    const refunds = props.refundsData.map(item => item.total_refunded_orders);

    return {
        labels,
        datasets: [
            {
                label: 'Sales',
                data: sales,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
            },
            {
                label: 'Refunds',
                data: refunds,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
            }
        ],
    };
})

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Sales and Refunds Data'
        }
    }
};

const submitForm = () => {
    Inertia.get(route('dashboard.index'), form.value, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard"/>
        <!--        <h1 class="text-2xl mb-4 dark:text-gray-100" v-for="el in props.orderStatuses">{{ el }}</h1>-->
        <div class="container mx-auto mb-10">
            <h1 class="text-3xl mb-6 dark:text-gray-100">Dashboard</h1>

            <!--            <div class="mb-10">-->
            <!--                <form @submit.prevent="submitForm" class="flex gap-4 mb-4">-->
            <!--                    <div>-->
            <!--                        <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>-->
            <!--                        <input type="date" id="startDate" v-model="form.startDate" class="mt-1 block w-full"/>-->
            <!--                    </div>-->
            <!--                    <div>-->
            <!--                        <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>-->
            <!--                        <input type="date" id="endDate" v-model="form.endDate" class="mt-1 block w-full"/>-->
            <!--                    </div>-->
            <!--                    <div class="flex items-end">-->
            <!--                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Apply</button>-->
            <!--                    </div>-->
            <!--                </form>-->
            <!--                <div class="px-32">-->
            <!--                    <Bar :data="salesChartData" :options="chartOptions"/>-->
            <!--                    <Bar :data="salesCountChartData" :options="chartOptions"/>-->
            <!--                </div>-->
            <!--            </div>-->

            <div class="mb-10">
                <div class="container mx-auto mt-10">
                    <h1 class="text-3xl mb-6">Kanban Board</h1>
                    <div class="flex gap-4">
                        <div
                            class="kanban-column"
                            v-for="column in columns"
                            :key="column.status"
                        >
                            <h2 class="text-xl mb-4">{{ column.status }} ({{ column.total }})</h2>
                            <div class="kanban-tasks">
                                <div
                                    class="kanban-task"
                                    v-for="task in column.items"
                                    :key="task.id"
                                >
                                    {{ task }} - {{ task }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
