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
    const user = usePage().props.auth;

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
                <Link :href="route('users.show', { user: poem.user.username })" class="text-gray-500 hover:underline">{{ poem.user.name }}</Link>
                <h2 class="font-semibold text-center text-xl  text-gray-800 my-4">{{ poem.title }}</h2>
                <pre class="text-lg font-serif">{{ poem.content }}</pre>
                <h2 class="font-semibold text-center text-md  text-gray-400 my-4">{{ uniqueViews }} tarapyndan {{ totalViews }} gezek görüldi</h2>
                <Link :href="route('tags.show', { tag: tag.slug })" v-for="tag in poem.tags" class="bg-white text-gray-800 text-s font-medium mr-2 px-2.5 py-1.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ tag.name }}</Link>
                <button @click="like(poem.slug)">

                    <svg v-if="!userLikedPoem" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>

                    <svg v-if="userLikedPoem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 fill-red-600">
                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                    </svg>

                </button>
            </div>
        </div>

        <ol v-if="comments.length" class="relative mx-auto w-96 border-l border-gray-200">
            <li v-for="comment in comments" :key="comment.id" class="mb-10 ml-6">
                <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">
                    <img class="rounded-full shadow-lg" src=""  alt=""/>
                </span>
                <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:bg-gray-700 dark:border-gray-600">
<!--                    <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">{{ comment.created_at }}</time>-->
                    <div class="text-sm font-normal text-gray-500 dark:text-gray-300">{{ comment.body }}</div>
                    <button v-if="canDelete(comment.user_id)" @click="deleteComment(comment.id)" class="text-sm font-normal text-gray-500 dark:text-gray-300">
                        Delete</button>
                </div>
                <div>
                    <button @click="vote(comment, 1)">
                        Upvote
                    </button>
                    {{ comment.votes_count }}
                    <button @click="vote(comment, -1)">
                        Downvote
                    </button>
                </div>
            </li>
        </ol>


        <form @submit.prevent="form.post(`/poems/${poem.slug}/comments`, { preserveScroll: true, onSuccess: () => form.reset('body') })">
            <div class="w-96 mx-auto mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
                    <label for="body" class="sr-only">Your comment</label>
                    <textarea id="body" v-model="form.body" rows="4" class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="Write a comment..." required></textarea>
                </div>
                <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600">
                    <button type="submit" :disabled="form.processing" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                        Post comment
                    </button>
                </div>
            </div>
        </form>

    </Layout>
</template>
