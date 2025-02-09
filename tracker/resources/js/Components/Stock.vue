<template>
    <div class="relative flex justify-between gap-x-6 py-2">
        <div class="flex min-w-0 gap-x-4">
            <div class="min-w-0 flex-auto">
                <p class="text-sm/6 font-semibold text-gray-900">
                    <a :href="stock.url">
                        <span class="absolute inset-x-0 -top-px bottom-0" />
                        {{ stock.retailer.name }}
                    </a>
                </p>
                <p class="mt-1 text-xs/5 text-gray-500">
                    Last updated
                    <time :datetime="stock.update_at">
                        {{ relativeDate(stock.updated_at) }}
                    </time>
                </p>
            </div>
        </div>
        <div class="flex shrink-0 items-center gap-x-4">
            <div class="hidden sm:flex sm:flex-col sm:items-end">
                <p class="text-sm/6 text-gray-900">
                    {{ stock.formatted_price }}
                </p>

                <div
                    v-if="stock.tracked && stock.in_stock"
                    class="mt-1 flex items-center gap-x-1.5"
                >
                    <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                        <div class="size-1.5 rounded-full bg-emerald-500" />
                    </div>
                    <p class="text-xs/5 text-gray-500">In Stock</p>
                </div>
                <div v-else-if="stock.tracked && !stock.in_stock" class="mt-1 flex items-center gap-x-1.5">
                    <div class="flex-none rounded-full bg-red-500/20 p-1">
                        <div class="size-1.5 rounded-full bg-red-500" />
                    </div>
                    <p class="text-xs/5 text-gray-500">Out of Stock</p>
                </div>
                  <div v-else-if="!stock.tracked && stock.is_new" class="mt-1 flex items-center gap-x-1.5">
                    <div class="flex-none rounded-full bg-red-500/20 p-1">
                        <div class="size-1.5 rounded-full bg-red-500" />
                    </div>
                    <p class="text-xs/5 text-gray-500">No tracked yet</p>
                </div>
                      <div v-else class="mt-1 flex items-center gap-x-1.5">
                    <div class="flex-none rounded-full bg-red-500/20 p-1">
                        <div class="size-1.5 rounded-full bg-yellow-500" />
                    </div>
                    <p class="text-xs/5 text-gray-500">Processing</p>
                </div>

            </div>
            <ChevronRightIcon
                class="size-5 flex-none text-gray-400"
                aria-hidden="true"
            />
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import { relativeDate } from "@/Utilities/date.js";
import { ChevronRightIcon } from "@heroicons/vue/20/solid";

const props = defineProps(["stock"]);
</script>
