<script setup>
import { watch, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import debounce from "lodash/debounce";

const props = defineProps({
    users: Object,
    filter: String
})

const search = ref(props.filter);

watch(search, debounce(function (value) {
    router.get('/admin/users', { search: value}, { preserveState: true })
    console.log(value)
}, 300));

</script>

<template>
    <Head title="Admin Eserler" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Eserler</h2>
        </template>


            <div class="relative overflow-x-auto border border-gray-100 sm:rounded-lg">
                <div class="p-4 bg-white dark:bg-gray-900">
                    <label for="table-search" class="sr-only">Search</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input v-model="search"  type="text" id="table-search" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
                    </div>
                </div>

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Ady
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            View
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="users" v-for="user in users.data" class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            <Link :href="route('admin.users.edit', { user: user.username })" class="font-medium hover:underline">{{ user.name }}</Link>
                        </th>
                        <td class="px-6 py-4">
                            @{{ user.username }}
                        </td>
                        <td class="px-6 py-4">
                            {{ user.email}}
                        </td>
                        <td class="px-6 py-4">
                            {{ user.category.name }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="users.links" class="mt-6" />


    </AdminLayout>
</template>
