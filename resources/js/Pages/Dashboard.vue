<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, Link} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    startDate: String,
    endDate: String,
    topPoems: Object,
    newPoems: Object
})

</script>

<template>
    <Head title="Dashboard" />

    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>



        <div class="flex-auto w-full min-w-0 lg:static lg:max-h-full lg:overflow-visible">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-bold text-xl text-gray-800  leading-tight mb-5">
                    Täze eserler
                </h2>

                <div class="columns-1 max-w-sm">
                    <div class="mb-10" v-for="poem in newPoems" >
                        <h5 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white">{{ poem.title }}</h5>
                        <span v-for="tag in poem.tags" class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ tag.name }}</span>
                        <pre class="font-sans">{{poem.content}}</pre>
                        <Link :href="route('poems.show', { slug: poem.slug })" class="inline-flex items-center text-blue-600 hover:underline">Dowamyny oka...</Link>
                    </div>
                </div>
            </div>
        </div>

        <aside class="fixed inset-0 z-20 flex-none bg-blue-50 hidden h-full w-72 lg:static lg:h-auto lg:overflow-y-visible lg:pt-0 lg:w-48 lg:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-l text-gray-800  leading-tight">
                    Geçen hepdäniň iň köp okalanlary {{ startDate }} - {{ endDate }}
                </h2>

                <ol>
                    <Link :href="route('poems.show', { slug: poem.slug })" v-for="poem in topPoems">
                        <li>{{ poem.title }}</li>
                    </Link>
                </ol>
            </div>
        </aside>

    </Layout>
</template>
