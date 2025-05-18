<script setup lang="ts">
import { ref, computed, nextTick } from 'vue'
import { debounce } from 'lodash-es'

interface Tag {
    id?: number
    name: string
}

// v-model:tags="form.tags" (niz objekata)
const tags = defineModel<Tag[]>('tags', { required: true })

const input = ref('')
const inputRef = ref<HTMLInputElement | null>(null)
const showSuggestions = ref(false)
const activeSuggestion = ref(-1)
const isLoading = ref(false)
const allTags = ref<Tag[]>([])

// Debounce pretraga tagova sa backend-a
const searchTags = debounce(async (query: string) => {
    try {
        isLoading.value = true
        const response = await fetch(`/api/tags/search?q=${encodeURIComponent(query)}`, {
            headers: { 'Accept': 'application/json' }
        })
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`)
        // Očekuješ niz objekata: [{id, name}, ...]
        allTags.value = await response.json()
    } catch (error) {
        allTags.value = []
        console.error('Greška pri pretrazi tagova:', error)
    } finally {
        isLoading.value = false
    }
}, 400)

const filteredTags = computed(() =>
    allTags.value.filter(
        tag =>
            tag.name.toLowerCase().includes(input.value.toLowerCase()) &&
            !tags.value.some(t => t.id === tag.id)
            && !tags.value.some(t => t.name.toLowerCase() === tag.name.toLowerCase())
    )
)

async function onInput() {
    if (input.value.length > 0) {
        await searchTags(input.value)
        showSuggestions.value = true
        activeSuggestion.value = -1
    } else {
        showSuggestions.value = false
    }
}

function addTag() {
    const tagName = input.value.trim()
    if (!tagName) return

    // Ako postoji predlog sa istim imenom, koristi njegov objekat
    const existing = allTags.value.find(
        t => t.name.toLowerCase() === tagName.toLowerCase()
    )
    if (existing && !tags.value.some(t => t.id === existing.id)) {
        tags.value = [...tags.value, existing]
    } else if (!tags.value.some(t => t.name.toLowerCase() === tagName.toLowerCase())) {
        tags.value = [...tags.value, { name: tagName }]
    }
    input.value = ''
    showSuggestions.value = false
}

function selectSuggestion(tag: Tag) {
    if (!tags.value.some(t => t.id === tag.id)) {
        tags.value = [...tags.value, tag]
    }
    input.value = ''
    showSuggestions.value = false
    nextTick(() => inputRef.value?.focus())
}

function removeTag(index: number) {
    tags.value = tags.value.filter((_, i) => i !== index)
}

function moveDown() {
    if (!showSuggestions.value || !filteredTags.value.length) return
    activeSuggestion.value = Math.min(
        activeSuggestion.value + 1,
        filteredTags.value.length - 1
    )
}

function moveUp() {
    if (!showSuggestions.value || !filteredTags.value.length) return
    activeSuggestion.value = Math.max(activeSuggestion.value - 1, 0)
}

function selectActiveOrAdd() {
    if (
        showSuggestions.value &&
        activeSuggestion.value >= 0 &&
        activeSuggestion.value < filteredTags.value.length
    ) {
        selectSuggestion(filteredTags.value[activeSuggestion.value])
    } else {
        addTag()
    }
}
</script>

<template>
    <div class="w-full max-w-md relative">
        <!-- Input i dugme -->
        <div class="flex gap-2 mb-2">
            <input
                v-model="input"
                @input="onInput"
                @keydown.down.prevent="moveDown"
                @keydown.up.prevent="moveUp"
                @keydown.enter.prevent="selectActiveOrAdd"
                type="text"
                placeholder="Pretraži ili dodaj tag"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                autocomplete="off"
                ref="inputRef"
            />
            <button
                @click="addTag"
                :disabled="isLoading"
                class="bg-primary text-primary-foreground px-3 py-1 rounded text-sm h-9"
            >
                <span v-if="isLoading" class="i-lucide-loader-2 animate-spin mr-2" />
                Dodaj
            </button>
        </div>

        <!-- Prikaz predloga -->
        <div
            v-if="showSuggestions && filteredTags.length"
            class="absolute w-full max-w-md rounded-md border bg-popover text-popover-foreground shadow-md outline-none animate-in fade-in-0 zoom-in-95 z-50"
        >
            <div
                v-for="(tag, i) in filteredTags"
                :key="tag.id"
                @mousedown.prevent="selectSuggestion(tag)"
                :class="[
          'flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none',
          i === activeSuggestion ? 'bg-accent text-accent-foreground' : 'hover:bg-accent hover:text-accent-foreground'
        ]"
            >
                {{ tag.name }}
            </div>
        </div>

        <!-- Prikaz izabranih tagova -->
        <div class="flex flex-wrap gap-2 mt-2">
      <span
          v-for="(tag, i) in tags"
          :key="tag.id ?? tag.name"
          class="inline-flex items-center rounded-full border border-input bg-accent px-2.5 py-1 text-xs font-medium text-accent-foreground"
      >
        {{ tag.name }}
        <button
            @click="removeTag(i)"
            class="ml-1.5 h-3 w-3 rounded-full bg-muted-foreground/20 hover:bg-muted-foreground/30 transition-colors"
        >
          <span class="sr-only">Ukloni</span>
          <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-2.5 w-2.5 m-auto"
              viewBox="0 0 20 20"
              fill="currentColor"
          >
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </span>
        </div>
    </div>
</template>
