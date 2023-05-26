<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    user: Object,
    categories: Object,
})

const successMessage = ref('')

const form = useForm({
    name: props.user.name,
    username: props.user.username,
    email: props.user.content,
    category: props.user.category.id,
})

const submitForm = async () => {
    // form.put(route('admin.poems.update', props.poem.slug))

    const response = await form.put(route('admin.poems.update', props.poem.slug))

    if (response.success) {
        successMessage.value = response.success
    }
}
</script>

<template>
    <Head title="Edit" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Eserler</h2>
        </template>

        <div v-if="successMessage" class="text-green-500">{{ successMessage }}</div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="container mx-auto py-4">
                    <form @submit.prevent="submitForm">
                        <div class="flex flex-col mb-4">
                            <label class="mb-2 font-semibold text-gray-700 dark:text-white" for="title">
                                Name:
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                id="title"
                                name="title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter the user's name"
                                required
                            />
                        </div>


                        <div class="flex flex-col mb-4">
                            <label class="mb-2 font-semibold text-gray-700 dark:text-white" for="title">
                                Username:
                            </label>
                            <input
                                v-model="form.username"
                                type="text"
                                id="title"
                                name="title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter the username"
                                required
                            />
                        </div>

                        <div class="flex flex-col mb-4">
                            <label class="mb-2 font-semibold text-gray-700 dark:text-white" for="title">
                                Email:
                            </label>
                            <input
                                v-model="form.email"
                                type="text"
                                id="title"
                                name="title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter the email"
                                required
                            />
                        </div>

                        <div class="flex flex-col mb-4">
                            <label for="author">Category:</label>
                            <select v-model="form.category" id="category" name="category"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    required>
                                <option disabled value="">Select an author</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id" :selected="form.category === category.id">{{ category.name }}</option>
                            </select>
                        </div>




                        <button type="submit" class="py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>





