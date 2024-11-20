<!-- resources/js/Pages/Analytics/Index.vue -->
<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VueApexCharts from 'vue3-apexcharts';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    statusDistribution: Object,
    applicationsOverTime: Array,
    responseRate: Number,
    commonJobTitles: Array,
});

// Donut chart options for status distribution
const statusChartOptions = computed(() => ({
    chart: {
        type: 'donut',
    },
    labels: Object.keys(props.statusDistribution),
    colors: ['#4299E1', '#48BB78', '#805AD5', '#38B2AC', '#38A169', '#E53E3E', '#718096'],
    plotOptions: {
        pie: {
            donut: {
                size: '70%'
            }
        }
    },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 300
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
}));

// Line chart options for applications over time
const timelineChartOptions = computed(() => ({
    chart: {
        type: 'area',
        height: 350
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        type: 'datetime'
    },
    tooltip: {
        x: {
            format: 'dd MMM yyyy'
        }
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.9,
        }
    }
}));
</script>

<template>
    <Head title="Analytics" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analytics Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-500 text-sm font-medium">Total Applications</h3>
                        <p class="text-3xl font-bold mt-2">{{ stats.total }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-500 text-sm font-medium">Active Applications</h3>
                        <p class="text-3xl font-bold mt-2">{{ stats.active }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-500 text-sm font-medium">Applications This Month</h3>
                        <p class="text-3xl font-bold mt-2">{{ stats.this_month }}</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-500 text-sm font-medium">Response Rate</h3>
                        <p class="text-3xl font-bold mt-2">{{ responseRate }}%</p>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Status Distribution Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Application Status Distribution</h3>
                        <apexchart
                            type="donut"
                            :options="statusChartOptions"
                            :series="Object.values(statusDistribution)"
                        ></apexchart>
                    </div>

                    <!-- Applications Over Time Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Applications Over Time</h3>
                        <apexchart
                            type="area"
                            :options="timelineChartOptions"
                            :series="[{
                                name: 'Applications',
                                data: applicationsOverTime
                            }]"
                        ></apexchart>
                    </div>
                </div>

                <!-- Common Job Titles -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Most Common Job Titles</h3>
                    <div class="space-y-4">
                        <div 
                            v-for="title in commonJobTitles" 
                            :key="title.job_title"
                            class="flex items-center"
                        >
                            <div class="flex-1">
                                <h4 class="font-medium">{{ title.job_title }}</h4>
                            </div>
                            <div class="w-64 h-4 bg-gray-200 rounded-full overflow-hidden">
                                <div 
                                    class="h-full bg-blue-500"
                                    :style="{
                                        width: `${(title.count / commonJobTitles[0].count) * 100}%`
                                    }"
                                ></div>
                            </div>
                            <span class="ml-4 text-gray-600">{{ title.count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>