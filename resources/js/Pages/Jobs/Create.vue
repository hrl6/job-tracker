<!-- resources/js/Pages/Jobs/Create.vue -->
<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    statuses: Array,
    companies: Array,
});

const form = useForm({
    job_title: '',
    company_id: null,
    status_id: null,
    job_description: '',
    salary_range: '',
    applied_date: new Date().toISOString().split('T')[0],
    job_location: '',
    job_type: '',
    application_url: '',
    requirements: '',
    is_remote: false
});

const submitForm = () => {
    form.post(route('jobs.store'), {
        onFinish: () => {
            if (!form.hasErrors) {
                form.reset();
            }
        }
    });
};

// Create a new company modal state
const isNewCompanyModalOpen = ref(false);
const newCompanyName = ref('');

const createNewCompany = () => {
    if (newCompanyName.value.trim()) {
        axios.post(route('companies.store'), { 
            name: newCompanyName.value 
        }).then(response => {
            props.companies.push(response.data);
            form.company_id = response.data.id;
            isNewCompanyModalOpen.value = false;
            newCompanyName.value = '';
        }).catch(error => {
            console.error('Error creating company', error);
        });
    }
};
</script>

<template>
    <Head title="Add New Job Application" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Add New Job Application
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                                <div class="flex items-center space-x-2">
                                    <select 
                                        v-model="form.company_id" 
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    >
                                        <option value="" disabled>Select a company</option>
                                        <option 
                                            v-for="company in companies" 
                                            :key="company.id" 
                                            :value="company.id"
                                        >
                                            {{ company.name }}
                                        </option>
                                    </select>
                                    <button 
                                        type="button"
                                        @click="isNewCompanyModalOpen = true"
                                        class="mt-1 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 transition"
                                    >
                                        +
                                    </button>
                                </div>
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
                                    <option value="" disabled>Select a status</option>
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

                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end">
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition disabled:opacity-50"
                            >
                                {{ form.processing ? 'Saving...' : 'Save Job Application' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- New Company Modal -->
        <div v-if="isNewCompanyModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl">
                <h3 class="text-lg font-semibold mb-4">Add New Company</h3>
                <input 
                    v-model="newCompanyName"
                    type="text"
                    placeholder="Company Name"
                    class="w-full mb-4 rounded-md border-gray-300"
                />
                <div class="flex justify-end space-x-2">
                    <button 
                        @click="isNewCompanyModalOpen = false"
                        class="px-4 py-2 bg-gray-200 rounded-md"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="createNewCompany"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md"
                    >
                        Create
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>