<script setup lang="ts">
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import type { Category, Tag, User } from '@/types'

interface Props {
    post?: any
    categories: Category[]
    users: User[]
    tags: Tag[]
}

const props = defineProps<Props>()

const form = useForm({
    title: props.post?.title || { bs: '' },
    excerpt: props.post?.excerpt || { bs: '' },
    content: props.post?.content || { bs: '' },
    meta_title: props.post?.meta_title || { bs: '' },
    meta_description: props.post?.meta_description || { bs: '' },
    slug: props.post?.slug || '',
    status: props.post?.status || 'draft',
    published_at: props.post?.published_at || '',
    featured: props.post?.featured || false,
    author_id: props.post?.author_id || '',
    category_id: props.post?.category_id || '',
    tags: props.post?.tags?.map((t: any) => t.id) || [],
})

const isEdit = computed(() => !!props.post)

function submit() {
    if (isEdit.value) {
        form.put(route('admin.posts.update', props.post.id))
    } else {
        form.post(route('admin.posts.store'))
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <Label for="title">Naslov (BS)</Label>
            <Input v-model="form.title.bs" id="title" />
        </div>

        <div>
            <Label for="excerpt">Izvod</Label>
            <Textarea v-model="form.excerpt.bs" id="excerpt" rows="2" />
        </div>

        <div>
            <Label for="content">Sadržaj</Label>
            <Textarea v-model="form.content.bs" id="content" rows="5" />
        </div>

        <div>
            <Label for="meta_title">Meta naslov</Label>
            <Input v-model="form.meta_title.bs" id="meta_title" />
        </div>

        <div>
            <Label for="meta_description">Meta opis</Label>
            <Textarea v-model="form.meta_description.bs" id="meta_description" rows="2" />
        </div>

        <div>
            <Label for="slug">Slug</Label>
            <Input v-model="form.slug" id="slug" />
        </div>

        <div>
            <Label>Status</Label>
            <Select v-model="form.status">
                <SelectTrigger><SelectValue /></SelectTrigger>
                <SelectContent>
                    <SelectItem value="draft">Draft</SelectItem>
                    <SelectItem value="published">Objavljen</SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div>
            <Label for="published_at">Datum objave</Label>
            <Input type="datetime-local" v-model="form.published_at" id="published_at" />
        </div>

        <div>
            <Label for="author_id">Autor</Label>
            <Select v-model="form.author_id">
                <SelectTrigger><SelectValue /></SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="user in props.users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div>
            <Label for="category_id">Kategorija</Label>
            <Select v-model="form.category_id">
                <SelectTrigger><SelectValue /></SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="cat in props.categories" :key="cat.id" :value="cat.id">
                        {{ cat.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div>
            <Label for="tags">Tagovi</Label>
            <Select v-model="form.tags" multiple>
                <SelectTrigger><SelectValue /></SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="tag in props.tags" :key="tag.id" :value="tag.id">
                        {{ tag.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <Button type="submit" :disabled="form.processing">
            {{ isEdit ? 'Ažuriraj' : 'Kreiraj' }} post
        </Button>
    </form>
</template>
