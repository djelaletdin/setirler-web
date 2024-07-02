<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, Link} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
const props = defineProps({
    users: Object,
    categories: Object,
    selectedCategory: Number,
})

</script>

<template>
    <Head title="Ýazyjylar" />

    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Ýazyjylar</h2>
        </template>

        <div class="">

            <div class="flex items-center justify-center py-1.5 flex-wrap gap-4">
                <Link :href="route('users.index')" type="button" class="text-gray-900 border border-white hover:border-gray-300 rounded-lg text-xs font-medium px-5 py-2.5 text-center me-3 mb-3 dark:text-white dark:focus:ring-gray-800" :class="{ 'bg-black text-white border-0 font-semibold': selectedCategory === 0, 'bg-white': selectedCategory !== 0 }" preserve-scroll>
                    Hemmesi
                </Link>
                <Link :href="route('users.index', {id: category.id})" v-for="category in categories" type="button"  class="text-gray-900 border border-white hover:border-gray-300 rounded-lg text-xs font-medium px-5 py-2.5 text-center me-3 mb-3 dark:text-white dark:focus:ring-gray-800" :class="{ 'bg-black text-white border-0 font-semibold': selectedCategory === category.id, 'bg-white': selectedCategory !== category.id }" preserve-scroll>
                    {{ category.name }}
                </Link>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 px-4 gap-4 max-w-prose mx-auto">
                    <Link :href="route('users.show', { user: user.username })" v-for="user in users.data" class="p-4 bg-white border border-gray-200 rounded-lg hover:border-gray-300">
                        <h5 class="mb-0.5 text-lg font-bold tracking-tight text-gray-900 dark:text-white">{{ user.name }}</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">{{ user.poems_count }} eser</p>
                    </Link>
                </div>
                <Pagination :links="users.links" class="mt-6" />
            </div>
        </div>
    </Layout>
</template>
