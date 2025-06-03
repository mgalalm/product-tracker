<script setup >
import AppLayout from '@/Layouts/AppLayout.vue';
import Container from '@/Components/Container.vue';
import Pagination from '@/Components/Pagination.vue';
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';
import { relativeDate } from "@/Utilities/date.js";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from '@/Components/Pill.vue';


defineProps({
    products: {
        type: Object,
        required: true
    },
    types: {
        type: Array,
        required: true
    },
    selectedType: {
        type: Object,
        required: false
    },
});

const formattedDate = (product) => relativeDate(product.created_at);
</script>

<template>

<AppLayout>

        <Container>
            <PageHeading :title="selectedType? selectedType.name : 'All products'"></PageHeading>
            <menu class="flex space-x-1 mt-3 whitespace-nowrap overflow-x-auto pb-2 pt-1">
                <li>
                    <Pill :href="route('products.index')" :filled="!selectedType">
                        All
                    </Pill>
                </li>
                <li v-for="type in types" :key="type.id">
                        <Pill :href="route('products.index', {type: type.slug})" :filled="selectedType && selectedType.id === type.id">
                            {{ type.name }}
                        </Pill>
                    </li>

            </menu>


            <ul class="divide-y divide-gray-200 mt-4">
                <li v-for="product in products.data" :key="product.id" class="flex flex-col items-baseline justify-between md:flex-row">
                    <Link :href="product.routes.show" class="group block px-2 py-4">
                        <span class="text-lg font-bold group-hover:text-indigo-500">{{ product.name }}</span>
                        <span
                            class="block pt-1 text-sm text-gray-600 first-letter:uppercase"
                            >{{ formattedDate(product) }}
                        </span>
                    </Link>
                    <Pill :href="route('products.index', {type: product.type.slug})">
                        {{ product.type.name }}
                    </Pill>
                </li>
            </ul>
            <Pagination :meta="products.meta" :only="['products', 'selectedType']"/>
        </Container>
</AppLayout>

</template>

