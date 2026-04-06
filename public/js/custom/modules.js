let moduleModalInstance = null;
let tableIcons;

document.addEventListener('DOMContentLoaded', () => {
    loadModuleData();

    $('#parent_module_id').select2({
        dropdownParent: $('#modalModule'), // CRÍTICO: Para que funcione dentro de un modal
        width: '100%',
        ajax: {
            url: parentModuleList,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        templateResult: formatModule,      // Renderiza la lista desplegable
        templateSelection: formatModule    // Renderiza la opción seleccionada
    });
})

// Corregir el scroll de Adminto cuando hay múltiples modales
$(document).on('hidden.bs.modal', '.modal', function () {
    if ($('.modal:visible').length > 0) {
        $('body').addClass('modal-open');
        // Ajustamos el z-index dinámicamente si es necesario
        $('.modal-backdrop').first().css('z-index', 1040);
    }
});

document.getElementById("btnAddModule").addEventListener("click", function () {
    openModal("create");
})

document.getElementById("module_url_input").addEventListener("input", function () {
    let urlValue = this.value;

    if (urlValue) {
        let processedValue = urlValue.replace(/\s+/g, '_');
        processedValue = processedValue.replace(/[^a-z0-9-_]/g, '');

        processedValue = processedValue.replace(/_+/g, '_');
        processedValue = processedValue.replace(/-+/g, '-');

        if (processedValue.startsWith('_')) {
            processedValue = processedValue.substring(1);
        }

        this.value = processedValue;
    }
});

document.getElementById("module_url_input").addEventListener("blur", function () {
    // Elimina guiones bajos al final si el usuario dejó uno suelto al dejar de escribir
    this.value = this.value.replace(/_$/, '');
});

document.querySelectorAll("#form_module .validate").forEach((element) => {
    element.addEventListener("input", () => {
        validateField(element);
    });
});

document.getElementById("btnSaveModule").addEventListener("click", async function () {
    const fields = document.querySelectorAll("#form_module .validate");
    const iconografia = document.getElementById("module_icon_input");
    const parentModule = document.getElementById("parent_module_id");
    const check_show_menu = document.getElementById("check_show_menu");
    const module_description = document.getElementById("module_description");
    const id_module_edit = document.getElementById("id_module_input");

    let isFormValid = true;
    let form_data = {};

    fields.forEach((element) => {
        // Ejecutamos validación y actualizamos el estado global del formulario
        if (element.offsetParent !== null) {
            if (!validateField(element)) {
                isFormValid = false;
            }

            form_data[element.id] = element.value;

        } else {
            //limpiar mensajes de errores si los hay
            element.classList.remove("is-invalid", "is-valid");
        }
    });

    if (iconografia.offsetParent !== null) {
        if (!iconografia.dataset.valid) iconografia.dataset.valid = "Icono";

        if (!validateField(iconografia)) {
            isFormValid = false;
        } else {
            form_data[iconografia.id] = iconografia.value;
        }
    }

    if (parentModule.offsetParent !== null) {
        if (!parentModule.dataset.valid) parentModule.dataset.valid = "Módulo Principal";

        // IMPORTANTE: Validamos el select directamente
        if (!validateField(parentModule)) {
            isFormValid = false;
        } else {
            form_data[parentModule.id] = parentModule.value;
        }
    }

    if (isFormValid) {
        form_data.id_module_edit = id_module_edit.value ? id_module_edit.value : null;
        form_data.method = id_module_edit.value ? 'PUT' : 'POST';
        form_data.show_menu = check_show_menu.checked;
        form_data.module_description = module_description.value || null;

        console.log(form_data);

        Swal.fire({
            title: 'Procesando...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const res = await fetch(saveOrUpdate,
                {
                    method: form_data.method,
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

                //loadUserData();
                //userModalInstance.hide();
            } else {
                let errorMsg = result.message;
                throw new Error(errorMsg);
            }

            loadModuleData();
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

function formatModule(module) {
    if (!module.id) {
        return module.text
    }

    let iconClass = module.icon ? module.icon : 'ti ti-package';

    let $module = $(
        `<span><i class="${iconClass} me-2 fs-5"></i>${module.text}</span>`
    );

    return $module;
}

function loadModuleData() {
    $('#modules-table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: modulesList,
        columns: [
            // Columna 0: ID de grupo (oculto)
            {data: 'group_id', name: 'group_id', visible: false, searchable: false},
            // Columna 1: Nombre de grupo (oculto)
            {data: 'group_name', name: 'group_name', visible: false},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'url_path', name: 'url_path'},
            {data: 'icon_class', name: 'icon_class'},
            {data: 'created_at', name: 'created_at', className: 'text-center'},
            {data: 'updated_at', name: 'updated_at', className: 'text-center'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        // Mantenemos el orden definido en el controlador
        order: [],
        rowGroup: {
            dataSrc: 'parent_name',
            startRender: function (rows, group) {
                return $('<tr/>')

                    .append(`
                <td colspan="100%" class="py-2">
                    <div class="d-flex align-items-center px-2">
                        <div class="flex-shrink-0 me-2">
                            <span class="badge bg-primary rounded-circle p-1">
                                <i class="ti ti-folders text-white font-size-12"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <span class="text-dark fw-bold fs-5 text-uppercase" style="letter-spacing: 0.5px;">
                                ${group}
                            </span>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge rounded-pill border border-primary text-primary font-size-10">
                                ${rows.count()} Submódulos
                            </span>
                        </div>
                    </div>
                </td>
            `);
            }
        },
        language: language_datatable
    });
}

function loadIconCatalog() {
    // Si la tabla ya existe, no la reinicializamos
    if ($.fn.DataTable.isDataTable('#table-icon-catalog')) {
        return;
    }

    // Ruta al archivo JSON de iconos (asegúrate de que exista)
    let jsonIconsPath = jsonTable;

    tableIcons = $('#table-icon-catalog').DataTable({
        destroy: true,
        processing: true,
        // Carga de datos local (Ajax a un archivo estático)
        ajax: {
            url: jsonIconsPath,
            dataSrc: '' // El JSON es un array simple, dataSrc va vacío
        },
        scrollY: '500px',
        scrollCollapse: true,
        paging: true,
        columns: [
            // 1. Columna del Icono (Visual) - Aquí está la clave del render
            {
                data: null,
                name: 'icon_visual',
                className: 'text-center',
                orderable: false,
                searchable: false,
                render: function (data) {
                    // 'data' es el nombre del icono, ej: 'ti ti-home'
                    return `
                    <div class="avatar-sm mx-auto icon-item-preview">
                        <div class="avatar-title rounded-circle bg-soft-primary text-primary fs-3">
                            <i class="${data}"></i>
                        </div>
                    </div>`;
                }
            },
            // 2. Columna del Nombre Técnico
            {
                data: null,
                name: 'icon_name',
                render: function (data) {
                    return `<code class="bg-light text-dark font-size-13 p-1 rounded-1">${data}</code>`;
                }
            },
            // 3. Columna de Acción
            {
                data: null,
                name: 'actions',
                className: 'text-center',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                    <button type="button" class="btn btn-soft-primary btn-sm rounded-pill px-3 fs-10" onclick="assignIcon('${data}')">
                        <i class="ti ti-circle-check-filled me-1"></i> Seleccionar
                    </button>`;
                }
            }
        ],
        language: language_datatable, // Usa tu configuración de idioma existente
        pageLength: 20, // Mostramos pocos para no saturar el DOM
        // Estilo Adminto para la búsqueda y paginación
        dom: '<"row"<"col-sm-12 col-md-6"f><"col-sm-12 col-md-6 text-end"i>>t<"row"<"col-sm-12 col-md-12 text-center"p>>',
    });

    // --- GESTIÓN DE SELECCIÓN ---

    // 1. Doble clic en la fila para seleccionar
    $('#table-icon-catalog tbody').on('dblclick', 'tr', function () {
        let rowData = tableIcons.row(this).data();
        if (rowData) {
            assignIcon(rowData);
        }
    });
}

/**
 * Asigna el icono seleccionado al formulario y actualiza la vista previa
 * @param {string} iconName - Nombre de la clase, ej: 'ti ti-home'
 */
function assignIcon(iconName) {
    // 1. Asignamos el valor al input del formulario de atrás
    $('#module_icon_input').val(iconName);
    $('#icon_preview').attr('class', iconName + ' fs-3 text-primary');

    // 2. Cerramos el selector y abrimos AUTOMÁTICAMENTE el formulario principal
    // Esto hace el efecto de "Toggle Back"
    var myModal = new bootstrap.Modal(document.getElementById('modalModule'));

    // Ocultamos el selector
    $('#modalIconSelector').modal('hide');

    // Mostramos el principal (Toggle back)
    $('#modalModule').modal('show');
}

 async function openModal(operation = null, data = null) {
    const modalElement = document.getElementById('modalModule');
    const modalTitle = document.getElementById("modalModuleLabel");
    const buttonUser = document.getElementById("btnSaveModule");
    const form = document.getElementById("form_module");

    if (!moduleModalInstance) {
        moduleModalInstance = new bootstrap.Modal(modalElement);
    }

    if (operation === "create") {
        form.reset();
        form.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        document.getElementById("div_parent_select").style.display = "none";
    }

    const isCreate = operation === 'create' ? true : operation === 'update' ? false : null;
    const config = {
        title: isCreate ? 'Agregar nuevo Módulo' : 'Actualizar Módulo',
        button: isCreate ? 'Guardar' : 'Actualizar',
        icon: isCreate ? 'ti-device-floppy fs-4' : 'fs-4 ti-refresh' // Opcional: Iconos dinámicos
    };

    modalTitle.textContent = config.title;
    buttonUser.innerHTML = `<i class="ti ${config.icon} me-1"></i> ${config.button}`;

    if (!isCreate && data) {
        // --- SETEO DE DATOS ---

        // 1. ID oculto para el controlador
        document.getElementById("id_module_input").value = data.id;

        // 2. Campos de texto básicos
        document.getElementById("module_name").value = data.name;
        document.getElementById("module_url_input").value = data.url_path;
        document.getElementById("module_description").value = data.description || '';

        const isShow = document.getElementById("check_show_menu");
        const isSubModule = document.getElementById("check_is_submodule");
        // 3. Manejo de Iconografía
        const iconInput = document.getElementById("module_icon_input");
        const iconPreview = document.getElementById("icon_preview");

        isShow.checked = parseInt(data.show_menu) === 1 ? true : false;

       if (iconInput.offsetParent === null) {
           iconInput.value = data.icon_class || '';
           // Actualizar el icono visualmente (usamos Tabler Icons)
           iconPreview.className = data.icon_class != null
            ? `${data.icon_class} fs-3 text-primary`
            : `ti ti-brush fs-3`
       }

        // 4. Manejo de Select2 (Módulo Padre)
        const parentSelect = $('#parent_module_id');

        if (data.parent_id) {
            // Verificamos si la opción ya existe en el select
            let optionExists = parentSelect.find(`option[value='${data.parent_id}']`).length > 0;

            if (!optionExists) {
                // Si no existe, la creamos usando el 'parent_name' que enviamos desde Laravel
                const newOption = new Option(data.parent_name || 'Cargando...', data.parent_id, true, true);
                parentSelect.append(newOption).trigger('change');
            } else {
                // Si ya existe, simplemente la seleccionamos
                parentSelect.val(data.parent_id).trigger('change');
            }

            // Lógica de UI para Submódulos
            if (isSubModule) isSubModule.checked = true;
            document.getElementById("div_parent_select").style.display = "block";
            document.getElementById("divIcono").style.display = "none";

        } else {
            // Si es null, es un módulo principal
            parentSelect.val(null).trigger('change');

            if (isSubModule) isSubModule.checked = false;
            document.getElementById("div_parent_select").style.display = "none";
            document.getElementById("divIcono").style.display = "block";
        }
    }

    await moduleModalInstance.show();
}

function toggleSubmoduleView() {
    const isSubmodule = document.getElementById('check_is_submodule').checked;
    const divSelect = document.getElementById('div_parent_select');
    const divIcono = document.getElementById('divIcono');
    const selectElement = document.getElementById('parent_module_id');

    if (isSubmodule) {
        // Mostramos el div usando flex para que el input-group funcione bien
        divSelect.style.display = 'block';
        divIcono.style.display = 'none';
        selectElement.setAttribute('required', 'required');
        document.getElementById("module_url_input").value = null;
    } else {
        // Ocultamos y limpiamos el valor
        divSelect.style.display = 'none';
        divIcono.style.display = 'block';
        selectElement.value = "";
        selectElement.removeAttribute('required');
        document.getElementById("module_url_input").value = "#";
    }
}

document.addEventListener("click", async (e) => {
    if (e.target.classList.contains('btn-edit')) {
        const moduleId = e.target.dataset.idedit;

        try {
            Swal.fire({
                title: 'Procesando...',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch(`${parentModuleList}?id_module_edit=${moduleId}`, {
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


            // Si el controlador devuelve el array directamente [...]
            if (Array.isArray(result) && result.length > 0) {
                openModal("update", result[0]); // Enviamos solo el primer objeto
            }
            // Si el controlador lo envuelve en { success: true, data: [...] }
            else if (result.success && result.data.length > 0) {
                openModal("update", result.data[0]);
            } else {
                Swal.fire('Atención', 'No se encontraron datos para este módulo', 'warning');
            }
        } catch (error) {
            Swal.fire('Error', error.message, 'error');
        }
    }
});
