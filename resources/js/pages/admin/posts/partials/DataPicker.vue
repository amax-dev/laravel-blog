<script setup lang="ts">
import { ref } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const published_at = defineModel<any>('published_at');



const date = ref(published_at);


</script>

<template>
    <VueDatePicker
        v-model="date"
        format="dd/MM/yyyy HH:mm"
        enable-time-picker
    />
</template>
