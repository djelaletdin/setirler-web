<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, Link} from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    poem: Object,
    comments: Object,
    totalViews: Number,
    uniqueViews: Number,
    userLikedPoem: Boolean,
})

const form = useForm({
    body: null,
})

function vote(comment, direction) {
    router.post(`/comments/${comment.id}/votes`, { direction: direction }, {preserveScroll: true})
    // comment.votes_count = comment.votes_count + direction
}

function canDelete(userId) {
    const user = usePage().props.auth.user;
    return user && userId === user.id;
}

function deleteComment(comment) {
    router.delete(`/comments/${comment}/`, {preserveScroll: true})
}

function like(poem) {
    // router.post(`/poems/${poem}/like`)
    router.post(`/poems/${poem}/like`, {}, {preserveScroll: true, only: ['userLikedPoem']})

}

form.reset()

</script>

<template>
    <Head title="Ýazyjylar" ></Head>

    <Layout>
        <div class="py-12 flex items-center">
            <div class="max-w-prose mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col justify-center items-center gap-1 my-3">
<!--                    <div class="m-auto flex flex-col justify-center items-center">-->
                        <h2 class="font-semibold text-center text-2xl text-gray-800">{{ poem.title }}</h2>
                        <Link :href="route('users.show', { user: poem.user.username })" class="text-gray-500 text-lg hover:underline">{{ poem.user.name }}</Link>
<!--                    </div>-->
                </div>
                <pre class="text-lg font-serif text-gray-800">{{ poem.content }}</pre>
                <div class="flex gap-4 justify-center" >

                    <div class="flex items-center gap-0.5 min-w-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="min-w-0 w-6 h-6 stroke-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <h2 class="min-w-0 font-normal text-center text-md  text-gray-400 my-4">{{ uniqueViews }} Görüldi</h2>
                    </div>

                    <div class="flex items-center gap-0.5 min-w-0">
                        <button @click="like(poem.slug)" class="flex min-w-0">
                            <svg v-if="!userLikedPoem" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"  class="w-6 h-6 stroke-gray-400 min-w-0 hover:fill-red-500 hover:stroke-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>

                            <svg v-if="userLikedPoem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 fill-red-600 min-w-0">
                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                            </svg>
                        </button>
                        <h2 class="min-w-0 font-normal text-center text-md  text-gray-400 my-4">1 Halanan</h2>
                    </div>
                </div>




                <Link :href="route('tags.show', { tag: tag.slug })" v-for="tag in poem.tags" class="bg-white text-gray-800 text-s font-medium mr-2 px-2.5 py-1.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ tag.name }}</Link>

            </div>
        </div>

        <ol v-if="comments.length" class="relative mx-auto w-96">
            <li v-for="comment in comments" :key="comment.id" class="mb-4 ml-6">
                <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg">
                    <div class="flex gap-2 mb-2 text-sm ">
                        <div class="font-semibold">{{ comment.user.name }}</div>
                        <time class="mb-1 font-normal text-gray-400 sm:order-last sm:mb-0">{{ comment.date }}</time>

                    </div>
                    <div class="text-base font-normal text-gray-500 dark:text-gray-300">{{ comment.body }}</div>
                    <div class="flex justify-between my-2">
                        <div class="rounded-l flex flex-auto">
                            <button :class="{ 'bg-green-100 border-green-200': comment.userVote === 1 }" class="border rounded hover:border-green-500 group hover:bg-green-100 "  @click="vote(comment, 1)">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" :class="{ 'fill-green-400': comment.userVote === 1 }"   class="w-5 h-5 fill-gray-500 group-hover:fill-green-500" >
                                    <path fill-rule="evenodd" d="M10 15a.75.75 0 01-.75-.75V7.612L7.29 9.77a.75.75 0 01-1.08-1.04l3.25-3.5a.75.75 0 011.08 0l3.25 3.5a.75.75 0 11-1.08 1.04l-1.96-2.158v6.638A.75.75 0 0110 15z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div :class="{ 'text-green-500 font-bold': comment.userVote === 1,  'text-red-500 font-bold': comment.userVote === -1}" class="font-semibold px-2 text-gray-500">{{ comment.votes_count }}</div>

                            <button :class="{ 'bg-red-100 border-red-200': comment.userVote === -1 }"  class="border rounded hover:border-red-500 group hover:bg-red-100 "  @click="vote(comment, -1)">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" :class="{ 'fill-red-400': comment.userVote === -1 }"  class="w-5 h-5 fill-gray-500 group-hover:fill-red-500 rotate-180">
                                    <path fill-rule="evenodd" d="M10 15a.75.75 0 01-.75-.75V7.612L7.29 9.77a.75.75 0 01-1.08-1.04l3.25-3.5a.75.75 0 011.08 0l3.25 3.5a.75.75 0 11-1.08 1.04l-1.96-2.158v6.638A.75.75 0 0110 15z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <button v-if="canDelete(comment.user_id)" @click="deleteComment(comment.id)" class="text-sm font-normal text-gray-500 hover:underline hover:text-red-600">
                            Poz
                        </button>
                    </div>

                </div>
            </li>
        </ol>

        <form @submit.prevent="form.post(`/poems/${poem.slug}/comments`, { preserveScroll: true, onSuccess: () => form.reset('body') })">
            <div class="w-96 mx-auto mb-4 border border-gray-200 rounded-lg">
                <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                    <label for="body" class="sr-only">Teswir</label>
                    <textarea id="body" v-model="form.body" rows="4" class="w-full p-2 text-sm text-gray-900 bg-gray-50 border-0 rounded-lg focus:ring-0" placeholder="Teswir ýazyň..." required></textarea>
                </div>
                <div class="flex items-center justify-end py-2 m-2 border-t">
                    <button type="submit" :disabled="form.processing" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                        Ugrat
                    </button>
                </div>
            </div>
        </form>

    </Layout>
</template>
