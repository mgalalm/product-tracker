<template>
    <ConfirmationModal :show="state.show">
        <template #title>
           {{ state.title }}
        </template>

        <template #content>
            {{ state.message }}
        </template>

        <template #footer>
                <CancelButton @click="cancel" ref="cancelButtonRef">
                    Cancel
                </CancelButton>

                <PrimaryButton @click="confirm" class="ml-3">
                    Confirm
                </PrimaryButton>
        </template>

    </ConfirmationModal>

</template>

<script setup>
import { useConfirm} from "@/Utilities/Composables/useConfirm";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
const {state, confirm, cancel} = useConfirm();
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CancelButton from "@/Components/CancelButton.vue";
import { nextTick, ref, watchEffect } from 'vue';

const cancelButtonRef = ref(null);

watchEffect(async () => {
    if (state.show) {
        await nextTick(); // wait till the DOM is updated because we can't actually focus on an element that isn't inside the DOM.
        cancelButtonRef.value?.$el.focus();

    }
});

</script>
