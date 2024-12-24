<template>
    <div class="flex justify-center items-center">
        <div class="relative w-full max-w-4xl">
            <label for="search-field" class="sr-only">Gözleg</label>
            <svg class="pointer-events-none absolute inset-y-0 left-2.5 h-full w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
            <input
                type="text"
                v-model="query"
                @input="debouncedSearch"
                placeholder="Search"
                class="bg-primary-50 border border-gray-300 text-primary-900 sm:text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full lg:w-[36rem] pl-10 p-2.5"
            />

            <!-- Dropdown Menu -->
            <div
                v-if="resultsVisible"
                ref="dropdownContainer"
            class="absolute z-50 bg-white border border-gray-300 rounded-lg mt-2 w-full max-h-60 overflow-y-auto shadow-lg"
            >
            <!-- Users Section -->
            <div v-if="users.length" class="px-4 py-2">
                <h3 class="font-bold text-sm text-gray-500">Awtorlar</h3>
                <ul>
                    <li v-for="user in users" :key="user.id" class="py-1">
                        <Link :href="route('users.show', { user: user.username })" class="text-primary-600 hover:underline">
                            {{ user.name }}
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Poem Titles Section -->
            <div v-if="poemTitles.length" class="px-4 py-2">
                <h3 class="font-bold text-sm text-gray-500">Eserler</h3>
                <ul>
                    <li v-for="poem in poemTitles" :key="poem.id" class="py-1">
                        <Link :href="route('poems.show', { slug: poem.slug })" class="text-primary-600 hover:underline">
                            {{ poem.title }}
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Poem Contents Section -->
            <div v-if="poemContents.length" class="px-4 py-2">
                <h3 class="font-bold text-sm text-gray-500">Setirler</h3>
                <ul>
                    <li v-for="poem in poemContents" :key="poem.id" class="py-1">
                        <Link :href="route('poems.show', { slug: poem.slug })" class="text-primary-600 hover:underline">
                            {{ poem.content }}
                        </Link>
                    </li>
                </ul>
            </div>
                <div v-if="!poemTitles.length && !poemContents.length && !users.length" class="px-4 py-2">
                    <div class="text-primary-600 py-5 flex items-center justify-center h-20">
                        Gözleg netije tapylmady
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const query = ref("");
const users = ref([]);
const poemTitles = ref([]);
const poemContents = ref([]);
const resultsVisible = ref(false);
const dropdownContainer = ref(null);

// Debounce timeout ID
let debounceTimeout = null;

// Search function that is called after a delay
const search = async () => {
    if (query.value.length < 2) {
        resultsVisible.value = false;
        return;
    }

    try {
        const response = await axios.get(`/search`, { params: { q: query.value } });
        users.value = response.data.users;
        poemTitles.value = response.data.poemTitles;
        poemContents.value = response.data.poemContents;
        resultsVisible.value = true;
    } catch (error) {
        console.error("Error fetching search results:", error);
        resultsVisible.value = false;
    }
};

// Debounced search function
const debouncedSearch = () => {
    if (debounceTimeout) {
        clearTimeout(debounceTimeout);
    }
    console.log("i am here");
    debounceTimeout = setTimeout(search, 500);
};

// Detect clicks outside the dropdown
const handleOutsideClick = (event) => {
    if (
        dropdownContainer.value &&
        !dropdownContainer.value.contains(event.target)
    ) {
        resultsVisible.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleOutsideClick);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleOutsideClick);
});
</script>
