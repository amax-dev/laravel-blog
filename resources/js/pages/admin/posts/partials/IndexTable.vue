<script setup lang="ts">
import { ref, computed } from 'vue'
import type { PostPagination } from '@/types'
import {
    FlexRender,
    getCoreRowModel,
    useVueTable,
    type ColumnDef,
} from '@tanstack/vue-table'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'

// Pretpostavimo da je ovo tvoj tip posta

const props = defineProps<{posts: PostPagination}>();



const columns: ColumnDef<Partial<PostPagination>>[] = [
    {
        accessorKey: 'id',
        header: 'ID',
    },
    {
        accessorKey: 'title',
        header: 'Title',
        cell: info => info.getValue(),
    },
    {
        accessorKey: 'category.name',
        header: 'Kategorija',
    },
    {
        accessorKey: 'tags',
        header: 'Tagovi',
    },

    {
        accessorKey: 'published',
        header: 'Published',
        cell: info => info.getValue() ? 'Yes' : 'No',
    },
]



// Filtering
const filter = ref('')
const filteredPosts = computed(() =>
    filter.value
        ? props.posts.data.filter(post =>
            post.title.toLowerCase().includes(filter.value.toLowerCase())
        )
        : props.posts.data
)

// Pagination
const pageSize = 5
const currentPage = ref(1)
const paginatedPosts = computed(() =>
    filteredPosts.value.slice((currentPage.value - 1) * pageSize, currentPage.value * pageSize)
)
const totalPages = computed(() => Math.ceil(filteredPosts.value.length / pageSize))

// Table instance
const table = useVueTable({
    get data() { return paginatedPosts.value },
    get columns() { return columns },
    getCoreRowModel: getCoreRowModel(),
})

// Pagination controls
function prevPage() {
    if (currentPage.value > 1) currentPage.value--
}
function nextPage() {
    if (currentPage.value < totalPages.value) currentPage.value++
}
</script>

<template>
    <div>
        <!-- Filter input -->
        <div class="flex items-center py-4">
            <Input
                class="max-w-sm"
                placeholder="Filter posts..."
                v-model="filter"
            />
        </div>

        <!-- Table -->
        <div class="border rounded-md">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="header in table.getHeaderGroups()[0].headers" :key="header.id">
                            <FlexRender :render="header.column.columnDef.header" :props="header.getContext()" />
                        </TableHead>
                        <TableHead>akcion</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows.length">
                        <TableRow v-for="row in table.getRowModel().rows" :key="row.id">
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <template v-if="cell.column.id === 'tags'">
                                    <div class="flex flex-wrap gap-1">
                                        <Badge v-for="tag in cell.getValue()" :key="tag.id" class="text-xs" variant="destructive">
                                            {{ tag.name }}
                                        </Badge>
                                    </div>
                                </template>
                                <template v-else>

                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </template>
                            </TableCell>
                            <TableCell><Button size="sm" variant="outline">Otvori</Button></TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell :colSpan="columns.length" class="h-24 text-center">
                                No results.
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination controls -->
        <div class="flex items-center justify-end py-4 space-x-2">
            <Button variant="outline" size="sm" :disabled="currentPage === 1" @click="prevPage">
                Previous
            </Button>
            <span>Page {{ currentPage }} of {{ totalPages }}</span>
            <Button variant="outline" size="sm" :disabled="currentPage === totalPages" @click="nextPage">
                Next
            </Button>
        </div>
    </div>
</template>
