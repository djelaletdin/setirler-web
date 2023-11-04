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


    <div class="mx-auto flex flex-col flex-col-reverse justify-between md:flex-row lg:gap-x-10" style="max-width: 900px;">
            <div class="forum-main mx-auto w-full md:flex-1 xl:max-w-[835px]">
                <h2 class="font-bold text-xl text-gray-800  leading-tight mb-5">
                    Täze eserler
                </h2>

                <div class="columns-1 max-w-sm">
                    <div class="mb-10" v-for="poem in newPoems" >
                        <p>{{ poem.user.name }}</p>
                        <h5 class="font-sans text-2xl font-semibold text-gray-900 text-xl pb-2">{{ poem.title }}</h5>
                        <span v-for="tag in poem.tags" class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ tag.name }}</span>
                        <pre class="font-serif text-gray-700 text-lg">{{poem.content}}</pre>
                        <Link :href="route('poems.show', { slug: poem.slug })" class="inline-flex items-center text-blue-600 hover:underline">Dowamyny oka...</Link>
                    </div>
                </div>
        </div>

        <aside class="sticky hidden h-screen max-w-[266px] 2xl:block bg-[#1da1f2]" style="top: 90px;">
            <div class="max-h-screen space-y-4 overflow-auto pb-15">
                <h2 class="font-semibold text-l text-gray-800 ">
                    Geçen hepdäniň iň köp okalanlary {{ startDate }} - {{ endDate }}
                </h2>

                <ol>
                    <Link :href="route('poems.show', { slug: poem.slug })" v-for="poem in topPoems">
                        <li>{{ poem.title }}</li>
                    </Link>
                </ol>
            </div>
        </aside>
    </div>
    </Layout>
</template>
