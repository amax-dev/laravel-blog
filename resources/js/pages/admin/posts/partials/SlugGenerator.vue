<script setup lang="ts">

import axios from 'axios';
import { computed, watch } from 'vue';
import { Categories} from '@/types';

const props = defineProps<{
    title: string;
    selected_category_id: number;
    categories: Categories[];
}>();



const slug = defineModel<string>('slug');
const slugLoading = defineModel<boolean>('slugLoading');

const slugCategory = computed(() =>
    props.categories.find(cat => cat.id === props.selected_category_id)?.slug ?? 'novosti'
)

const generateSlug = () => {
    if (!props.title) {
        slug.value = ''
        return
    }
    try {
        axios.post('/api/generate-slug', { title: props.title })
            .then(response => {
                slug.value = response.data.slug;
            });
    } catch (e: object | any) {
        slug.value = 'Greška pri generisanju: ' + e.message;
    } finally {

        slugLoading.value =false
    }
}



watch(slugLoading, generateSlug);




</script>

<template>
        <span v-if="slug">analiticar.me/{{slugCategory}}/{{slug}}</span>
</template>
