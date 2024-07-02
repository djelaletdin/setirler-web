<script setup>
import {ref, watch} from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import debounce from "lodash/debounce";
import {useForm} from "@inertiajs/inertia-vue3";

const props = defineProps({
    poems: Object,
    filter: String
})

const search = ref(props.filter);

watch(search, debounce(function (value) {
    router.get('/admin/poems', { search: value}, { preserveState: true })
    console.log(value)
}, 300));


const successMessage = ref('')

const form = useForm({
    status: 1,
})

const makeStatusPublished = async(slug)=>{

    // form.status = 1;

    const response = await form.put(route('admin.poems.update_status', slug))

    if (response.success) {
        successMessage.value = response.success
    }

    console.log(props.poems[0])
}

</script>

<template>
    <Head title="Admin Eserler" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Eserler</h2>
        </template>



        <div class="m-8">
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
                            Eser ady
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Awtor
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tags
                        </th>
                        <th scope="col" class="px-6 py-3">
                            View
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="poems" v-for="poem in poems.data" class="bg-white border-b hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            <Link :href="route('admin.poems.edit', { poem: poem.slug })" class="font-medium hover:underline">{{ poem.title }}</Link>
                        </th>
                        <td class="px-6 py-4">
                            {{ poem.author_name}}
                        </td>
                        <td class="px-6 py-4">
                            <span v-for="tag in poem.tags" class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ tag.name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            {{ poem.view_count}}
                        </td>
                        <td class="px-6 py-4 text-sm leading-6 sm:pr-8 lg:pr-20">
                            <!--                                ACTIVE GREEN-->
                            <div v-if="poem.status===1" class="flex items-center justify-end gap-x-2 sm:justify-start">
                                <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">
                                    <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                </div>
                                <div class="hidden sm:block">Active</div>
                            </div>
                            <!--                                PENDING ORANGE-->
                            <div v-if="poem.status===0" class="flex items-center justify-end gap-x-2 sm:justify-start">
                                <div class="flex-none rounded-full p-1 text-orange-400 bg-orange-400/10">
                                    <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                </div>
                                <div class="hidden sm:block">Pending</div>
                                <button @click="makeStatusPublished(poem,poem)" type="button" class="ml-4 rounded-md px-6 py-2 font-medium text-green-500 hover:text-green-700 hover:bg-gray-200 inline-flex items-center gap-x-1.5 leading-6 ">

                                    <svg class="w-6 h-6 fill-green-100"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>

                                    Publish
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="poems.links" class="mt-6" />
        </div>

    </AdminLayout>
</template>
