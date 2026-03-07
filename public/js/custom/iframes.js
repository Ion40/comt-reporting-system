document.addEventListener("livewire:initialized", () => {
});

async function deleteIframeFn(slug) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "El reporte se dará de baja y dejará de estar visible para los usuarios.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6e7d88',
        confirmButtonText: 'Sí, dar de baja',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        allowOutsideClick: false,
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Procesando...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });


            try {
                const url = deleteIframe.replace(':url_path', slug);
                const res = await fetch(url,{
                    method: 'PUT',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf_token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                });

                const result = await res.json();

                if (result.success) {
                    await Swal.fire({
                        icon: 'success',
                        text: result.message,
                        allowOutsideClick: false,
                    });
                } else {
                    let errorMsg = result.message;
                    if (result.errors) {
                        errorMsg += ": " + Object.values(result.errors).flat().join(", ");
                    }
                    throw new Error(errorMsg);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en la operación',
                    text: error.message,
                    allowOutsideClick: false,
                });
            }
        }
    })
}
