<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    poem: Object,
    users: Object
})

const successMessage = ref('')

const form = useForm({
    title: props.poem.title,
    author: props.poem.user.id,
    content: props.poem.content,
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
                                Title:
                            </label>
                            <input
                                v-model="form.title"
                                type="text"
                                id="title"
                                name="title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter the poem title"
                                required
                            />
                        </div>

                        <div class="flex flex-col mb-4">
                            <label for="author">Author:</label>
                            <select v-model="form.author" id="author" name="author"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    required>
                                <option disabled value="">Select an author</option>
                                <option v-for="user in users" :key="user.id" :value="user.id" :selected="form.author === user.id">{{ user.name }}</option>
                            </select>
                        </div>

                        <div class="flex flex-col mb-4">
                            <label class="mb-2 font-semibold text-gray-700 dark:text-white" for="content">
                                Content:
                            </label>
                            <textarea
                                v-model="form.content"
                                id="content"
                                name="content"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 h-48 resize-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter the poem content"
                                required
                            ></textarea>
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





