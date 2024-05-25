<script setup>
import {computed, onMounted, ref} from 'vue';
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Bar} from 'vue-chartjs';
import {BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip} from 'chart.js';
import {Inertia} from '@inertiajs/inertia';
import Sortable from 'sortablejs';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const props = defineProps({
    salesData: Array,
    refundsData: Array,
    transferStatuses: Array,
    startDate: String,
    endDate: String,
});

const columns = ref(props.transferStatuses.map(status => ({
    status: status.status,
    total: status.total,
    items: status.items,
})));

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
});

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

const updateColumnData = (taskId, newStatus) => {
    // Найти задачу в текущих колонках и обновить ее статус
    let task;
    columns.value.forEach(column => {
        const index = column.items.findIndex(item => item.id === parseInt(taskId));
        if (index !== -1) {
            task = column.items.splice(index, 1)[0];
            column.total--;
        }
    });

    if (task) {
        // Обновить статус задачи и добавить в новую колонку
        task.status = newStatus;
        const newColumn = columns.value.find(column => column.status === newStatus);
        if (newColumn) {
            newColumn.items.push(task);
            newColumn.total++;
        }
    }
};

onMounted(() => {
    columns.value.forEach(column => {
        const el = document.querySelector(`.kanban-column[data-status="${column.status}"] .kanban-tasks`);
        Sortable.create(el, {
            group: 'kanban',
            animation: 150,
            onEnd: function (evt) {
                const taskId = evt.item.getAttribute('data-id');
                const newStatus = evt.to.closest('.kanban-column').getAttribute('data-status');

                // Обновление статуса задачи
                Inertia.put(route('transfers.updateStatus', taskId), {status: newStatus}, {
                    onSuccess: () => {
                        // Локальное обновление данных в колонках
                        updateColumnData(taskId, newStatus);
                    }
                });
            },
        });
    });
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard"/>
        <div class="mt-20 mb-6 ml-20">
            <a :href="route('products.create')" class="rounded-lg bg-blue-500 text-white py-2">
                <button class="p-4 ">Add Product</button>
            </a>
        </div>
        <div class="container mx-auto pb-10">
            <h1 class="text-3xl mb-6 dark:text-gray-100">Dashboard</h1>

            <div class="mb-10">
                <form @submit.prevent="submitForm" class="flex gap-4 mb-4">
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" id="startDate" v-model="form.startDate" class="mt-1 block w-full"/>
                    </div>
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" id="endDate" v-model="form.endDate" class="mt-1 block w-full"/>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Apply</button>
                    </div>
                </form>
                <div class="px-32">
                    <Bar :data="salesChartData" :options="chartOptions"/>
                    <Bar :data="salesCountChartData" :options="chartOptions"/>
                </div>
            </div>

            <div class="mb-10 px-20">
                <div class="container mt-10 dark:text-gray-100">
                    <h1 class="text-3xl mb-6">Kanban Board</h1>
                    <div class="flex gap-4">
                        <div
                            class="kanban-column border-2 rounded-lg p-6"
                            v-for="column in columns"
                            :key="column.status"
                            :data-status="column.status"
                        >
                            <h2 class="text-2xl text-center font-bold mb-4">{{ column.status }} ({{
                                    column.total
                                }})</h2>
                            <div class="kanban-tasks">
                                <div
                                    class="kanban-task"
                                    v-for="task in column.items"
                                    :key="task.id"
                                    :data-id="task.id"
                                >
                                    {{ task.product.id }}) {{ task.product.name }} - {{ task.transfer_date }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.kanban-column {
    flex: 1;
    padding: 20px;
    border-radius: 5px;
}

.kanban-tasks {
    min-height: 200px;
    padding: 10px;
}

.kanban-task {
    background-color: #496599;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
}
</style>
