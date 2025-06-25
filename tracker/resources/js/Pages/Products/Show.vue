<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Stock from "@/Components/Stock.vue";
import Container from "@/Components/Container.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { formatDistance, parseISO } from "date-fns";
import Pagination from "@/Components/Pagination.vue";
import { relativeDate, fullMonth } from "@/Utilities/date.js";
import Heading from "@/Components/PageHeading.vue";
// import router from "@/router";
import { defineProps, ref } from "vue";
import { visitModal } from "@inertiaui/modal-vue";

import {
    BriefcaseIcon,
    CalendarIcon,
    CheckIcon,
    ChevronDownIcon,
    CurrencyDollarIcon,
    LinkIcon,
    MapPinIcon,
    PencilIcon,
    TrashIcon,
    ShoppingBagIcon,
    CheckBadgeIcon,
} from "@heroicons/vue/20/solid";
import { useConfirm } from "@/Utilities/Composables/useConfirm";
import { Link } from '@inertiajs/vue3';
import Pill from "@/Components/Pill.vue";

const props = defineProps(["product", "stocks"]);

const formattedDate = computed(() => fullMonth(props.product.created_at));

const stockIdBeingEdited = ref(null);

const { confirmation } = useConfirm();

const editStock = (stockId) => {
    stockIdBeingEdited.value = stockId;
    visitModal(route("products.stocks.edit", [props.product.id, stockId]));
};

const deleteProduct = async () => {
    if (
        !(await confirmation(
            "Are you sure you want to delete this product?",
            "This action cannot be undone.",
            "Delete Product"
        ))
    )
        return;

    router.delete(route("products.destroy", props.product.id));
};

const deleteStock = async (stockId) => {
    if (
        !(await confirmation(
            "Are you sure you want to delete this stock?",
            "This action cannot be undone.",
            "Delete Stock"
        ))
    )
        return;

    router.delete(
        route("products.stocks.destroy", [props.product.id, stockId]),
        {
            preserveScroll: true,
            onSuccess: () => {
                console.log("deleted");
            },
        }
    );
};
</script>
<template>
    <AppLayout :title="product.name">
        <Container>
            <!-- <Pill
                class="mb-4"
                :color="'indigo'"
                :href="route('products.index', { type: product.type.slug })"
            >{{ product.type.name }}</Pill> -->
            <heading :title="product.name" :date="formattedDate">
                <template #meta>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <ShoppingBagIcon
                            class="mr-1.5 size-5 shrink-0 text-gray-400"
                            aria-hidden="true"
                        />
                        <Link :href="route('products.index', {type: product.type})"> {{ product.type.name }} </Link>
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <CheckBadgeIcon
                            class="mr-1.5 size-5 shrink-0 text-gray-400"
                            aria-hidden="true"
                        />
                        Premium
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <CurrencyDollarIcon
                            class="mr-1.5 size-5 shrink-0 text-gray-400"
                            aria-hidden="true"
                        />
                        {{ product.price_range }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <CalendarIcon
                            class="mr-1.5 size-5 shrink-0 text-gray-400"
                            aria-hidden="true"
                        />
                        Added on {{ formattedDate }}
                    </div>
                </template>
                <span class="hidden sm:block">
                    <form @submit.prevent="deleteProduct">
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                        >
                            <TrashIcon
                                class="-ml-0.5 mr-1.5 size-5 text-gray-400"
                                aria-hidden="true"
                            />
                            Delete
                        </button>
                    </form>
                </span>

                <span class="ml-3 hidden sm:block">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    >
                        <PencilIcon
                            class="-ml-0.5 mr-1.5 size-5 text-gray-400"
                            aria-hidden="true"
                        />
                        Edit
                    </button>
                </span>

                <span class="sm:ml-3">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        <CheckIcon
                            class="-ml-0.5 mr-1.5 size-5"
                            aria-hidden="true"
                        />
                        Publish
                    </button>
                </span>
            </heading>
            <div>
                <h2 class="text-xl font-semibold mt-4">Stocks</h2>
                <ul role="list" class="divide-y divide-gray-300">
                    <li v-for="stock in stocks.data" :key="stock.id">
                        <Stock
                            :stock="stock"
                            @edit="editStock"
                            @delete="deleteStock"
                        />
                    </li>
                </ul>
            </div>
            <Pagination :meta="stocks.meta" :only="['stocks']" />
        </Container>
    </AppLayout>
</template>
