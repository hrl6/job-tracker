<!-- resources/js/Pages/Dashboard.vue -->
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    recentApplications: Array,
    statusCounts: Object,
    upcomingInterviews: Array,
    activitySummary: Object,
});

// Format date helper
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Get status color
const getStatusColor = (statusName) => {
    const colors = {
        'Wishlist': 'gray',
        'Applied': 'blue',
        'Phone Screen': 'indigo',
        'Interview': 'purple',
        'Offer': 'green',
        'Accepted': 'emerald',
        'Rejected': 'red',
        'Withdrawn': 'gray'
    };
    return colors[statusName] || 'gray';
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
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
                <!-- Activity Summary -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Applications This Week</div>
                            <div class="mt-2 text-3xl font-semibold">
                                {{ activitySummary.applications_this_week }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Active Applications</div>
                            <div class="mt-2 text-3xl font-semibold">
                                {{ activitySummary.active_applications }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Upcoming Interviews</div>
                            <div class="mt-2 text-3xl font-semibold">
                                {{ activitySummary.interviews_this_week }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Response Rate</div>
                            <div class="mt-2 text-3xl font-semibold">
                                {{ Math.round(activitySummary.response_rate) }}%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Applications -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Recent Applications</h3>
                                    <Link :href="route('jobs.index')" class="text-blue-500 hover:text-blue-600">
                                        View All
                                    </Link>
                                </div>
                                <div class="space-y-4">
                                    <div v-for="app in recentApplications" :key="app.id" 
                                        class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition">
                                        <div class="flex-1">
                                            <h4 class="font-medium">{{ app.job_title }}</h4>
                                            <p class="text-sm text-gray-500">{{ app.company.name }}</p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span :class="`px-3 py-1 rounded-full text-sm font-medium bg-${getStatusColor(app.status.name)}-100 text-${getStatusColor(app.status.name)}-800`">
                                                {{ app.status.name }}
                                            </span>
                                            <Link :href="route('jobs.edit', app.id)" 
                                                class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Overview & Upcoming Interviews -->
                    <div class="space-y-6">
                        <!-- Status Overview -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium mb-4">Status Overview</h3>
                                <div class="space-y-3">
                                    <div v-for="(count, status) in statusCounts" :key="status" 
                                        class="flex items-center justify-between">
                                        <span class="text-sm font-medium" 
                                            :class="`text-${getStatusColor(status)}-600`">
                                            {{ status }}
                                        </span>
                                        <span class="text-sm font-medium">{{ count }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Interviews -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium mb-4">Upcoming Interviews</h3>
                                <div v-if="upcomingInterviews.length === 0" 
                                    class="text-gray-500 text-sm">
                                    No upcoming interviews this week
                                </div>
                                <div v-else class="space-y-4">
                                    <div v-for="interview in upcomingInterviews" :key="interview.id" 
                                        class="border-l-4 border-blue-500 pl-4">
                                        <div class="font-medium">{{ interview.job_title }}</div>
                                        <div class="text-sm text-gray-500">{{ interview.company.name }}</div>
                                        <div class="text-sm text-gray-400">
                                            {{ formatDate(interview.notes[0].reminder_date) }}
                                        </div>
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