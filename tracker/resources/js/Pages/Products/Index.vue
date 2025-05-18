<script setup >
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';
import { relativeDate } from "@/Utilities/date.js";


defineProps({
    products: {
        type: Array,
        required: true
    }
});

const formattedDate = (product) => relativeDate(product.created_at);
</script>

<template>

<AppLayout>

        <Container>
            <ul class="divide-y divide-gray-200">
                <li v-for="product in products.data" :key="product.id" class="flex flex-col items-baseline justify-between md:flex-row">
                    <Link :href="product.routes.show" class="group block px-2 py-4">
                        <span class="text-lg font-bold group-hover:text-indigo-500">{{ product.name }}</span>
                        <span
                            class="block pt-1 text-sm text-gray-600 first-letter:uppercase"
                            >{{ formattedDate(product) }}
                        </span>
                    </Link>
                    <Link href="/" class="mb-2 rounded-full border border-pink-500 px-2 py-0.5 text-pink-500 hover:bg-indigo-500 hover:text-indigo-50">
                        {{ product.type.name}}
                    </Link>
                </li>
            </ul>
            <Pagination :meta="products.meta" :only="['products']"/>
        </Container>
</AppLayout>

</template>

