<script>
import Layout from '@/Layouts/Layout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head,Link, useForm, usePage } from '@inertiajs/vue3'

export default {
    components:{
        Head,
        Link,
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
    },
    layout:Layout,
    props:{
        tag:Object,
    },
    remember: 'form',
    data(){
        return{
            form:this.$inertia.form({
                _method:'put',
                name: this.tag.name,
                description: this.tag.description,
            })
        }
    },
    methods:{
        update(){
            this.form.post(`/dash/tags/${this.tag.id}`)
        }
    }
}
</script>

<template>
    <Head title="Edit Tag"/>

    <Layout>

        <div class="sm:items-center sm:justify-between">
            <p class="font-semibold text-2xl text-gray-800 dark:text-white">Edit {{tag.name}}</p>
        </div>

        <div class="w-full lg:w-9/12 xl:w-4/5 mt-6">
                <form class="space-y-6 max-w-md" @submit.prevent="update">

                    <div>
                        <InputLabel for="name" value="Name"/>

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                        />

                        <InputError class="mt-2" :message="form.errors.name"/>
                    </div>

                    <div>
                        <InputLabel for="description" value="Description"/>

                        <div class="mt-1 rounded-md">
                            <textarea
                                v-model="form.description"
                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                name="description" rows="3"></textarea>
                        </div>

                        <InputError class="mt-2" :message="form.errors.description"/>
                    </div>

                    <div class="flex items-center gap-4 mb-6">
                        <PrimaryButton class="mb-4" :disabled="form.processing">Save</PrimaryButton>

                        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                            <p v-if="form.recentlySuccessful" class="text-x text-green-600 dark:text-green-500">Saved!</p>
                        </Transition>
                    </div>
                </form>
            </div>


    </Layout>
</template>
