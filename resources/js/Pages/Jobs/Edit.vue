<!-- resources/js/Pages/Jobs/Edit.vue -->
<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3'

const props = defineProps({
    jobApplication: Object,
    statuses: Array,
    companies: Array,
});

const form = useForm({
    job_title: props.jobApplication.job_title,
    company_id: props.jobApplication.company_id,
    status_id: props.jobApplication.status_id,
    job_description: props.jobApplication.job_description || '',
    salary_range: props.jobApplication.salary_range || '',
    applied_date: props.jobApplication.applied_date,
    job_location: props.jobApplication.job_location || '',
    job_type: props.jobApplication.job_type || '',
    application_url: props.jobApplication.application_url || '',
    requirements: props.jobApplication.requirements || '',
    is_remote: props.jobApplication.is_remote || false,
});

const submitForm = () => {
    form.put(route('jobs.update', props.jobApplication.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Show success notification
            showNotification('Job application updated successfully');
        },
    });
};

const deleteJob = () => {
    if (confirm('Are you sure you want to delete this job application?')) {
        router.delete(route('jobs.destroy', props.jobApplication.id), {
            onSuccess: () => {
                // Redirect will happen automatically
            },
        });
    }
};

// Notification system
const notification = ref({
    show: false,
    message: '',
});

const showNotification = (message) => {
    notification.value.message = message;
    notification.value.show = true;
    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};
</script>

<template>
    <Head title="Edit Job Application" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Job Application
                </h2>
                <button 
                    @click="deleteJob"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                >
                    Delete Application
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Success Notification -->
                <div 
                    v-if="notification.show"
                    class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative"
                    role="alert"
                >
                    <span class="block sm:inline">{{ notification.message }}</span>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Job Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Job Title</label>
                                <input 
                                    v-model="form.job_title" 
                                    type="text" 
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                />
                                <div v-if="form.errors.job_title" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.job_title }}
                                </div>
                            </div>

                            <!-- Company -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Company</label>
                                <select 
                                    v-model="form.company_id" 
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                >
                                    <option 
                                        v-for="company in companies" 
                                        :key="company.id" 
                                        :value="company.id"
                                    >
                                        {{ company.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.company_id" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.company_id }}
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Application Status</label>
                                <select 
                                    v-model="form.status_id" 
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                >
                                    <option 
                                        v-for="status in statuses" 
                                        :key="status.id" 
                                        :value="status.id"
                                    >
                                        {{ status.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.status_id" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.status_id }}
                                </div>
                            </div>

                            <!-- Applied Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Applied Date</label>
                                <input 
                                    v-model="form.applied_date" 
                                    type="date" 
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                />
                            </div>

                            <!-- Salary Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Salary Range</label>
                                <input 
                                    v-model="form.salary_range" 
                                    type="text" 
                                    placeholder="e.g., $80,000 - $100,000"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                />
                            </div>

                            <!-- Job Location -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Job Location</label>
                                <input 
                                    v-model="form.job_location" 
                                    type="text" 
                                    placeholder="City, State"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                />
                            </div>

                            <!-- Job Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Job Type</label>
                                <select 
                                    v-model="form.job_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                >
                                    <option value="">Select Job Type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Freelance">Freelance</option>
                                </select>
                            </div>

                            <!-- Remote Option -->
                            <div class="flex items-center">
                                <input 
                                    v-model="form.is_remote"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                />
                                <label class="ml-2 text-sm text-gray-600">Is this a remote position?</label>
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Job Description</label>
                            <textarea 
                                v-model="form.job_description"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Paste job description here..."
                            ></textarea>
                        </div>

                        <!-- Requirements -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Requirements</label>
                            <textarea 
                                v-model="form.requirements"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="List key requirements..."
                            ></textarea>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <Link 
                                :href="route('jobs.index')"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                            >
                                Cancel
                            </Link>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition disabled:opacity-50"
                            >
                                {{ form.processing ? 'Saving...' : 'Update Job Application' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>