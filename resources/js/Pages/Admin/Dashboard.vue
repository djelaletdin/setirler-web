<script setup>
import {watch, ref} from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import debounce from "lodash/debounce";

const props = defineProps({
    visitorCounts: Object,
    unpublishedPoems: Object,
    selectedDay: Number,
    poemCount: Number,
})

</script>

<template>

    <AdminLayout>

        <Head title="Dolandyryş Paneli" />

        <main>
            <div class="relative isolate overflow-hidden">
                <!-- Secondary navigation -->
                <header class="sm:pb-6">
                    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-6 px-4 sm:flex-nowrap sm:px-6 lg:px-8">
                        <h1 class="text-base font-semibold leading-7 text-gray-900">Hasabat</h1>
                        <div class="order-last flex w-full gap-x-8 text-sm font-semibold leading-6 sm:order-none sm:w-auto sm:border-l sm:border-gray-200 sm:pl-6 sm:leading-7">
                            <Link :href="route('admin.dashboard.index', { d: 7 })" :class="selectedDay === 7 ? 'text-indigo-600' : 'text-gray-700'">Soňky 7 gün</Link>
                            <Link :href="route('admin.dashboard.index', { d: 30 })" :class="selectedDay === 30 ? 'text-indigo-600' : 'text-gray-700'">Soňky 30 gün</Link>
                            <Link :href="route('admin.dashboard.index', { d: 0 })" :class="selectedDay === 0 ? 'text-indigo-600' : 'text-gray-700'">Hemmesi</Link>
                        </div>
                    </div>
                </header>

                <!-- Stats -->
                <div class="border-b border-b-gray-900/10 lg:border-t lg:border-t-gray-900/5">
                    <dl class="mx-auto grid max-w-7xl grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 lg:px-2 xl:px-0">
                        <div class="flex items-baseline flex-wrap justify-between gap-y-2 gap-x-4 border-t border-gray-900/5 px-4 py-10 sm:px-6 lg:border-t-0 xl:px-8">
                            <dt class="text-sm font-medium leading-6 text-gray-500">Eserler</dt>
                            <dd class="text-xs font-medium text-green-400">+54.02%</dd>
                            <dd class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-gray-900">
                                {{ poemCount }}</dd>
                        </div>
                        <div class="flex items-baseline flex-wrap justify-between gap-y-2 gap-x-4 border-t border-gray-900/5 px-4 py-10 sm:px-6 lg:border-t-0 xl:px-8 sm:border-l">
                            <dt class="text-sm font-medium leading-6 text-gray-500">Ulanyjylar</dt>
                            <div class="w-full flex-none">
                                <dd class=" text-3xl font-medium leading-10 tracking-tight text-gray-900">{{visitorCounts.unique}}</dd>
                                <dd class="w-full flex-none text-lg font-small leading-10 tracking-tight text-gray-500"> ({{visitorCounts.total}})</dd>
                            </div>
                        </div>
                    </dl>
                </div>

                <div class="absolute left-0 top-full -z-10 mt-96 origin-top-left translate-y-40 -rotate-90 transform-gpu opacity-20 blur-3xl sm:left-1/2 sm:-ml-96 sm:-mt-10 sm:translate-y-0 sm:rotate-0 sm:transform-gpu sm:opacity-50" aria-hidden="true">
                    <div class="aspect-[1154/678] w-[72.125rem] bg-gradient-to-br from-[#F1950e] to-[#D4F1F4]" style="clip-path: polygon(100% 38.5%, 82.6% 100%, 60.2% 37.7%, 52.4% 32.1%, 47.5% 41.8%, 45.2% 65.6%, 27.5% 23.4%, 0.1% 35.3%, 17.9% 0%, 27.7% 23.4%, 76.2% 2.5%, 74.2% 56%, 100% 38.5%)"></div>
                </div>
            </div>

            <div class="space-y-16 py-16 xl:space-y-20">
                <!-- Recent activity table -->
                <div>
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <h2 class="mx-auto max-w-2xl text-base font-semibold leading-6 text-gray-900 lg:mx-0 lg:max-w-none">Goşulmadyk goşgular</h2>
                    </div>
                    <div class="mt-6 overflow-hidden border-t border-gray-100">
                        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
                                <table class="w-full text-left">
                                    <thead class="sr-only">
                                    <tr>
                                        <th>Amount</th>
                                        <th class="hidden sm:table-cell">Client</th>
                                        <th>More details</th>
                                    </tr>
                                    </thead>
                                    <tbody>



                                    <tr  v-for="poem in unpublishedPoems">
                                        <td  class="relative py-5 pr-6">
                                            <div class="flex gap-x-6">
                                                <svg class="hidden h-auto w-5 flex-none text-gray-400 sm:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.337 21.718a6.707 6.707 0 0 1-.533-.074.75.75 0 0 1-.44-1.223 3.73 3.73 0 0 0 .814-1.686c.023-.115-.022-.317-.254-.543C3.274 16.587 2.25 14.41 2.25 12c0-5.03 4.428-9 9.75-9s9.75 3.97 9.75 9c0 5.03-4.428 9-9.75 9-.833 0-1.643-.097-2.417-.279a6.721 6.721 0 0 1-4.246.997Z" clip-rule="evenodd" />
                                                </svg>


                                                <div class="flex-auto">
                                                    <div class="text-sm font-medium leading-6 text-gray-900">{{poem.title}}</div>

                                                    <div class="mt-1 text-xs leading-5 text-gray-500">{{ poem.user.name }}</div>
                                                </div>
                                            </div>
                                            <div class="absolute bottom-0 right-full h-px w-screen bg-gray-100"></div>
                                            <div class="absolute bottom-0 left-0 h-px w-screen bg-gray-100"></div>
                                        </td>
                                        <td class="hidden py-5 pr-6 sm:table-cell">
                                            <div class="mt-1 text-sm leading-5 text-gray-500">{{poem.date}}</div>
                                        </td>
                                        <td class="py-5 text-right">
                                            <div class="flex justify-end">
                                                <div @click="" class="cursor-pointer text-sm font-medium leading-6 text-indigo-600 hover:text-indigo-500">
                                                    Publish
                                                    </div>
                                            </div>

                                            <div class="mt-1 text-xs leading-5 text-gray-500">
                                                <div class="flex items-center justify-end gap-x-2">
                                                    <div class="flex-none rounded-full p-1 text-orange-400 bg-orange-400/10">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                                    </div>
                                                    <div class="hidden sm:block">Pending</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </AdminLayout>
</template>
