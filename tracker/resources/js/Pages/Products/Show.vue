<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Stock from "@/Components/Stock.vue";
import Container from "@/Components/Container.vue";
import { computed } from "vue";
import { formatDistance, parseISO } from "date-fns";
import Pagination from "@/Components/Pagination.vue";
import { ChevronRightIcon } from "@heroicons/vue/20/solid";
import { relativeDate } from "@/Utilities/date.js";


const props = defineProps(["product", "stocks"]);

const formattedDate = computed(() => relativeDate(props.product.created_at));
</script>
<template>
    <AppLayout :title="product.name">
        <Container>
            <h1 class="text-2xl font-bold">{{ product.name }}</h1>
            <span class="text-sm text-gray-600">Added {{ formattedDate }}</span>

            <h2 class="text-xl font-semibold mt-2">Stocks</h2>
            <div>
                <ul role="list" class="divide-y divide-gray-300">
                    <li
                        v-for="stock in stocks.data"
                        :key="stock.id"
                        class="relative flex justify-between gap-x-6 py-5"
                    >
                        <Stock :stock="stock" />

                    </li>
                </ul>
            </div>
            <Pagination :meta="stocks.meta" :only="['stocks']"/>
        </Container>
    </AppLayout>
</template>
