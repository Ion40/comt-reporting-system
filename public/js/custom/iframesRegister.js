document.addEventListener('DOMContentLoaded', () => {
    $('#iframe_url_input').on('input blur', function () {
        let url = $(this).val();
        let $iframe = $('#iframe-preview');
        let $placeholder = $('#preview-placeholder');

        if (url && url.startsWith('http')) {
            $placeholder.hide();
            $iframe.attr('src', url).show();
        } else {
            $placeholder.show();
            $iframe.hide().attr('src', '');
        }
    });
})

document.getElementById("btnSelectModule").addEventListener("click", async function (e) {
    const modalElement = document.getElementById("modalSelectorModulos");

    const modal = new bootstrap.Modal(modalElement);

    await getModulesParents();

    modal.show();

});

document.getElementById("btnRefreshPreview").addEventListener("click", async function (e) {
    $('#iframe_url_input').on('input', function() {
        const url_iframe = $(this).val();
        if (url_iframe.includes('http')) {
            $('#preview-placeholder').hide();
            $('#iframe-preview').attr('src', url_iframe).show();
        } else {
            $('#preview-placeholder').show();
            $('#iframe-preview').hide();
        }
    });

    $('#iframe_url_input').trigger('input');
})
document.getElementById("btnSaveIframe").addEventListener("click", async function (e) {
    Swal.fire({
        title: 'Procesando...',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    try {
        let form_data = {};

        if (isEdit) {
            form_data.id_iframe = document.getElementById('id_iframe').value;
        }
        form_data.id_module_input = document.getElementById('id_module_input').value;
        form_data.titulo_iframe = document.getElementById('titulo_iframe').value;
        form_data.iframe_url_input = document.getElementById('iframe_url_input').value;
        form_data.module_url_input = document.getElementById('module_url_input').value;


        const res = await fetch(saveOrUpdate, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf_token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(form_data)
        });

        const result = await res.json();

        if (result.success) {
            await Swal.fire({
                icon: 'success',
                text: result.message,
                allowOutsideClick: false,
            });
        } else {
            // Manejo de errores de validación o servidor
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
})

async function getModulesParents() {
    const $parentList = document.getElementById('parent-list');

    //mostrar loader mientras se carga la info
    $parentList.innerHTML = `
    <div class="list-group">
            ${[1, 2, 3, 4, 5].map(() => `
                <div class="list-group-item p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2 w-100 placeholder-glow">
                            <span class="placeholder rounded" style="width:28px;height:28px;"></span>
                            <span class="placeholder col-6"></span>
                        </div>
                        <span class="placeholder rounded" style="width:16px;height:16px;"></span>
                    </div>
                </div>
            `).join('')}
        </div>
    `;

    fetch(modulesParents, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            //limpiar el contenedor
            $parentList.innerHTML = ``;

            if (data.length === 0) {
                $parentList.innerHTML = `<div class="p-3 text-center text-muted">No hay módulos disponibles</div>`;
                return;
            }

            //recorrer la data y dibujar las opciones
            data.forEach(parent => {
                const buttonHTML = `
                    <button type="button"
                            class="list-group-item list-group-item-action p-3 btn-select-parent border-bottom"
                            data-id="${parent.id}"
                            data-name="${parent.name}">
                        <i class="${parent.icon_class} me-2 text-bold fs-4"></i>
                        <span class="fw-medium">${parent.name}</span>
                        <i class="ti ti-chevron-right float-end mt-1 text-muted"></i>
                    </button>
                `;
                $parentList.insertAdjacentHTML('beforeend', buttonHTML);
            });

            initParentClickEvents();
        })
        .catch(error => {
            console.log(`Error: ${error}`);
            $parentList.innerHTML = `<div class="p-3 text-danger text-center">Error al cargar módulos</div>`;
        })
}

//Funcion para manejar el evento click de los botones creados dinamicamente
async function initParentClickEvents() {
    document.getElementById('parent-list').addEventListener('click', async function (e) {
        const button = e.target.closest('.btn-select-parent');
        if (!button) return; // si no hizo click dentro del botón, salir
        // 🔥 Quitar active a todos
        document.querySelectorAll('.btn-select-parent')
            .forEach(btn => btn.classList.remove('active'));
        // 🔥 Agregar active al botón correcto
        button.classList.add('active');
        await loadSubmodules(button);
    });
}

/**
 * Carga los submódulos de un módulo padre y los dibuja en el contenedor.
 * @param {HTMLElement} element - El botón que fue presionado.
 */
async function loadSubmodules(element) {
    const $btn = $(element);
    const parentId = $btn.data('id');
    const parentName = $btn.data('name');
    const $container = $('#submodule-list');

    // 1. Estética: resaltar el módulo padre seleccionado
    $('.btn-select-parent').removeClass('active');
    $btn.addClass('active');

    // 2. Mostrar skeleton de carga (UX)
    $container.html(`
    <div class="list-group mt-3">

        ${[1, 2, 3, 4].map(() => `
            <div class="list-group-item p-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between placeholder-wave">

                    <div class="d-flex align-items-center gap-2 w-100">
                        <!-- Icon skeleton -->
                        <span class="placeholder rounded"
                              style="width:24px;height:24px;"></span>

                        <!-- Text skeleton -->
                        <span class="placeholder col-6"></span>
                    </div>

                    <!-- Arrow skeleton -->
                    <span class="placeholder rounded"
                          style="width:16px;height:16px;"></span>

                </div>
            </div>
        `).join('')}

        <div class="text-center mt-3">
            <small class="text-muted">Cargando submódulos...</small>
        </div>

    </div>
`);

    // 3. Preparar la URL dinámica reemplazando el placeholder de Laravel
    const url = getSubmodules.replace(':id', parentId);

    // 4. Petición AJAX
    $.get(url, function (data) {
        let html = '<div class="list-group list-group-flush shadow-sm border-start">';

        if (data.length === 0) {
            html = `
                <div class="alert alert-info m-3 text-center">
                    <i class="ti ti-info-circle fs-2 d-block mb-2"></i>
                    Este módulo no tiene submódulos registrados.
                </div>`;
        } else {
            data.forEach(sub => {
                // Verificamos si existe el iframe (usando la lógica de tu controlador)
                const hasIframe = sub.iframe_exists !== null;
                const badge = hasIframe
                    ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-2">Asignado</span>'
                    : '<span class="badge bg-light text-muted border px-2">Sin asignar</span>';

                html += `
                    <button type="button"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center btn-final-selection py-3"
                            data-id="${sub.id}"
                            data-name="${sub.name}"
                            data-url="${sub.url_path}"
                            data_iframe="${sub.iframe_url}"
                            data-parent="${parentName}">
                        <div>
                            <div class="fw-bold text-dark">${sub.name}</div>
                            <small class="text-muted fs-5"><i class="ti ti-link me-1 fs-5"></i>${sub.url_path}</small>
                        </div>
                        ${badge}
                    </button>`;
            });

            initSubModuleClickEvents();
        }

        html += '</div>';
        $container.html(html);

    }).fail(function () {
        $container.html(`
            <div class="alert alert-danger m-3">
                Error al conectar con el servidor. Intente de nuevo.
            </div>`);
    });
}

function initSubModuleClickEvents() {
    document.getElementById('submodule-list').addEventListener('click', function (e) {
        const button = e.target.closest('.btn-final-selection');
        let id = button.getAttribute('data-id');
        let name = button.getAttribute('data-name');
        let url = button.getAttribute('data-url');
        let urlIframe = button.getAttribute('data_iframe');
        let parentName = button.getAttribute('data-parent');

        if (!button) return;
        document.querySelectorAll('.btn-final-selection')
            .forEach(btn => btn.classList.remove('disabled','text-white'));
       button.classList.add('text-white','disabled');

        let inputIdModule = document.getElementById("id_module_input");
        inputIdModule.value = "";
        inputIdModule.value = id;

        let inputParent = document.getElementById("parent_id_input");
        inputParent.value = "";
        inputParent.value = parentName;

        let inputSunmodule = document.getElementById("sub_module_input");
        inputSunmodule.value = "";
        inputSunmodule.value = name;

        let inputUrl = document.getElementById("module_url_input");
        inputUrl.value = "";
        inputUrl.value = `/${url}`;

        let iframe_url_input = document.getElementById("iframe_url_input");
        iframe_url_input.value = "";
        iframe_url_input.value = `${urlIframe == "null" ? '' : urlIframe}`;

        $('#iframe_url_input').on('input', function() {
            const url_iframe = $(this).val();
            if (url_iframe.includes('http')) {
                $('#preview-placeholder').hide();
                $('#iframe-preview').attr('src', url_iframe).show();
            } else {
                $('#preview-placeholder').show();
                $('#iframe-preview').hide();
            }
        });

        $('#iframe_url_input').trigger('input');
        $('#modalSelectorModulos').modal('hide');
    });
}

