<template>
    <fieldset class="mt-3">
        <legend class="block text-sm/6 font-medium text-gray-900">
            Product URL
        </legend>
        <div class="mt-2">
            <SelectInput v-model="select" :options=options>
            </SelectInput>
            <div class="-mt-px">
                <TextInput
                    id="stock-url"
                    name="stock-url"
                    autocomplete="stock-url"
                    aria-label="Stock URL"
                    placeholder="Stock URL"
                    class="rounded-none rounded-b-md"
                    v-model="input"
                    > </TextInput>
            </div>
        </div>
    </fieldset>
</template>

<script setup>
import TextInput from './TextInput.vue';
import SelectInput from './SelectInput.vue';
import { defineModel, defineProps } from 'vue';

const input = defineModel('input');
const select = defineModel('select');

defineProps ({
    options: {
        type: Array,
        required: true,
    },
});


</script>
