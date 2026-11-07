<script setup>
import { Modal } from '@inertiaui/modal-vue'
import { computed, ref } from 'vue';
import GroupInput from "@/Components/GroupInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CancelButton from "@/Components/CancelButton.vue";
import { useForm , router} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    retailers: {
        type: Array,
        required: true,
    },
    stock: {
        type: Object,
        required: true,
    },
    product: {
        type: Object,
        required: true,
    },
});

const options = ref(props.retailers.map((retailer) => ({
    key: retailer.id,
    value: retailer.name,
})));

const modalRef = ref(null);

const stockForm = useForm({
    retailer:  props.stock.retailer.id,
    url:  props.stock.url,
});

const updateStock = () => {
    stockForm.put(route("products.stocks.update", [props.product.id, props.stock.id]), {
        preserveScroll: true,
        onSuccess: () => {
            console.log("updated");
            modalRef.value.close();
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};


</script>

<template>
    <Modal v-slot="{ close }" ref="modalRef">
        <h1>Edit Stock</h1>
        <form @submit.prevent="updateStock">
               <GroupInput
                                v-model:select="stockForm.retailer"
                                v-model:input="stockForm.url"
                                :options="options"
                            ></GroupInput>
                            <InputError :message="stockForm.errors.retailer" class="mt-1"/>
                            <InputError :message="stockForm.errors.url" class="mt-1"/>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <CancelButton @click="close">
                        Cancel
                    </CancelButton>
                    <PrimaryButton type="submit" :disabled="stockForm.processing">
                        Save
                    </PrimaryButton>
                </div>
        </form>
    </Modal>
</template>
