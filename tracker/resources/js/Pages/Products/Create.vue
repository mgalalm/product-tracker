<template>
    <AppLayout>
        <Container>
            <heading title="Create Product"></heading>
            <form @submit.prevent="addProduct">
                <div class="space-y-4">
                    <div class="border-b border-gray-900/10 pb-8">
                        <h2 class="text-base/7 font-semibold text-gray-900">
                            Product
                        </h2>
                        <div
                            class="mt-2 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                        >
                            <div class="sm:col-span-4">
                                <label
                                    for="name"
                                    class="block text-sm/6 font-medium text-gray-900 sr-only"
                                    >Name</label
                                >
                                <div class="mt-2">
                                    <TextInput
                                        v-model="productForm.name"
                                        placeholder="Name of your product here"
                                        id="name"
                                    />
                                    <InputError
                                        :message="productForm.errors.name"
                                        class="mt-1"
                                    />
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <!-- <InputLabel
                                    for="type"
                                    class="block text-sm/6 font-medium text-gray-900"
                                    >Select a Type</InputLabel
                                > -->
                                <div  class="mt-2">
                                    <SelectInput
                                        v-model="productForm.type_id"
                                        :options="
                                            props.types.map((type) => ({
                                                key: type.id,
                                                value: type.name,
                                            }))
                                        "
                                        id="type"
                                        name="type_id"
                                        placeholder="Select a type"
                                        class="rounded-b-md"
                                    />
                                    <InputError
                                        :message="productForm.errors.type_id"
                                        class="mt-1"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base/7 font-semibold text-gray-900">
                            Retailers
                        </h2>
                        <p class="mt-1 text-sm/6 text-gray-600">
                            Add the retailers where you can find this product.
                            You can add multiple retailers.
                        </p>
                        <div
                            class="mt-1 grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-6"
                        >
                            <div
                                class="sm:col-span-4"
                                v-for="(stock, index) in productForm.stocks"
                                :key="index"
                            >
                                <GroupInput
                                    v-model:select="stock.retailer"
                                    v-model:input="stock.url"
                                    :placeholder="'Stock URL'"
                                    :name="'stocks.' + index + '.url'"
                                    :options="options"
                                ></GroupInput>
                                <InputError
                                    :message="
                                        productForm.errors[
                                            `stocks.${index}.retailer`
                                        ]
                                    "
                                    class="mt-1"
                                />
                                <InputError
                                    :message="
                                        productForm.errors[
                                            `stocks.${index}.url`
                                        ]
                                    "
                                    class="mt-1"
                                />
                                <button
                                    type="button"
                                    @click="removeStock(index)"
                                    class="text-red-500"
                                >
                                    Remove
                                </button>
                            </div>
                            <button
                                type="button"
                                @click="addStock"
                                class="mt-2 text-blue-500"
                            >
                                Add Stock
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <CancelButton> Cancel </CancelButton>
                    <PrimaryButton
                        type="submit"
                        :disabled="productForm.processing"
                    >
                        Save
                    </PrimaryButton>
                </div>
            </form>
        </Container>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import GroupInput from "@/Components/GroupInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CancelButton from "@/Components/CancelButton.vue";
import InputError from "@/Components/InputError.vue";
import Heading from "@/Components/PageHeading.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectInput from "@/Components/SelectInput.vue";

const props = defineProps({
    retailers: {
        type: Array,
        required: true,
    },
    types: {
        type: Array,
        required: true,
    },
});

const productForm = useForm({
    name: "",
    type_id: props.types[0].id, // Default to the first type
    stocks: [
        {
            retailer: "",
            url: "",
        },
    ],
});

const addStock = () => {
    productForm.stocks.push({
        retailer: "",
        url: "",
    });
};

const removeStock = (index) => {
    productForm.stocks.splice(index, 1);
};

const addProduct = () => {
    productForm.post(route("products.store"), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (errors) => {
            console.log(errors);
        },
    });
};

// transform the retailers into options
const options = ref(
    props.retailers.map((retailer) => ({
        key: retailer.id,
        value: retailer.name,
    }))
);
</script>
