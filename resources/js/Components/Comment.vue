<script setup>
import Layout from '@/Layouts/Layout.vue';
import {Head, Link} from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    comment: Object
})

const isReplyVisible = ref(false);

const form = useForm({
    body: null,
})

function canDelete(userId) {
    const user = usePage().props.auth.user;
    return user && userId === user.id;
}


const toggleReply = () => {
    isReplyVisible.value=!isReplyVisible.value;
}

</script>

<template>
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

        <button @click="toggleReply" class="text-sm font-normal text-gray-500 hover:underline hover:text-blue-600">
                Jogapla
        </button>

    </div>


</div>


<!-- replying starts here -->
<div v-if="isReplyVisible">
<div class="text-gray-300 font-medium pl-6">|</div>

<form @submit.prevent="form.post(`/poems/${poem.slug}/comments`, { preserveScroll: true, onSuccess: () => form.reset('body') })" class="w-96 mx-auto my-2 pl-4">
<div class="mb-4 border border-gray-200 rounded-lg">
    <div class="px-4 py-2 bg-white rounded-t-lg dark:bg-gray-800">
        <label for="body" class="sr-only">Teswir</label>
        <textarea id="body" v-model="form.body" rows="4" class="my-2 w-full p-2 text-sm text-gray-900 bg-gray-50 border-0 rounded-lg focus:ring-0" placeholder="Teswir ýazyň..." required></textarea>
    </div>
        <div class="flex items-center justify-end py-2 m-2 border-t">
            <button type="submit" :disabled="form.processing" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-black rounded-lg focus:ring-4 focus:ring-gray-200 hover:bg-gray-800">
                Ugrat
            </button>
        </div>
    </div>
</form>
</div>
</template>
