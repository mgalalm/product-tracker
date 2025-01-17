<template>
    <AppLayout>
        <Container>
            <form @submit.prevent="addProduct">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base/7 font-semibold text-gray-900">
                            Product
                        </h2>
                        <p class="mt-1 text-sm/6 text-gray-600">
                            This information will be displayed publicly so be
                            careful what you share.
                        </p>
                        <div
                            class="mt-2 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                        >
                            <div class="sm:col-span-4">
                                <label
                                    for="email"
                                    class="block text-sm/6 font-medium text-gray-900 sr-only"
                                    >Name</label
                                >
                                <div class="mt-2">
                                    <TextInput v-model="productForm.name" placeholder="Name of the product here"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base/7 font-semibold text-gray-900">
                            Retailers
                        </h2>
                        <p class="mt-1 text-sm/6 text-gray-600">
                            Use a permanent address where you can receive mail.
                        </p>
                        <div
                            class="mt-1 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                        >
                            <div class="sm:col-span-4">
                                <GroupInput
                                v-model:select="productForm.retailer"
                                v-model:input="productForm.stockUrl"
                                :options="options"
                                ></GroupInput>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <CancelButton>
                        Cancel
                    </CancelButton>
                    <PrimaryButton type="submit">
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
import { ref } from 'vue';
import TextInput from "@/Components/TextInput.vue";
import GroupInput from "@/Components/GroupInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CancelButton from "@/Components/CancelButton.vue";

const productForm = useForm({
    name: "",
    retailer: "",
    stockUrl: "",
});


const addProduct = () => {
    // console.log(productForm);
    productForm.post(route("products.store"));
};


const props = defineProps({
    retailers: {
        type: Array,
        required: true,
    },
});

// transform the retailers into options
const options = ref(props.retailers.map((retailer) => ({
    key: retailer.id,
    value: retailer.name,
})));


</script>
