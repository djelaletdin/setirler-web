<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, Link} from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    poem: Object,
    comments: Object,
    totalViews: Number
})

const form = useForm({
    body: null,
})

function vote(comment, direction) {
    router.post(`/comments/${comment.id}/votes`, { direction: direction }, {preserveScroll: true})
    // comment.votes_count = comment.votes_count + direction
}

form.reset()

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

        <ol v-if="comments.length" class="relative mx-auto w-96 border-l border-gray-200">
            <li v-for="comment in comments" :key="comment.id" class="mb-10 ml-6">
                <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">
                    <img class="rounded-full shadow-lg" src=""  alt=""/>
                </span>
                <div class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:bg-gray-700 dark:border-gray-600">
                    <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">{{ comment.created_at }}</time>
                    <div class="text-sm font-normal text-gray-500 dark:text-gray-300">{{ comment.body }}</div>
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
