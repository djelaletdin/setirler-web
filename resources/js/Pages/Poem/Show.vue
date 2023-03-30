<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, Link} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
const props = defineProps({
    poem: Object,
    totalViews: Number
})

</script>

<template>
    <Head title="Ýazyjylar" />

    <Layout>
        <div class="py-12 flex items-center">
            <div class="max-w-prose mx-auto sm:px-6 lg:px-8">
                <a :href="route('users.poems', { user: poem.user.username })" class="text-gray-500 hover:underline">{{ poem.user.name }}</a>
                <h2 class="font-semibold text-center text-xl  text-gray-800 my-4">{{ poem.title }}</h2>
                <pre class="text-lg font-serif">{{ poem.content }}</pre>
                <h2 class="font-semibold text-center text-md  text-gray-400 my-4">{{ totalViews }} gezek görüldi</h2>
            </div>
        </div>

    </Layout>
</template>
