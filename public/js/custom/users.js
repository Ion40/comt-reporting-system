document.addEventListener("DOMContentLoaded", function (e) {
    loadUserData();
});

function loadUserData(){
    const table = $('#users-table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true, // Habilita la carga por servidor
        ajax: usersList,
        //dom: 'rtip', // Oculta el buscador por defecto de DT para usar el tuyo
        columns: [
            {data: 'nombre', name: 'nombre'},
            {data: 'email', name: 'email'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        language: language_datatable
    });
}

// Definimos la instancia del modal fuera para reutilizarla
let userModalInstance = null;
let changePasswordModalInstance = null;

function openModal(operation = 'create', data = null) {
    const modalElement = document.getElementById("modalUser");
    const modalTitle = document.getElementById("modalUserLabel");
    const buttonUser = document.getElementById("btnSaveUpdated");
    const form = document.getElementById("form_user");

    // 1. Inicializamos el modal solo si no existe
    if (!userModalInstance) {
        userModalInstance = new bootstrap.Modal(modalElement);
    }

    form.reset();
    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

    // 2. Definimos los textos de forma clara
    const isCreate = operation === 'create';
    const config = {
        title: isCreate ? 'Agregar usuario' : 'Actualizar usuario',
        button: isCreate ? 'Guardar' : 'Actualizar',
        icon: isCreate ? 'ti-device-floppy fs-4' : 'fs-4 ti-refresh' // Opcional: Iconos dinámicos
    };

    // 3. Actualizamos el contenido (usamos textContent/innerHTML para limpiar lo previo)
    modalTitle.textContent = config.title;

    // Limpiamos y seteamos el botón con su icono
    buttonUser.innerHTML = `<i class="ti ${config.icon} me-1"></i> ${config.button}`;

    if (!isCreate && data) {
        document.getElementById("div_pass").style.display = "none";
        document.getElementById("id_user_input").value = data.id;
        document.getElementById("user_name_input").value = data.nombre;
        document.getElementById("user_mail_input").value = data.email;
    } else {
        document.getElementById("id_user_input").value = "";
        document.getElementById("div_pass").style.display = "block";
    }

    // 5. Mostramos el modal
    userModalInstance.show();
}

document.getElementById("btnAddUser").addEventListener("click", function () {
    openModal("create");
})

document.getElementById("btnSaveUpdated").addEventListener("click", async (e) => {
    const fields = document.querySelectorAll("#form_user .validate");
    let id_user_input = document.getElementById("id_user_input");
    let isFormValid = true;
    let form_data = {};

    fields.forEach((element) => {
        // Ejecutamos validación y actualizamos el estado global del formulario
        if (element.offsetParent !== null) {
            if (!validateField(element,4)) {
                isFormValid = false;
            }

            form_data[element.id] = element.value;

        } else {
            //limpiar mensajes de errores si los hay
            element.classList.remove("is-invalid", "is-valid");
        }
    });

    if (isFormValid) {
        form_data.id_user_input = id_user_input.value ? id_user_input.value : null;
        form_data.method = id_user_input.value ? 'PUT' : 'POST';

        Swal.fire({
            title: 'Procesando...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const res = await fetch(saveOrUpdate, {
                method: form_data.method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf_token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(form_data),
            });

            const result = await res.json();

            if (result.success) {
                await Swal.fire({
                    icon: 'success',
                    text: result.message,
                    allowOutsideClick: false,
                });

                loadUserData();
                userModalInstance.hide();
            } else {
                let errorMsg = result.message;
                throw new Error(errorMsg);
            }

        }  catch(error) {
            Swal.fire({
                icon: 'error',
                title: 'Error en la operación',
                text: error.message,
                allowOutsideClick: false,
            });
        }
    }
});

document.querySelectorAll("#form_user .validate").forEach((element) => {
    element.addEventListener("input", () => {
        validateField(element, 4);
    });
});

document.addEventListener("click", async (e) => {
    if (e.target.classList.contains('btn-edit')) {
        const userId = e.target.dataset.iduser;
        editUser(userId);
    }
});

async function editUser(userId) {
    const url = getUser.replace(':id', userId);

    try {
        Swal.fire({
            title: 'Procesando...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf_token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        Swal.close();

        if (result.success) {
            openModal("update",result.data);
        } else {
            let errorMsg = result.message;
            throw new Error(errorMsg);
        }
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
}

