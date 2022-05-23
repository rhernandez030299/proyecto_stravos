Dropzone.autoDiscover = false;
$(document).ready(function() {
    var calendar = '';
    var isGlobal = true;
    $(".proyectos-menu").addClass("menu-item-active");

    moment.locale('es');
    $('input[name="fecha_ini"]').daterangepicker({
        locale: {
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            format: 'YYYY/MM/DD'
        }
    });

    $('input[name="fecha_ini_update"]').daterangepicker({
        locale: {
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            format: 'YYYY/MM/DD'
        }
    });

    $(document).on('click', '#add_button', function(e) {
        e.preventDefault();
        $('#historiaUsuarioModal').modal('show');
    });

    $(document).on('click', '#finalizar_modulo', function(e) {
        e.preventDefault();
        $('#finalizarModuloUsuarioModal').modal('show');
    });

    $(document).on('click', '#abrir_modulo', function(e) {
        e.preventDefault();
        $('#abrirModuloModal').modal('show');
    });


    cargar_historias();

    function cargar_historias() {
        var data = {};
        data.url_proyecto = $url_proyecto;
        data.url_metodologia = $url_metodologia;
        data.url_fase = $url_fase;
        data.url_modulo = $url_modulo;
        ajax('historia/obtener_historia', data, function(response) {

            if (response.res != $EXIT_SUCCESS) {
                heading = "Modificado!";
                icon = "success";
                text = response.msg;
                return mensaje_toast(heading, text, icon);
            }

            $("#contador-elementos div").remove();

            if (response.data.length == 0) {
                $("#contador-elementos").append(`<div class="contenedor-elementos">No hay historias registradas</div>`);
            }

            $("#subheader_total").text(response.data.length + " Total");

            for (var i = 0; i < response.data.length; i++) {
                var evidencia = '';

                if (response.data[i]['archivos'].length > 0) {
                    evidencia += `
                                <p>
                                    <strong>Evidencias</strong>
                                </p>
                                <div class="row evidencia">`;

                    for (var j = 0; j < response.data[i]['archivos'].length; j++) {

                        var ext = response.data[i]['archivos'][j]['ruta'].split('.').pop();
                        var url = base_url + 'uploads/' + response.data[i]['archivos'][j]['ruta'];
                        if (ext == "pdf") {
                            url = base_url + "assets/public/img/pdf.svg";
                        } else if (ext.indexOf("doc") != -1) {
                            url = base_url + "assets/public/img/doc.svg";
                        } else if (ext.indexOf("xls") != -1) {
                            url = base_url + "assets/public/img/csv.svg";
                        }

                        evidencia += `
                            <div class="col-md-3">
                                <div class="card card-custom gutter-b card-stretch">
                                    <div class="card-body border-rgba">
                                        <div class="d-flex flex-column align-items-center">
                                            <img alt="" class="max-h-100px" src="` + url + `">
                                            <a href="` + base_url + 'historia/descargas/' + response.data[i]['archivos'][j]['ruta'] + `" class="text-dark-75 font-weight-bold mt-15 font-size-lg word-break">` + response.data[i]['archivos'][j]['client_name'] + `</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    evidencia += "</div>";
                }

                $("#contador-elementos").append(`
                    <div class="col-md-12 mb-10 hu-container-${response.data[i][0]}" id="${response.data[i][0]}">
                        <div class="card card-custom">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">` + response.data[i][1] + `
                                    <span class="label label-` + response.data[i]['estadoColor'] + ` label-inline mr-2 mb-5" style="max-width: max-content;">` + response.data[i][8] + `</span></h3>
                                </div>
                                <div class="card-toolbar">
                                    <span class="label label-` + response.data[i]['estadoColor'] + ` label-inline mr-2 mb-5" style="max-width: max-content;">Prioridad:  ` + response.data[i][11] + `</span></h3>
                                    ` + response.data[i]["botones"] + `
                                </div>
                                </div>
                                <div class="card-body">
        
                                    <div class="d-flex flex-wrap">
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="font-weight-bolder mb-">Descripción</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1">
                                            <span class="font-weight-normal font-size-base">` + response.data[i][5] + `</span>
                                        </div>
                                        <div class="mr-12 d-flex flex-column mb-7 pull-right">
                                            <span class="font-weight-bolder mb-">Presupuesto</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1">
                                            <span class="font-weight-normal font-size-base">` + response.data[i]['presupuesto'] + `</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap">
                                        
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="font-weight-bolder mb-0">Tiempo estimado</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1">
                                            <span class="font-weight-normal font-size-base">` + response.data[i][7] + `</span>
                                        </div>

                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="font-weight-bolder mb-0">Entrega</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1">
                                            <span class="font-weight-normal font-size-base">` + response.data[i][2] + `</span>
                                        </div>

                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="font-weight-bolder mb-0">Riesgo del desarrollo</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1">
                                            <span class="font-weight-normal font-size-base">` + response.data[i][4] + `</span>
                                        </div>
                                        
                                        <div class="d-flex flex-column flex-lg-fill float-left">
                                            <span class="font-weight-bolder mb-1">Responsable</span>
                                            <a href="` + response.data[i]['url_usuario'] + `">
                                            <div class="symbol-group symbol-hover">
                                                <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="` + response.data[i][9] + `" data-original-title="` + response.data[i][9] + `">
                                                    <img alt="Pic" src="` + response.data[i]['foto_usuario'] + `">
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="ver-mas hide">
                                        <div class="d-flex flex-wrap">
                                            <!--begin: Item-->
                                            <div class="mr-12 d-flex flex-column mb-7">
                                                <span class="font-weight-bolder mb-">Objetivo</span>
                                                <span class="font-weight-bolder font-size-h5 pt-1">
                                                <span class="font-weight-normal font-size-base">` + response.data[i][3] + `</span>
                                            </div>
                                            <!--end::Item-->
                                
                                        </div>

                                        <div class="d-flex flex-wrap">
                                            <!--begin: Item-->
                                            <div class="mr-12 d-flex flex-column mb-7">
                                                <span class="font-weight-bolder mb-">Observaciones</span>
                                                <span class="font-weight-bolder font-size-h5 pt-1">
                                                <span class="font-weight-normal font-size-base">` + response.data[i][6] + `</span>
                                            </div>
                                        </div>
                                        ` + evidencia + `
                                    </div>
                        
                                    <div class="ps__rail-x" style="left: 0px; bottom: -184px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 184px; height: 200px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 66px; height: 71px;"></div></div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <button class="btn btn-light-primary font-weight-bold ver-mas-boton">Detalles</button>
                                </div>
                            </div>
                        </div>  
                    </div>
                `);
            }
            goContainer();
        });
    }

    $(document).on('click', '.ver-mas-boton', function(e) {
        $(this).parent().parent().find(".card-body .ver-mas").toggleClass('hide');;
    });

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form) {
            if ($(".dz-image").length == 0) {
                var data = {};

                data.titulo = document.getElementById("titulo").value.trim();
                data.fecha_ini = document.getElementById("fecha_ini").value.trim();
                data.objetivo = document.getElementById("objetivo").value.trim();
                data.prioridad = document.getElementById("prioridad").value.trim();
                data.riesgodesarrollo = document.getElementById("riesgodesarrollo").value.trim();
                data.numeracion = document.getElementById("numeracion").value.trim();
                data.descripcion = document.getElementById("descripcion").value.trim();
                data.responsable = document.getElementById("responsable").value.trim();
                data.url_proyecto = $url_proyecto;
                data.url_modulo = $url_modulo;
                ajax('historia/crear', data, function(response) {
                    heading = "Alerta!";
                    text = response.msg;
                    icon = "error";
                    if (response.res != $EXIT_ERROR) {
                        cargar_historias();
                        heading = "Ingresado!";
                        text = response.msg;
                        icon = "info";
                        $('#historiaUsuarioModal').modal('hide');

                        var myEvent = {
                            id: response.data.idhistoria,
                            title: response.data.titulo,
                            start: response.data.fecha_fin,
                            end: response.data.fecha_fin,
                            color: "#f39c12",
                            textColor: "#FFF"
                        };
                        calendar.addEvent(myEvent);

                    }
                    return mensaje_toast(heading, text, icon);
                });
            }
        }
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('historia/listar_id', data, function(response) {
            $('#historiaUsuarioModalupdate').modal('show');

            $("#titulo_update").val(response.titulo);

            $('#fecha_ini_update').data('daterangepicker').setStartDate(response.fecha_ini);
            $('#fecha_ini_update').data('daterangepicker').setEndDate(response.fecha_fin);
            $("#objetivo_update").val(response.objetivo);
            $("#prioridad_update").val(response.idprioridad).trigger('change');
            $("#riesgodesarrollo_update").val(response.riesgodesarrollo).trigger('change');
            $("#numeracion_update").val(response.numeracion);
            $("#descripcion_update").val(response.descripcion);

            $("#historia_id_update").val(response.id);

            $("#archivos_update .dz-preview").remove();
            $("#archivos_update").removeClass("dropzone dz-clickable dz-started dz-max-files-reached");
            $("#archivos_update").addClass("dropzone dz-clickable");

            if (response.archivos != "" && response.archivos != null && response.archivos.length > 0) {
                for (var i = 0; i < response.archivos.length; i++) {

                    var ext = response.archivos[i].ruta.split('.').pop();
                    var url = base_url + 'uploads/' + response.archivos[i].ruta;
                    if (ext == "pdf") {
                        url = base_url + "assets/public/img/pdf.jpg";
                    } else if (ext.indexOf("doc") != -1) {
                        url = base_url + "assets/public/img/word.png";
                    } else if (ext.indexOf("xls") != -1) {
                        url = base_url + "assets/public/img/excel.png";
                    }

                    var mockFile = {
                        size: response.archivos[i].peso,
                        name: response.archivos[i].ruta,
                        fileId: i
                    };

                    myDropzoneSlider.emit("addedfile", mockFile);
                    myDropzoneSlider.emit("thumbnail", mockFile, url);
                    myDropzoneSlider.emit("complete", mockFile);
                    myDropzoneSlider.files.push(mockFile);


                }


            }

        });
    });

    //Validate para modificar la metodologia con sus datos ya cargados.
    $("#form_update").validate({
        submitHandler: function(form) {
            var data = {};

            data.id = document.getElementById("historia_id_update").value.trim();
            data.titulo = document.getElementById("titulo_update").value.trim();
            data.fecha_ini = document.getElementById("fecha_ini_update").value.trim();
            data.objetivo = document.getElementById("objetivo_update").value.trim();
            data.prioridad = document.getElementById("prioridad_update").value.trim();
            data.riesgodesarrollo = document.getElementById("riesgodesarrollo_update").value.trim();
            data.numeracion = document.getElementById("numeracion_update").value.trim();
            data.descripcion = document.getElementById("descripcion_update").value.trim();
            data.url_proyecto = $url_proyecto;
            data.url_modulo = $url_modulo;

            ajax('historia/actualizar', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";

                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificado!";
                    icon = "success";
                    $('#historiaUsuarioModalupdate').modal('hide');
                    cargar_historias();
                    var event = calendar.getEventById(data.id);
                    event.remove();
                    var myEvent = {
                        id: data.id,
                        title: data.titulo,
                        start: response.data.fecha_fin,
                        end: response.data.fecha_fin,
                        color: response.data.color,
                        textColor: "#FFF"
                    };
                    calendar.addEvent(myEvent);
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.delete', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('historia/listar_id', data, function(response) {
            $('#historiaModaleliminar').modal('show');
            $("#historia_id_eliminar").val(response.id);
        });
    });

    $(document).on('click', '.eliminar', function(e) {
        var data = {};
        data.id = document.getElementById("historia_id_eliminar").value.trim();
        data.url_modulo = $url_modulo;
        ajax('historia/eliminar', data, function(response) {
            heading = "Alerta!";
            text = response.msg;
            icon = "error";

            if (response.estado != $EXIT_ERROR) {
                cargar_historias();
                heading = "Eliminado!";
                text = response.msg;
                icon = "error";
                var event = calendar.getEventById(data.id);
                event.remove();
            }

            $('#historiaModaleliminar').modal('hide');
            return mensaje_toast(heading, text, icon);
        });
    });

    $(document).on('click', '.finalizar_modulo_submit', function(e) {
        var data = {};
        data.url_proyecto = $url_proyecto;
        data.url_metodologia = $url_metodologia;
        data.url_fase = $url_fase;
        data.url_modulo = $url_modulo;
        ajax('historia/finalizar_modulo', data, function(response) {
            var heading = "Alerta!";
            var text = response.msg;
            var icon = "error";

            if (response.res != $EXIT_ERROR) {
                heading = "Enviado!";
                text = response.msg;
                icon = "success";
                setInterval(function() { location.reload(); }, 3000);
            }

            $('#finalizarModuloUsuarioModal').modal('hide');
            return mensaje_toast(heading, text, icon);
        });
    });

    $(document).on('click', '.abrir_modulo_submit', function(e) {
        var data = {};
        data.url_proyecto = $url_proyecto;
        data.url_metodologia = $url_metodologia;
        data.url_fase = $url_fase;
        data.url_modulo = $url_modulo;
        ajax('historia/abrir_modulo', data, function(response) {
            var heading = "Alerta!";
            var text = response.msg;
            var icon = "error";

            if (response.res != $EXIT_ERROR) {
                heading = "Enviado!";
                text = response.msg;
                icon = "success";
                setInterval(function() { location.reload(); }, 3000);
            }

            $('#abrirModuloModal').modal('hide');
            return mensaje_toast(heading, text, icon);
        });
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.entregada', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('historia/listar_id', data, function(response) {
            $('#historiaModalEntregada').modal('show');
            $("#historia_id_entregar").val(response.id);
        });
    });

    $(document).on('click', '.entregar_submit', function(e) {
        var data = {};
        data.id = document.getElementById("historia_id_entregar").value.trim();
        data.url_modulo = $url_modulo;
        ajax('historia/entregar', data, function(response) {
            heading = "Alerta!";
            text = response.msg;
            icon = "error";

            if (response.estado != $EXIT_ERROR) {
                cargar_historias();
                heading = "Entregada!";
                text = response.msg;
                icon = "success";
                var event = calendar.getEventById(data.id);
                let titulo = event.title;
                let start = event.start;
                event.remove();
                var myEvent = {
                    id: data.id,
                    title: titulo,
                    start: start,
                    end: start,
                    color: '#00a65a',
                    textColor: "#FFF"
                };
                calendar.addEvent(myEvent);
            }

            $('#historiaModalEntregada').modal('hide');
            return mensaje_toast(heading, text, icon);
        });
    });

    $(document).on('click', '.completa', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('historia/listar_id', data, function(response) {
            $('#historiaUsuarioModalestado').modal('show');
            $("#historia_id_estado").val(response.id);
            $("#estado_id").val(4);
            $(".titulo-estado-historia").text("¿Esta seguro que desea marcar como finalizada esta tarea?");
        });
    });

    $(document).on('click', '.incompleta', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('historia/listar_id', data, function(response) {
            $('#historiaUsuarioModalestado').modal('show');
            $("#historia_id_estado").val(response.id);
            $("#estado_id").val(3);
            $(".titulo-estado-historia").text("¿Esta seguro que desea marcar como incompleta esta tarea?");
        });
    });

    $("#form_estado").validate({
        submitHandler: function(form) {
            var data = {};

            data.id = document.getElementById("historia_id_estado").value.trim();
            data.idestado = document.getElementById("estado_id").value.trim();
            data.observacion = document.getElementById("observacion_estado").value.trim();
            data.url_modulo = $url_modulo;

            ajax('historia/cambiar_estado', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";

                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificado!";
                    icon = "success";
                    $('#historiaUsuarioModalestado').modal('hide');
                    cargar_historias();

                    var event = calendar.getEventById(data.id);
                    let titulo = event.title;
                    let start = event.start;

                    event.remove();
                    var myEvent = {
                        id: data.id,
                        title: titulo,
                        start: start,
                        end: start,
                        color: '#f39c12',
                        textColor: "#FFF"
                    };
                    calendar.addEvent(myEvent);

                    if (response.reload == true) {
                        setInterval(function() { location.reload(); }, 3000);
                    }
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });

    var myDropzoneArchivo = new Dropzone("#archivos", {
        autoProcessQueue: false,
        url: base_url + 'historia/crear',
        addRemoveLinks: true,
        dictRemoveFile: 'Remover archivo',
        dictMaxFilesExceeded: 'No se puede ingresar más de tres fotos',
        dictFileTooBig: 'El archivo es demasiado grande ({{filesize}}MiB). Tamaño máximo de archivo: {{maxFilesize}}MiB.',
        uploadMultiple: true,
        parallelUploads: 30,
        init: function() {

            var myDropzoneArchivo = this;
            var contador = 0;

            // Update selector to match your button
            $("#action").click(function(e) {
                contador = 0;
                if ($("#form_ingresar").valid()) {
                    contadorAlerta = 1;
                    myDropzoneArchivo.processQueue();
                }
            });

            this.on('sending', function(file, xhr, formData) {
                var data = $('#frmTarget').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });

                if (contador == 0) {

                    var titulo = document.getElementById("titulo").value.trim();
                    var fecha_ini = document.getElementById("fecha_ini").value.trim();
                    var objetivo = document.getElementById("objetivo").value.trim();
                    var prioridad = document.getElementById("prioridad").value.trim();
                    var riesgodesarrollo = document.getElementById("riesgodesarrollo").value.trim();
                    var numeracion = document.getElementById("numeracion").value.trim();
                    var descripcion = document.getElementById("descripcion").value.trim();
                    var responsable = document.getElementById("responsable").value.trim();
                    var url_proyecto = $url_proyecto;
                    var url_modulo = $url_modulo;

                    formData.append("titulo", titulo);
                    formData.append("fecha_ini", fecha_ini);
                    formData.append("objetivo", objetivo);
                    formData.append("prioridad", prioridad);
                    formData.append("riesgodesarrollo", riesgodesarrollo);
                    formData.append("numeracion", numeracion);
                    formData.append("descripcion", descripcion);
                    formData.append("url_proyecto", url_proyecto);
                    formData.append("url_modulo", url_modulo);
                    formData.append("responsable", responsable);
                }

                contador++;
            });
        },
        success: function(file, response) {
            var response = JSON.parse(response);
            if (response.res == 0) {

                this.removeFile(this.files[0]);
                file.status = undefined;
                myDropzoneArchivo.addFile(file);

                if (contadorAlerta == 1) {
                    heading = "Alerta!";
                    text = response.msg;
                    icon = "error";
                    $.toast({
                        heading: heading,
                        text: text,
                        icon: icon,
                        position: 'top-right',
                        showHideTransition: 'slide',
                        loader: true, // Change it to false to disable loader
                        loaderBg: '#ffffff', // To change the background
                    });

                }

            } else {

                if (contadorAlerta == 1) {
                    $('#historiaUsuarioModal').modal('hide');
                    $("textarea, input[type=text]").val("");

                    heading = "Ingresado!";
                    icon = "success";
                    text = response.msg;
                    myDropzoneArchivo.removeAllFiles();
                    cargar_historias();
                    mensaje_toast(heading, text, icon);
                    $("button, input[type=button], input[type=submit]").attr("disabled", true);

                    var myEvent = {
                        id: response.data.idhistoria,
                        title: response.data.titulo,
                        start: response.data.fecha_fin,
                        end: response.data.fecha_fin,
                        color: "#f39c12",
                        textColor: "#FFF"
                    };
                    calendar.addEvent(myEvent);
                }

            }

            contadorAlerta++;
        },
        removedfile: function(file, data) {
            file.previewElement.remove();
        },
    });

    var myDropzoneSlider = new Dropzone("#archivos_update", {
        url: base_url + 'historia/upload_file',
        addRemoveLinks: true,
        dictRemoveFile: 'Remover archivo',
        init: function() {

            this.on("sending", function(file, xhr, formData) {
                formData.append("idhistoria", $("#historia_id_update").val());
            });

            var submitButton = document.querySelector("#update_button");

            var _this = this;

            submitButton.addEventListener("click", function() {
                if ($("#form_update").valid()) {
                    $(".dz-preview").remove();
                    $("#archivos_update").removeClass("dropzone dz-clickable dz-started dz-max-files-reached");
                    $("#archivos_update").addClass("dropzone dz-clickable dz-max-files-reached");
                }
            });
        },

        success: function(file, response) {
            var response = JSON.parse(response);
            $(file.previewTemplate).append('<span class="server_file hide">' + response.name + '</span>');
        },

        removedfile: function(file, data) {

            var server_file = $(file.previewTemplate).find('.dz-details .dz-filename span').text();

            // Do a post request and pass this path and use server-side language to delete the file
            //$.post("delete.php", { file_to_be_deleted: server_file } ); 
            var data = {};

            data.nombre = server_file;
            data.idproyecto = $("#historia_id_update").val();

            ajax('historia/removed_file', data, function(response) {
                file.previewElement.remove();
            });

        },
    });

    cargar_presupuesto_global();

    function cargar_presupuesto_global(data) {
        var data = {};
        data.url_modulo = $url_modulo;

        ajax('presupuestos/consultar_presupuesto', data, function(response) {
            $(".texto-presupuesto strong").text(response.presupuesto);
        });
    }

    cargarCalendario();

    function cargarCalendario(data) {
        var data = {};
        data.url_proyecto = $url_proyecto;
        data.url_metodologia = $url_metodologia;
        data.url_fase = $url_fase;
        data.url_modulo = $url_modulo;

        $("#nav-calendar").addClass('active');
        ajax('historia/obtener_historia', data, function(response) {

            var data = response.data;
            events = [];

            if( $fecha_inicio != undefined){
                events.push({
                    id: null,
                    title: 'Fecha Inicio',
                    start: $fecha_inicio,
                    end: $fecha_inicio,
                    color: '#00a65a',
                    textColor: "#FFF"
                });
            }

            if( $fecha_finalizacion != undefined){
                events.push({
                    id: null,
                    title: 'Fecha Final',
                    start: $fecha_finalizacion,
                    end: $fecha_finalizacion,
                    color: '#f64e60',
                    textColor: "#FFF"
                });
            }

            for (var i = 0; i < data.length; i++) {
                events.push({
                    id: data[i]['idhistoria'],
                    title: data[i][1],
                    start: data[i]['fecha_fin'],
                    end: data[i]['fecha_fin'],
                    color: data[i]['color'],
                    textColor: "#FFF"
                });
            }

            var calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                plugins: ['interaction', 'dayGrid'],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                defaultDate: new Date(),
                navLinks: false, // can click day/week names to navigate views
                selectable: true,
                selectMirror: true,
                select: function(arg) {
                    var nueva_fecha = arg.end.setDate(arg.end.getDate() - 1);
                    nueva_fecha = new Date(nueva_fecha);
                    nueva_fecha = obtener_fecha(nueva_fecha, true);
                    $("#historiaUsuarioModal").modal("show");
                    $('#fecha_ini').data('daterangepicker').setStartDate(arg.startStr);
                    $('#fecha_ini').data('daterangepicker').setEndDate(nueva_fecha);
                },
                eventClick: function(event, jsEvent, view) {

                    var data = {};
                    data.id = event.event.id;
                    ajax('historia/listar_id', data, function(response) {

                        if (response.estado == 4) {
                            heading = "Alerta!";
                            icon = "info";
                            text = "No es posible modificar esta historia";
                            return mensaje_toast(heading, text, icon);
                        }


                        $('#historiaUsuarioModalupdate').modal('show');

                        $("#titulo_update").val(response.titulo);

                        $('#fecha_ini_update').data('daterangepicker').setStartDate(response.fecha_ini);
                        $('#fecha_ini_update').data('daterangepicker').setEndDate(response.fecha_fin);
                        $("#objetivo_update").val(response.objetivo);
                        $("#prioridad_update").val(response.idprioridad).trigger('change');
                        $("#riesgodesarrollo_update").val(response.riesgodesarrollo);
                        $("#numeracion_update").val(response.numeracion);

                        $("#descripcion_update").val(response.descripcion);

                        $("#historia_id_update").val(response.id);

                        $("#archivos_update .dz-preview").remove();
                        $("#archivos_update").removeClass("dropzone dz-clickable dz-started dz-max-files-reached");
                        $("#archivos_update").addClass("dropzone dz-clickable dz-max-files-reached");


                        if (response.archivos != "" && response.archivos != null && response.archivos.length > 0) {
                            for (var i = 0; i < response.archivos.length; i++) {

                                var ext = response.archivos[i].ruta.split('.').pop();
                                var url = base_url + 'uploads/' + response.archivos[i].ruta;
                                if (ext == "pdf") {
                                    url = base_url + "assets/public/img/pdf.jpg";
                                } else if (ext.indexOf("doc") != -1) {
                                    url = base_url + "assets/public/img/word.png";
                                } else if (ext.indexOf("xls") != -1) {
                                    url = base_url + "assets/public/img/excel.png";
                                }

                                var mockFile = {
                                    size: response.archivos[i].peso,
                                    name: response.archivos[i].ruta
                                };

                                myDropzoneSlider.emit("addedfile", mockFile);
                                myDropzoneSlider.emit("thumbnail", mockFile, url);
                                myDropzoneSlider.emit("complete", mockFile);
                                myDropzoneSlider.files.push(mockFile);
                                $(myDropzoneSlider.files[i].previewTemplate).append('<span class="server_file hide">' + response.archivos[i].ruta + '</span>');

                            }
                        }
                    });
                },
                editable: false,
                eventLimit: true, // allow "more" link when too many events
                events: events
            });


            calendar.render();

            $("#nav-calendar").removeClass('active');
        });
    }

    function obtener_fecha(fecha, format) {

        var dd = fecha.getDate();
        var mm = fecha.getMonth() + 1;
        var yyyy = fecha.getFullYear();

        if (mm < 10) {
            mm = "0" + mm;
        }

        if (dd < 10) {
            dd = "0" + dd;
        }

        var fecha = { "dd": dd, "mm": mm, "yyyy": yyyy };
        if (format == true) {
            fecha = yyyy + "-" + mm + "-" + dd;
        }

        return fecha;
    }

    function goContainer(){
        
        if(isGlobal){
            var urlHash = window.location.href.split("id=")[1];
            if(urlHash != undefined && urlHash != '' && ! isNaN(urlHash) && urlHash != '#'){
                $('html,body').animate({
                    scrollTop: $('.hu-container-' + urlHash).offset().top - 135
                }, 1000);
            }
            isGlobal = false;
        }
    }

    $("#contador-elementos").sortable({
        update: function(event, ui) {
            changeLoadPosition();
        }
    });
    $("#contador-elementos").disableSelection();

    function changeLoadPosition(){
        var data = {};
        var historias = [];
        var count = 0;
        $(".ui-sortable > div").each(function() {
            let id = $(this).attr("id");
            if(id != undefined){
                historias[count] = $(this).attr("id");
                count++;
            }
        });

        data.historias = historias;
        ajax('historia/ordenar', data, function(response) {
            
        }, 'POST');
    }

    var historia_id = '';

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.presupuesto', function(e) {
        $('#presupuesto_table').DataTable().destroy();
        historia_id = $(this).attr("id");
        cargar_presupuesto(historia_id);
        $('#presupuestoModalListar').modal('show');
        
    });

    $(document).on('click', '#crear_presupuesto_boton', function(e) {
        e.preventDefault();        
        $("#form_presupuesto #idresponsable, #form_presupuesto #idcategoria").val("").trigger('change');
        $('#form_presupuesto input[type=text]').val('');
        $('#presupuestoModalCrear').modal('show');
        $('#presupuestoModalListar').modal('hide');
    });

    $("#form_presupuesto").validate({
        submitHandler: function(form) {
            var data = {};

            data.id = historia_id;
            data.descripcion = $("#form_presupuesto #descripcion").val().trim();
            data.cantidad = $("#form_presupuesto #cantidad").val().trim();
            data.valor_unidad = $("#form_presupuesto #valor_unidad").val().trim();
            data.idcategoria = $("#form_presupuesto #idcategoria").val().trim();
            data.idresponsable = $("#form_presupuesto #idresponsable").val().trim();

            ajax('presupuestos/crear', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";

                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificado!";
                    icon = "success";
                    $('#presupuestoModalCrear').modal('hide');
                    cargar_historias();
                    $('#presupuesto_table').DataTable().destroy();
                    cargar_presupuesto(historia_id);
                    cargar_presupuesto_global();
                    $('#presupuestoModalListar').modal('show');
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });

    function cargar_presupuesto(historia_id){
        var data_table = $('#presupuesto_table').DataTable({
            "processing":true,
            "serverSide":true,
            "order":[],
            "ajax":{
                url: base_url+"presupuestos/listar_presupuestos",
                type:"POST",
                 data:{"historia_id":historia_id}
            },
            "columnDefs":[
            {
                "targets":[5],
                "orderable":false,
            },
            ],

            "rowCallback": function( row, data ) {
                $(row).addClass('xs-block');
                $('td:nth-child(1)', row).attr("data-title","Descripción");
                $('td:nth-child(2)', row).attr("data-title","Cantidad");
                $('td:nth-child(3)', row).attr("data-title","Valor unidad");
                $('td:nth-child(4)', row).attr("data-title","Total");
                $('td:nth-child(5)', row).attr("data-title","Categoría");
                $('td:nth-child(6)', row).attr("data-title","Acción");
            }
        });
    }

     //Mostrar la ventana modal y cargar renombrar el texto
     $(document).on('click', '.deletepresupuesto', function(e){
        e.preventDefault();
        var data  ={};
        data.id = $(this).attr("id");        
        ajax('presupuestos/listar_id',data,function(response){
            // $('#presupuestoModalEliminar').modal('show');
            // $('#presupuestoModalListar').modal('hide');

            $('#presupuestoModalEliminar').modal('show');
            $('#presupuestoModalListar').modal('hide');
            
            $("#presupuestoModalEliminar > div > div > div > h4").html("Esta seguro de eliminar el presupuesto '"+response.descripcion+"'");
            $(".eliminarpresupuesto").val("Eliminar");
            $(".eliminarpresupuesto").attr("id", response.id);        
        });
    });

    //Enviar los datos al controlador para eliminarlo
    $(document).on('click', '.eliminarpresupuesto', function(e){
        var data  ={};
        data.id = $(this).attr("id");

        ajax('presupuestos/eliminar',data,function(response){
            heading = "Alerta!";
            text = response.msg;
            icon = "error";    

            if(response.estado!=$EXIT_ERROR){
                heading = "Eliminado!";
                text = response.msg;
                icon = "error";                
            }

            $('#presupuestoModalEliminar').modal('hide');
            $('#presupuesto_table').DataTable().destroy();
            cargar_presupuesto(historia_id);
            cargar_presupuesto_global();
            $('#presupuestoModalListar').modal('show');
            cargar_historias();
            return mensaje_toast(heading,text,icon);
        });
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.updatepresupuesto', function(e) {
        var data = {};
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('presupuestos/listar_id', data, function(response) {
            
            $("#form_presupuesto_update #idpresupuesto_update").val(response.id);
            $("#form_presupuesto_update #descripcion_update").val(response.descripcion);
            $("#form_presupuesto_update #cantidad_update").val(response.cantidad);
            $("#form_presupuesto_update #valor_unidad_update").val(response.valor_unidad);
            $("#form_presupuesto_update #idcategoria_update").val(response.idcategoria).trigger('change');
            $("#form_presupuesto_update #idresponsable_update").val(response.idresponsable).trigger('change');
        
            $('#presupuestoModalUpdate').modal('show');
            $('#presupuestoModalListar').modal('hide');
        });
    });

    //Validate para modificar la metodologia con sus datos ya cargados.
    $("#form_presupuesto_update").validate({
        submitHandler: function(form) {
            var data = {};
            
            data.idpresupuesto = $("#form_presupuesto_update #idpresupuesto_update").val().trim();
            data.descripcion = $("#form_presupuesto_update #descripcion_update").val().trim();
            data.cantidad = $("#form_presupuesto_update #cantidad_update").val().trim();
            data.valor_unidad = $("#form_presupuesto_update #valor_unidad_update").val().trim();
            data.idcategoria = $("#form_presupuesto_update #idcategoria_update").val().trim();
            data.idresponsable = $("#form_presupuesto_update #idresponsable_update").val().trim();

            ajax('presupuestos/actualizar', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";

                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificado!";
                    icon = "success";                    
                    $('#presupuestoModalUpdate').modal('hide');
                    $('#presupuesto_table').DataTable().destroy();
                    cargar_presupuesto(historia_id);
                    cargar_presupuesto_global();
                    $('#presupuestoModalListar').modal('show');
                    cargar_historias();
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });
});