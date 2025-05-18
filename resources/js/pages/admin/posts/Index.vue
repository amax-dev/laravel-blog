<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@/types';
import {PostPagination} from '@/types';
import IndexTable from '@/pages/admin/posts/partials/IndexTable.vue';

  const props = defineProps<{
      posts: PostPagination,
  }>()





const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Lista članaka',
        href: '/admin/posts',
    },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Lista članaka" />

      <div class="p-4">

          <IndexTable :posts="props.posts"/>

      </div>

  </AppLayout>
</template>
