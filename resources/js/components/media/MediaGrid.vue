<script lang="ts" setup>
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue';
import axios from 'axios';
import MediaItemCard from '@/components/media/MediaItemCard.vue';
import type { MediaItem } from '@/types';
import { debounce } from 'lodash-es';

const emit = defineEmits<{
    (e: 'select', item: MediaItem): void;
}>();

// Stanje komponente
const loading = ref(false);
const error = ref<string | null>(null);
const items = ref<MediaItem[]>([]);
const searchQuery = ref('');
const currentPage = ref(1);
const lastPage = ref(1);
const perPage = 24;

// Debounce pretrage
const debouncedSearch = debounce(() => {
    currentPage.value = 1;
    fetchData();
}, 500);

// Pratimo promjene u pretrazi
watch(searchQuery, debouncedSearch);

// Funkcija za dobavljanje podataka
async function fetchData() {
    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get(route('api.media'), {
            params: {
                search: searchQuery.value,
                page: currentPage.value,
                per_page: perPage,
            },
        });

        // Ako je prva stranica, resetuj listu
        if (currentPage.value === 1) {
            items.value = response.data.data;
        } else {
            // Ako je sledeća stranica, dodaj na postojeću listu
            items.value = [...items.value, ...response.data.data];
        }

        currentPage.value = response.data.current_page;
        lastPage.value = response.data.last_page;
    } catch (err) {
        error.value = 'Došlo je do greške pri učitavanju medija';
        console.error(err);
    } finally {
        loading.value = false;
    }
}

// Učitaj prvu stranicu prilikom mount-a
onMounted(() => {
    fetchData();
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

// Funkcija za učitavanje sljedeće stranice
async function loadMore() {
    if (currentPage.value < lastPage.value) {
        currentPage.value++;
        const lastItemId = items.value.length ? items.value[items.value.length - 1].id : null;
        await fetchData();
        await nextTick();
        if (lastItemId) {
            const el = document.getElementById('media-item-' + lastItemId);
            el?.scrollIntoView({ behavior: 'instant', block: 'end', inline: 'nearest' });
        }
    }
}

function handleScroll() {
    const scrollBottom = window.innerHeight + window.scrollY;
    const threshold = document.documentElement.offsetHeight - 100;

    if (scrollBottom >= threshold && !loading.value && currentPage.value < lastPage.value) {
        loadMore();
    }
}

// Prosljeđivanje selektovanog itema parentu
function handleSelect(item: MediaItem) {
    emit('select', item);
}
</script>

<template>
    <div>
        <!-- Search input -->
        <input v-model="searchQuery" class="mb-4 w-full rounded border px-4 py-2" placeholder="Pretraži medije..." type="text" />

        <!-- Loading state -->
        <div v-if="loading && currentPage > 1" class="py-4 text-center">
            <div class="inline-block animate-spin ..."></div>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="py-6 text-center text-red-500">
            {{ error }}
        </div>

        <!-- Rezultati -->
        <template v-else>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-4 lg:gap-5 xl:grid-cols-6">
                <MediaItemCard v-for="item in items" :id="'media-item-' + item.id" :key="item.id" :item="item" @click="handleSelect(item)" />
            </div>

            <!-- Prazna lista -->
            <div v-if="items.length === 0" class="text-muted py-6 text-center">Nema pronađenih rezultata</div>

            <!-- Paginacija -->
            <div v-if="currentPage < lastPage" class="mt-8 text-center">
                <button
                    :disabled="loading"
                    class="bg-primary hover:bg-primary-dark rounded px-6 py-2 text-xs text-white disabled:opacity-50"
                    @click="loadMore"
                >
                    {{ loading ? 'Učitavam...' : 'Učitaj više' }}
                </button>
            </div>
        </template>
    </div>
</template>
