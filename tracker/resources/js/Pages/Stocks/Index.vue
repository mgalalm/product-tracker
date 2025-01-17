<script setup >
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';


defineProps({
    stocks: {
        type: Array,
        required: true
    }
});
</script>

<template>

<AppLayout>

        <Container>
            <ul class="divide-y divide-gray-200">
                <li v-for="stock in stocks.data" :key="stock.id" class="px-2 py-4">
                    <span class="font-bold text-lg">{{ stock.name }}</span>
                </li>
            </ul>
            <Pagination :meta="products.meta"/>
        </Container>
</AppLayout>

</template>

