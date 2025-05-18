<script setup lang="ts">
import Form from './Form.vue'

defineProps({
    post: Object,
    categories: Array,
    users: Array,
    tags: Array,
})

const handleSubmit = (form) => {
    form.put(route('admin.posts.update', post.id))
}
</script>

<template>
    <div>
        <h1 class="text-xl font-bold mb-4">Uredi post</h1>
        <Form :post="post" :categories="categories" :users="users" :tags="tags" @submit="handleSubmit" />
    </div>
</template>
