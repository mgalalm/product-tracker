import { reactive, ReactiveEffect, readonly } from "vue";

const globalState = reactive({
    show: false,
    title: "",
    message: "",
    resolver: null,
});

export const useConfirm = () => {

    const resetModal = () => {
        globalState.show = false;
        globalState.title = "";
        globalState.message = "";
        globalState.resolver = null;
    };

    return {
        state: readonly(globalState),

        confirmation: (title, message) => {
            globalState.show = true;
            globalState.title = title;
            globalState.message = message;

            return new Promise((resolve) => {
                globalState.resolver = resolve; // creates a Promise and stores its resolve function in the globalState.resolver
            });

        },
        confirm: () => {
            if (globalState.resolver) {
                globalState.resolver(true); // resolves the Promise with true
            }

            resetModal();
        },
        cancel: () => {
            if (globalState.resolver) {
                globalState.resolver(false); // resolves the Promise with false
            }

            resetModal();
        },
    };
};

// setInterval(() => {globalState.show = !globalState.show}, 1000);
