<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem, MediaItem } from '@/types';
import MediaUpload from '@/components/media/MediaUpload.vue';
import { ref } from 'vue';
import MediaItemCard from '@/components/media/MediaItemCard.vue';



const uploadedFile = ref<MediaItem>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Novi fajl',
        href: '/admin/media/create',
    },
];

function handleUploaded(item: MediaItem) {
    uploadedFile.value = item;

}


</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Dodaj novi fajl" />

      <div class="p-4">
          <MediaUpload @uploaded="handleUploaded"/>

          <MediaItemCard v-if="uploadedFile" :item="uploadedFile"/>


      </div>

  </AppLayout>
</template>
