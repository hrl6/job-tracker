<!-- resources/js/Pages/Jobs/Index.vue -->
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    jobApplications: Array,
    statuses: Array
});

const getStatusColor = (statusId) => {
    const status = props.statuses.find(s => s.id === statusId);
    return status ? status.color : '#718096';
};
</script>

<template>
    <Head title="Job Applications" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Job Applications
                </h2>
                <Link 
                    :href="route('jobs.create')" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition"
                >
                    Add New Application
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Status Filter Cards -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div 
                        v-for="status in statuses" 
                        :key="status.id"
                        class="bg-white rounded-lg shadow p-4"
                    >
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ status.name }}</span>
                            <span 
                                class="px-2 py-1 rounded text-sm font-semibold"
                                :style="{ backgroundColor: status.color + '20', color: status.color }"
                            >
                                {{ jobApplications.filter(job => job.status_id === status.id).length }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Job Applications List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="jobApplications.length === 0" class="text-center py-8 text-gray-500">
                            No job applications yet. Start by adding one!
                        </div>
                        <div v-else class="space-y-4">
                            <div 
                                v-for="job in jobApplications" 
                                :key="job.id"
                                class="border rounded-lg p-4 hover:shadow-md transition"
                            >
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-lg">{{ job.job_title }}</h3>
                                        <p class="text-gray-600">{{ job.company.name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span 
                                            class="px-3 py-1 rounded-full text-sm"
                                            :style="{ 
                                                backgroundColor: getStatusColor(job.status_id) + '20',
                                                color: getStatusColor(job.status_id)
                                            }"
                                        >
                                            {{ job.status.name }}
                                        </span>
                                        <Link 
                                            :href="route('jobs.edit', job.id)"
                                            class="text-blue-500 hover:text-blue-600"
                                        >
                                            Edit
                                        </Link>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    <div class="flex space-x-4">
                                        <span>📅 Applied: {{ new Date(job.applied_date).toLocaleDateString() }}</span>
                                        <span v-if="job.job_location">📍 {{ job.job_location }}</span>
                                        <span v-if="job.job_type">💼 {{ job.job_type }}</span>
                                        <span v-if="job.is_remote">🏠 Remote</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>