<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, Link} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import {onMounted} from "vue";

const props = defineProps({
    startDate: String,
    endDate: String,
    topPoems: Object,
    newPoems: Object,
    newComments: Object
})

</script>

<template>
    <Head title="Dashboard"/>

    <Layout>

        <div class="mx-auto flex flex-col-reverse justify-between md:flex-row lg:gap-x-10"
             style="max-width: 900px;">
            <div class="forum-main mx-auto w-full md:flex-1 xl:max-w-[835px]">
                <div class="mb-4 border-b border-gray-200 sticky overflow-auto">
                    <ul class="flex text-sm font-medium text-center" id="default-tab"
                        data-tabs-toggle="#default-tab-content" role="tablist">
                        <li class="w-full" role="presentation">
                            <button class="w-full inline-block p-4 border-b-2 rounded-t-lg border-transparent aria-selected:border-blue-500" id="new-tab"
                                    data-tabs-target="#new" type="button" role="tab"
                                    aria-selected="true">Täze eserler
                            </button>
                        </li>
                        <li class="w-full" role="presentation">
                            <button class="w-full inline-block p-4 border-b-2 rounded-t-lg border-transparent aria-selected:border-blue-500 aria-selected:font-semibold"
                                id="profile-tab" data-tabs-target="#top" type="button" role="tab"
                                aria-selected="false">Köp okalanlar
                            </button>
                        </li>

                    </ul>
                </div>
                <div id="default-tab-content">
                    <div class="p-4 " id="new" role="tabpanel">
                        <div class="columns-1 max-w-sm">
                            <div class="mb-10" v-for="poem in newPoems">
                                <p class="text-gray-500 pb-2">{{ poem.user.name }} | {{ poem.date }}</p>
                                <h5 class="font-sans text-2xl font-semibold text-gray-900 text-xl pb-2">{{ poem.title }}</h5>
                                <span v-for="tag in poem.tags" class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">{{tag.name }}</span>
                                <pre class="font-serif text-gray-700 text-lg pb-2">{{ poem.content }}</pre>
                                <Link :href="route('poems.show', { slug: poem.slug })"
                                      class="inline-flex items-center text-blue-600 hover:underline">Dowamyny oka...
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="top" role="tabpanel">
                        <div class="columns-1 max-w-sm">
                            <div class="mb-10" v-for="poem in topPoems">
                                <p class="text-gray-500 pb-2">{{ poem.user.name }} | {{ poem.date }}</p>
                                <h5 class="font-sans text-2xl font-semibold text-gray-900 text-xl pb-2">{{ poem.title }}</h5>
                                <span v-for="tag in poem.tags" class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">{{tag.name }}</span>
                                <pre class="font-serif text-gray-700 text-lg pb-2">{{ poem.content }}</pre>
                                <Link :href="route('poems.show', { slug: poem.slug })"
                                      class="inline-flex items-center text-blue-600 hover:underline">Dowamyny oka...
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="sticky hidden h-screen w-[266px] 2xl:block p-4 rounded-lg" style="top: 90px;">
                <div class="max-h-screen space-y-4 overflow-auto pb-15">
                    <h2 class="font-semibold text-l text-gray-800 ">
                        Gürrüňe goşul
                    </h2>
                    <div class="mb-10 border-gray-200 border-b" v-for="comment in newComments">
                        <Link :href="route('poems.show', { slug: comment.poem.slug })" class="text-gray-900 text-sm font-semibold pb-2">{{ comment.poem.title }}</Link>
                        <div class="text-gray-500 text-sm pb-2">{{ comment.body }}</div>
                    </div>
                </div>
            </aside>
        </div>
    </Layout>
</template>
