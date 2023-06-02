<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, Link} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    topPoems: Object,
})

</script>

<template>
    <Head title="Dashboard" />

    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-l text-gray-800  leading-tight">
                    Geçen hepdäniň iň köp okalanlary
                </h2>

                <div class="grid grid-cols-1 px-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link :href="route('poems.show', { slug: poem.slug })" v-for="poem in topPoems" class="p-4 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md transition duration-200 ease-in-out">
                        <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">{{ poem.title }}</h5>
                        <span v-for="tag in poem.tags" class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ tag.name }}</span>
                    </Link>
                </div>
            </div>
        </div>
    </Layout>
</template>
