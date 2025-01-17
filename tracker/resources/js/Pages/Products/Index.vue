<script setup >
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';


defineProps({
    products: {
        type: Array,
        required: true
    }
});
</script>

<template>

<AppLayout>

        <Container>
            <ul class="divide-y divide-gray-200">
                <li v-for="product in products.data" :key="product.id" class="px-2 py-4">
                    <Link :href="route('products.show', product.id)">
                        <span class="font-bold text-lg">{{ product.name }}</span>
                    </Link>
                </li>
            </ul>
            <Pagination :meta="products.meta" :only="['products']"/>
        </Container>
</AppLayout>

</template>

