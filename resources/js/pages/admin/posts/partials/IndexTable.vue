<script lang="ts" setup>
import { router } from '@inertiajs/vue3'
import { PostPagination } from '@/types';


 defineProps<{
    posts: PostPagination,
}>()

function editPost(id: number) {
    router.visit(`/admin/posts/${id}/edit`)
}
function deletePost(id: number) {
    if (confirm('Da li ste sigurni da želite obrisati post?')) {
        router.delete(`/admin/posts/${id}`)
    }
}
function goToPage(url: string) {
    router.visit(url)
}
function formatDate(dateStr: string) {
    const date = new Date(dateStr)
    return date.toLocaleDateString('sr-ME', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}
</script>

<template>
    <div class="container mx-auto">
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naslov</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum objave</th>
                    <th class="px-6 py-3"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                <tr v-for="post in posts.data" :key="post.id">
                    <td class="px-6 py-4">
                        <p class="font-semibold break-words">
                            {{ post.title }}
                        </p>
                        <p class="text-sm">tema: {{post.category.name}} | autor: {{post.author.name}}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ formatDate(post.published_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <button
                            @click="editPost(post.id)"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition"
                        >
                            Izmijeni
                        </button>
                        <button
                            @click="deletePost(post.id)"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 transition"
                        >
                            Obriši
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="px-6 py-3 flex justify-between items-center">
                <button
                    v-if="posts.prev_page_url"
                    @click="goToPage(posts.prev_page_url!)"
                    class="text-blue-600 hover:underline cursor-pointer hover:text-blue-700 transition"
                >Prethodna</button>
                <span>Strana {{ posts.current_page }} od {{ posts.last_page }}</span>
                <button
                    v-if="posts.next_page_url"
                    @click="goToPage(posts.next_page_url!)"
                    class="text-blue-600 hover:underline cursor-pointer hover:text-blue-700 transition"
                >Sljedeća</button>
            </div>
        </div>
    </div>
</template>
