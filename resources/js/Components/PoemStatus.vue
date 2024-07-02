<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: Boolean,
})

</script>

<template>
    <div class="flex items-center justify-end gap-x-2 sm:justify-start">
        <div v-if="status=true">
            <div class="flex-none rounded-full p-1 text-orange-400 bg-orange-400/10">
                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
            </div>
            <div class="hidden sm:block">Pending</div>
            <button type="button" class="ml-4 rounded-md px-6 py-2 font-medium text-green-500 hover:text-green-700 hover:bg-gray-200 inline-flex items-center gap-x-1.5 leading-6 ">

                <svg class="w-6 h-6 fill-green-100"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                Publish
            </button>
        </div>

        <div v-if="status=false">
            <div class="flex-none rounded-full p-1 text-green-400 bg-green-400/10">-->
                <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
            </div>
            <div class="hidden sm:block">Active</div>
        </div>

    </div>



    <!--                                </div>-->
    <!--                                Rejected RED-->
    <!--                                <div class="flex items-center justify-end gap-x-2 sm:justify-start">-->
    <!--                                    <div class="flex-none rounded-full p-1 text-red-600 bg-red-600/10">-->
    <!--                                        <div class="h-1.5 w-1.5 rounded-full bg-current"></div>-->
    <!--                                    </div>-->
    <!--                                    <div class="hidden sm:block">Rejected</div>-->


</template>
