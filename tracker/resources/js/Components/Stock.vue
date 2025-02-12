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
            <!-- <ChevronRightIcon
                class="size-5 flex-none text-gray-400"
                aria-hidden="true"
            /> -->
            <Menu as="div" class="relative flex-none">
          <MenuButton class="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900">
            <span class="sr-only">Open options</span>
            <EllipsisVerticalIcon class="size-5" aria-hidden="true" />
          </MenuButton>
          <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
            <MenuItems class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
              <MenuItem v-slot="{ active }">
                <a :href="stock.url" :class="[active ? 'bg-gray-50 outline-none' : '', 'block px-3 py-1 text-sm/6 text-gray-900']"
                  >View stock<span class="sr-only">, {{ stock.url }}</span></a
                >
              </MenuItem>
              <MenuItem v-slot="{ active }">

                    <a href="#" @click="deleteStock" :class="[active ? 'bg-gray-50 outline-none' : '', 'block px-3 py-1 text-sm/6 text-gray-900']"
                      >Remove<span class="sr-only">, {{ stock.id }}</span></a
                    >
              </MenuItem>
            </MenuItems>
          </transition>
        </Menu>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
import { relativeDate } from "@/Utilities/date.js";
import { ChevronRightIcon } from "@heroicons/vue/20/solid";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon } from '@heroicons/vue/20/solid'
import { router, usePage } from "@inertiajs/vue3";


const productId = usePage().props.product.id;
const props = defineProps(["stock"]);

// get the product id from the url
// const productId = window.location.pathname.split("/").pop();

const deleteStock = () => router.delete(route("stocks.destroy", [productId , props.stock.id]),
    {
        preserveScroll: true,
        onSuccess: () => {
            console.log("deleted");
        },
    }
);
</script>
