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
        user:Object,
    },
    remember: 'form',
    data(){
        return{
            form:this.$inertia.form({
                _method:'put',
                name: this.user.name,
                email: this.user.email,
                username: this.user.username,
                photo: null,
                info: this.user.info,
            })
        }
    },
    methods:{
        update(){
            this.form.post(`/dash/users/${this.user.id}`, {
                onSuccess : () => this.form.reset('password,photo')
            })
        }
    }
}
</script>

<template>
    <Head title="Edit User"/>

    <Layout>

        <div class="sm:items-center sm:justify-between">
            <p class="font-semibold text-2xl text-gray-800 dark:text-white">Edit {{user.name}}</p>
        </div>

        <div class="relative flex flex-col px-10 py-8 lg:flex-row">
            <div class="flex justify-start w-full mb-8 lg:w-3/12 xl:w-1/5 lg:m-b0">
                <div class="relative w-32 h-32 cursor-pointer group">
                    <img id="preview" v-if="user.photo" :src="user.photo" class="w-32 h-32 rounded-full object-cover">

                    <div class="absolute inset-0 w-full h-full">
                        <input type="file" id="upload"
                               class="absolute inset-0 z-20 w-full h-full opacity-0 cursor-pointer group">
                        <input type="hidden" id="uploadBase64" name="avatar">

                        <button
                            class="absolute bottom-0 z-10 flex items-center justify-center w-10 h-10 mb-2 -ml-5 bg-black bg-opacity-75 rounded-full opacity-75 group-hover:opacity-100 left-1/2">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-9/12 xl:w-4/5">
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
                        <InputLabel for="username" value="Username"/>

                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.username"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.username"/>
                    </div>

                    <div>
                        <InputLabel for="email" value="Email"/>

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.email"/>
                    </div>

                    <div>
                        <InputLabel for="info" value="Info"/>

                        <div class="mt-1 rounded-md">
                            <textarea
                                v-model="form.info"
                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                name="info" rows="3"></textarea>
                        </div>

                        <InputError class="mt-2" :message="form.errors.info"/>
                    </div>

                    <!--                Dropdown radio-->
                    <div>
                        <InputLabel class="mt-6" for="category" value="Category"/>

                        <button id="dropdownRadioBgHoverButton" data-dropdown-toggle="dropdownRadioBgHover"
                                class="mt-1 text-gray-800 bg-white hover:bg-gray-100 shadow focus:ring-2 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                type="button">Category 2
                            <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdownRadioBgHover"
                             class="z-10 hidden mt-2 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600">

                            <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownRadioBgHoverButton">
                                <li>
                                    <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <input id="default-radio-4" type="radio" value="" name="category1"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="default-radio-4"
                                               class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Category
                                            1</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <input checked id="default-radio-5" type="radio" value="" name="category2"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="default-radio-5"
                                               class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Category
                                            2</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <input id="default-radio-6" type="radio" value="" name="category3"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                        <label for="default-radio-6"
                                               class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Category
                                            3</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mb-6">
                        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                            <p v-if="form.recentlySuccessful" class="text-x text-green-600 dark:text-green-500">Saved!</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </div>

    </Layout>
</template>
