<template>
    <div class="grid grid-cols-1 focus-within:relative">
        <select
            class="col-start-1 row-start-1 w-full appearance-none rounded-t-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        >
            <option v-for="option in options" :value="option.key" :key="option.value" :selected="option.key === modelValue">
                {{ option.value }}
            </option>
        </select>
        <!-- <ChevronDownIcon
            class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
            aria-hidden="true"
        /> -->
    </div>
</template>

<script setup>
import { defineProps, defineEmits, onMounted } from "vue";
import { ChevronDownIcon } from "@heroicons/vue/16/solid";


const props = defineProps({
    modelValue: String,
    options: {
        type: Array,
        required: true,
        // Each option should have { value, label } properties
    },
});

onMounted(() => {
    console.log(props.modelValue);
});

// log the modelValue and options
// console.log(props.modelValue);
</script>
