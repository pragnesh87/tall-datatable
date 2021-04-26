document.addEventListener("DOMContentLoaded", () => {
    const SwalModal = (icon, title, html) => {
        Swal.fire({
            icon,
            title,
            html,
        });
    };

    const SwalConfirm = (
        icon,
        title,
        html,
        confirmButtonText,
        method,
        params,
        callback
    ) => {
        Swal.fire({
            icon,
            title,
            html,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText,
            reverseButtons: true,
        }).then((result) => {
            console.log(method, params, callback);
            if (result.value) {
                if (params) {
                    return livewire.emit(method, params);
                } else {
                    return livewire.emit(method);
                }
            }

            if (callback) {
                return livewire.emit(callback);
            }
        });
    };

    const SwalAlert = (icon, title, timeout = 7000) => {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: timeout,
            onOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });

        Toast.fire({
            icon,
            title,
        });
    };

    this.livewire.on("swal:modal", (data) => {
        SwalModal(data.icon, data.title, data.text);
    });

    this.livewire.on("swal:confirm", (data) => {
        SwalConfirm(
            data.icon,
            data.title,
            data.text,
            data.confirmText,
            data.method,
            data.params,
            data.callback
        );
    });

    this.livewire.on(
        "swal:confirm-message",
        (message, method, params = "", callback = "") => {
            SwalConfirm(
                "warning",
                message,
                "",
                "Yes",
                method,
                params,
                callback
            );
        }
    );

    Livewire.on("swal:deleteconfirm", (method, params = "", callback = "") => {
        SwalConfirm(
            "warning",
            "Do you want to delete this?",
            "You won't be able to revert this!",
            "Yes, delete!",
            method,
            params,
            callback
        );
    });

    this.livewire.on("swal:alert", (data) => {
        SwalAlert(data.icon, data.title, data.timeout);
    });

    this.livewire.on("modal:show", (modalId) => {
        $(modalId).modal("show");
    });
});
