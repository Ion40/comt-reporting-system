document.addEventListener('livewire:navigated', function (e) {
    $('#user_select').select2({
        ajax: {
            url: '/api/users/search',
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Indica a Laravel que es una petición AJAX
            },
            delay: 250,
            data: function (params) {
                return {
                    term: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results // Según la estructura de tu controlador
                };
            },
            cache: true
        },
    });

    // Comunicación con tu componente Livewire de permisos
    $('#user_select').on('change', function (e) {
        Livewire.dispatch('user-selected', {id: $(this).val()});
    });

    document.getElementById('btnGuardarPermisos').addEventListener('click', async function (e) {
        // 1. Obtener el ID del usuario desde el Select2 (usando jQuery que requiere Select2)
        // Suponiendo que tu select tiene el id="userSelect"
        const userId = $('#user_select').val();

        // 2. Obtener los valores de los checkboxes que estén en TRUE (checked)
        // Buscamos todos los inputs tipo checkbox que estén marcados
        const selectedPermissions = [];
        document.querySelectorAll('input[type="checkbox"]:checked').forEach((checkbox) => {
            // Solo agregamos si tiene un valor (ej. "2_1")
            selectedPermissions.push(checkbox.value);
        });

        // Validaciones previas
        if (!userId) {
            Swal.fire({
                icon: 'error',
                text: 'Por favor, seleccione un usuario.',
                allowOutsideClick: false,
            });
            return;
        }

        if (selectedPermissions.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Permisos vacíos',
                text: 'No ha seleccionado ningún permiso.',
                allowOutsideClick: false,
            });
            return;
        }

        try {
            const response = await fetch(apiSaveConfiguration, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf_token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    permissions: selectedPermissions
                })
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    text: `${result.message}`,
                    allowOutsideClick: false,
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    text: `Error del servidor: ${(result.error || "No se pudo guardar")}`,
                    allowOutsideClick: false,
                });
            }
        } catch (error) {
            console.error("Error en Fetch:", error);
            Swal.fire({
                icon: 'error',
                text: `Error en Fetch:"${error.message}",`,
                allowOutsideClick: false,
            });
        }
    });

})
