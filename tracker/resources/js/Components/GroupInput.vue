<template>
    <fieldset class="mt-3">
        <legend class="block text-sm/6 font-medium text-gray-900">
            Product URL
        </legend>
        <div class="mt-2">
            <SelectInput
                v-model="select"
                :options="options"
                :id="`select-${machine_name}`"
                :name="`select-${machine_name}`"
                :autocomplete="`select-${machine_name}`"
            >
            </SelectInput>
            <div class="-mt-px">
                <TextInput
                    :id="`input-${machine_name}`"
                    :name="`input-${machine_name}`"
                    :autocomplete="`placeholder-${machine_name}`"
                    :aria-label="placeholder"
                    class="rounded-none rounded-b-md"
                    :placeholder="placeholder"
                    v-model="input"
                >
                </TextInput>
            </div>
        </div>
    </fieldset>
</template>

<script setup>
import TextInput from "./TextInput.vue";
import SelectInput from "./SelectInput.vue";
import { computed, defineModel, defineProps } from "vue";

const input = defineModel("input");
const select = defineModel("select");

const machine_name = computed(() => {
    return props.name.replace(/\./g, "_");
});

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    placeholder: {
        type: String,
        default: "Add your place holder text here",
    },
    name: {
        type: String,
        default: "",
    },
});
</script>
