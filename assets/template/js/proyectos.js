Dropzone.autoDiscover = false;
$(document).ready(function() {

    var inicio = 0;
    var fin = 10;
    var length = 10;

    $(".proyectos-menu").addClass("menu-item-active");

    $(document).on('click', '.ver-mas-boton', function(e) {
        console.log($(this).parents('card'));
        $(this).parents('.card').find(".card-body .ver-mas").toggleClass('hide');
    });

    $(document).on('click', '.ver-presupuesto', function(e) {

        var data = {};
        data.id = $(this).attr('attr-id-presupuesto');

        ajax('proyectos/obtener_presupuesto', data, function(response) {

            $('#presupuesto_table thead, #presupuesto_table tbody').html('');
            
            var cabeceraPresupuesto = '';
            var cuerpoPresupuesto = '';

            for (var j = 0; j < response.length; j++) {
                cabeceraPresupuesto += "<th> " + capitalizarPrimeraLetra(response[j]['nombre']) + " </th>";
                cuerpoPresupuesto += "<td> " + response[j]['total'] + " </td>";
            }

            $('#presupuesto_table thead').html(cabeceraPresupuesto);
            $('#presupuesto_table tbody').html(cuerpoPresupuesto);
            $('#proyectosModalPresupuesto').modal('show');
        });
    });

    var FotoEdit = { init: function() { new KTImageInput("foto") } };
    var FotoEditUpdate = { init: function() { new KTImageInput("foto_update") } };
    jQuery(document).ready(function() {
        FotoEdit.init();
        FotoEditUpdate.init();
    });

    $(document).on('click', '#add_button', function(e) {
        e.preventDefault();
        $('#proyectosModal').modal('show');
    });

    moment.locale('es');
    $('input[name="fecha_inicio"]').daterangepicker({
        locale: {
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            format: 'YYYY/MM/DD'
        }
    });

    $('input[name="fecha_inicio_update"]').daterangepicker({
        locale: {
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            format: 'YYYY/MM/DD'
        }
    });

    cargar_proyecto(inicio, true);

    function cargar_proyecto(inicio, estado = true) {
        var data = {};

        data.start = inicio;
        data.length = length;
        data.estado = estado;

        var nombre_contenedor = "";

        if(estado != true && estado != false){
            return;
        }

        if(estado){
            nombre_contenedor =  "contador-elementos-activos";
        }else if(!estado){
            nombre_contenedor =  "contador-elementos-inactivos";
        }

        if (inicio == 0) {
            $(".primero, .anterior").addClass("disabled");
        } else {
            $(".primero, .anterior").removeClass("disabled");
            $(".ultimo, .siguiente").removeClass("disabled");
        }

        data.search = $("#subheader_search_form").val();

        ajax('proyectos/obtener_proyectos', data, function(response) {

            if (response.res != $EXIT_SUCCESS) {
                heading = "Modificado!";
                icon = "success";
                text = response.msg;
                return mensaje_toast(heading, text, icon);
            }

            $("#"+nombre_contenedor+" div").remove();

            if (response.data.length == 0) {
                $("#"+nombre_contenedor+"").append(`<div class="contenedor-elementos">No tienes proyectos asignados</div>`);
            }

            $("#subheader_total").text(response.count + " Total");

            if (response.data.length < length) {
                $(".siguiente, .ultimo").addClass("disabled");
            } else {
                $(".siguiente, .ultimo").removeClass("disabled");
            }

            if (response.count <= length) {
                $(".siguiente, .ultimo").addClass("disabled");
            }

            fin = Math.floor(response.count / length) * length;

            var cantidad_filtro = (response.count < length) ? response.count : length;

            $(".display-text").text("Mostrando " + cantidad_filtro + " registros de " + response.count + " totales");

            for (var i = 0; i < response.data.length; i++) {

                var nombreMetodologia = "";
                var urlMetodologia = "";
                for (var j = 0; j < response.data[i].metodologia.length; j++) {
                    nombreMetodologia = response.data[i].metodologia[j]["nombre"];
                    urlMetodologia = response.data[i].metodologia[j]["url"];
                }

                var miembro = "";
                var contador = 0;
                for (var j = 0; j < response.data[i].miembro.length; j++) {

                    contador++;
                    if (contador > 5) {
                        break;
                    }

                    var url_image = base_url + 'assets/public/img/imagen3.jpg';
                    if (response.data[i].miembro[j].ruta_imagen != null && response.data[i].miembro[j].ruta_imagen != '' && response.data[i].miembro[j].ruta_imagen != undefined) {
                        url_image = base_url + 'uploads/' + response.data[i].miembro[j].ruta_imagen;
                    }

                    miembro += `
                        <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="` + response.data[i].miembro[j].nombre + " " + response.data[i].miembro[j].apellido + `" data-original-title="` + response.data[i].miembro[j].nombre + " " + response.data[i].miembro[j].apellido +`">
                            <img alt="Pic" src="` + url_image + `">
                        </div>
                    `;
                }

                if (response.data[i].miembro.length > 5) {
                    miembro += `
                        <div class="symbol symbol-30 symbol-circle symbol-light">
                            <span class="symbol-label font-weight-bold">` + (response.data[i].miembro.length - 5) + `+</span>
                        </div>
                    `
                }

                var miembro_profesor = "";
                var contador = 0;
                for (var j = 0; j < response.data[i].miembro_profesor.length; j++) {

                    contador++;
                    if (contador > 5) {
                        break;
                    }

                    var url_image = base_url + 'assets/public/img/imagen3.jpg';
                    if (response.data[i].miembro_profesor[j].ruta_imagen != null && response.data[i].miembro_profesor[j].ruta_imagen != '' && response.data[i].miembro_profesor[j].ruta_imagen != undefined) {
                        url_image = base_url + 'uploads/' + response.data[i].miembro_profesor[j].ruta_imagen;
                    }

                    miembro_profesor += `
                        <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="` + response.data[i].miembro_profesor[j].nombre + `" data-original-title="` + response.data[i].miembro_profesor[j].nombre + `">
                            <img alt="Pic" src="` + url_image + `">
                        </div>
                    `;
                }

                if (response.data[i].miembro_profesor.length > 5) {
                    miembro_profesor += `
                        <div class="symbol symbol-30 symbol-circle symbol-light">
                            <span class="symbol-label font-weight-bold">` + (response.data[i].miembro_profesor.length - 5) + `+</span>
                        </div>
                    `
                }

                var archivos = '';
                for (var j = 0; j < response.data[i].archivo.length; j++) {

                    var url = base_url + 'uploads/' + response.data[i].archivo[j].ruta;
                    var ext = (response.data[i].archivo[j].ruta).split(".");

                    ext = (ext[ext.length - 1]);

                    if (ext == "pdf") {
                        url = base_url + "assets/public/img/pdf.jpg";
                    } else if (ext.indexOf("doc") != "-1") {
                        url = base_url + "assets/public/img/word.png";
                    } else if (ext.indexOf("xls") != "-1") {
                        url = base_url + "assets/public/img/excel.png";
                    }

                    archivos += `
                        <div class="col-md-6">
                            <div class="card card-custom gutter-b card-stretch">
                                <div class="card-body border-rgba">
                                    <div class="d-flex flex-column align-items-center">
                                        <img alt="" class="max-h-100px" src="` + url + `">
                                        <a href="` + base_url + 'proyectos/descargas/' + response.data[i].archivo[j].ruta + `" class="text-dark-75 font-weight-bold mt-15 font-size-lg word-break">` + response.data[i].archivo[j].ruta + `</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                for (var j = 0; j < response.data[i].archivo_metodologia.length; j++) {

                    var url = base_url + 'uploads/' + response.data[i].archivo_metodologia[j].ruta;
                    var ext = (response.data[i].archivo_metodologia[j].ruta).split(".");

                    ext = (ext[ext.length - 1]);

                    if (ext == "pdf") {
                        url = base_url + "assets/public/img/pdf.jpg";
                    } else if (ext.indexOf("doc") != "-1") {
                        url = base_url + "assets/public/img/word.png";
                    } else if (ext.indexOf("xls") != "-1") {
                        url = base_url + "assets/public/img/excel.png";
                    }

                    archivos += `
                        <div class="col-md-6">
                            <div class="card card-custom gutter-b card-stretch">
                                <div class="card-body border-rgba">
                                    <div class="d-flex flex-column align-items-center">
                                        <img alt="" class="max-h-100px" src="` + url + `">
                                        <a href="` + base_url + 'proyectos/descargas/' + response.data[i].archivo_metodologia[j].ruta + `" class="text-dark-75 font-weight-bold mt-15 font-size-lg word-break">` + response.data[i].archivo_metodologia[j].ruta + `</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }


                var imagen = `
                    <div class="symbol symbol-lg-65 symbol-circle symbol-primary">
                        <span class="symbol-label font-size-h3 font-weight-boldest">` + (response.data[i].nombre).substring(0, 1).toUpperCase() + `</span>
                    </div>
                `;

                if (response.data[i].ruta_imagen != null && response.data[i].ruta_imagen != '' && response.data[i].ruta_imagen != undefined) {
                    imagen = `<img src="` + base_url + 'uploads/' + response.data[i].ruta_imagen + `" alt="image">`;
                }

                var botones = "";
                if (response.data[i].botones != '') {
                    botones = `<div class="card-toolbar mb-auto">
                                        
                                <div class="dropdown dropleft">
                                    <a class="btn btn-light-primary btn-icon btn-sm dropdown-toggle toggle-not-arrow" href="#" role="button" id="dropdownMenuLink` + data.estado + (i + 1) + `" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink` + data.estado + (i + 1) + `">
                                        ` + response.data[i].botones + `
                                    </div>
                                </div>

                            </div>`;
                }

                var presupuesto = "";
                
                $("#"+nombre_contenedor+"").append(`
                    <div class="col-xl-6">

                        <div class="card card-custom gutter-b card-stretch">
                            <div class="card-body">
                                <div class="d-flex align-items-center">

                                    <div class="flex-shrink-0 mr-4 symbol symbol-65 symbol-circle">
                            
                                    </div>
                                    
                                    <div class="flex-shrink-0 mr-4 symbol symbol-65 symbol-circle">
                                        ` + imagen + `
                                    </div>
                                    
                                    <div class="d-flex flex-column mr-auto">
                                    
                                        <a href="#" class="card-title text-hover-primary font-weight-bolder font-size-h5 text-dark mb-1">` + response.data[i].nombre + `</a>
                                        <span class="text-muted font-weight-bold">` + response.data[i].subtitulo + `</span>
                                    
                                    </div>

                                    ` + botones + ` 
                                    
                                </div>
                                <div class="d-flex flex-wrap mt-14">
                                    <div class="mr-12 d-flex flex-column mb-7">
                                        <span class="d-block font-weight-bold mb-4">Fecha de inicio</span>
                                        <span class="btn btn-light-primary btn-sm font-weight-bold btn-upper btn-text">` + response.data[i].date_inicio + `</span>
                                    </div>
                                    <div class="mr-12 d-flex flex-column mb-7">
                                        <span class="d-block font-weight-bold mb-4">Fecha de vencimiento</span>
                                        <span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text">` + response.data[i].date_finalizacion + `</span>
                                    </div>
                                    
                                    <div class="flex-row-fluid mb-7">
                                        <span class="d-block font-weight-bold mb-4">Progreso</span>
                                        <div class="d-flex align-items-center pt-2">
                                            <div class="progress progress-xs mt-2 mb-2 w-100">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: ` + response.data[i].porcentaje + `%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="ml-3 font-weight-bolder">` + response.data[i].porcentaje + `%</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-7 mt-3">` + response.data[i].descripcion + `</p>
                                <!--end::Text-->
                                <!--begin::Blog-->
                                <div class="d-flex flex-wrap">
                                    <!--begin: Item-->
                                    <div class="mr-12 d-flex flex-column mb-7">
                                        <span class="font-weight-bolder mb-4">Metodología</span>
                                        <span class="font-weight-bolder font-size-h5 pt-1">
                                        <span class="font-weight-bold text-dark-50"></span>` + nombreMetodologia + `</span>
                                    </div>
                                    <div class="d-flex flex-column flex-lg-fill float-left mb-10">
                                        <a class=" mb-4" href="` + base_url + `proyectos/miembros/` + response.data[i].url + `/` + urlMetodologia + `">
                                            <span class="font-weight-bolder">Miembros</span>
                                        </a>
                                        <div class="symbol-group symbol-hover">
                                            ` + miembro + `
                                        </div>

                                    </div>
                                    
                                    <div class="d-flex flex-column flex-lg-fill float-left mb-7">
                                        <span class="font-weight-bolder mb-4">Profesores</span>
                                        <div class="symbol-group symbol-hover">
                                            ` + miembro_profesor + `
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ver-mas hide">
                                    <p>
                                        <strong>Archivos</strong>
                                    </p>
                                    <div class="row">								
                                        ` + archivos + ` 
                                    </div>
                                </div>
                            </div>
                            

                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                <a href="` + base_url + `proyectos/fases/` + response.data[i].url + `/` + urlMetodologia + `" class="btn btn-success btn-sm text-uppercase font-weight-bolder mt-5 mt-sm-0 mr-2 ">Fases</a>
                                <a href="` + base_url + `graficas/index?idproyecto=` + response.data[i].idproyecto + `" class="btn btn-primary btn-sm text-uppercase font-weight-bolder mt-5 mt-sm-0 mr-auto mr-sm-0 me-2 ">Gráficas</a>
                                </div> 

                                <div>
                                <button type="button" class="btn btn-success btn-sm text-uppercase font-weight-bolder mt-5 mt-sm-0 mr-auto mr-sm-0 ml-sm-auto mr-2 ver-presupuesto" attr-id-presupuesto="` + response.data[i].idproyecto + `">Presupuesto</button>
                                <button type="button" class="btn btn-primary btn-sm text-uppercase font-weight-bolder mt-5 mt-sm-0 mr-auto mr-sm-0 ml-sm-auto mr-2 ver-mas-boton">Detalles</button>
                                </div> 
                            </div>
                        </div>
                    </div>
                `);


                $("#dropdownMenuLink" + data.estado + (i + 1)).dropdown();
            }
        });
    }

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form) {
            if ($(".dz-image").length == 0) {

                var formData = new FormData(document.getElementById("form_ingresar"));

                var nombre = document.getElementById("nombre").value.trim();
                var fecha_inicio = document.getElementById("fecha_inicio").value.trim();

                var contadorInterno = 0;
                $('#metodologia option:checked').each(function() {
                    formData.append("metodologia[" + contadorInterno + "][idmetodologia]", $(this).val());
                    contadorInterno++;
                });

                contadorInterno = 0;
                $('#profesor option:checked').each(function() {
                    formData.append("profesor[" + contadorInterno + "][idusuario]", $(this).val());
                    contadorInterno++;
                });

                var grupos = [];
                contadorInterno = 0;
                $('#grupos option:checked').each(function() {
                    formData.append("grupos[" + contadorInterno + "][idgrupo]", $(this).val());
                    contadorInterno++;
                });

                var usuarios = [];
                contadorInterno = 0;
                $('#usuarios option:checked').each(function() {
                    formData.append("usuarios[" + contadorInterno + "][idusuario]", $(this).val());
                    contadorInterno++;
                });

                formData.append("nombre", nombre);
                formData.append("fecha_inicio", fecha_inicio);

                $.ajax({
                    url: base_url + 'proyectos/crear',
                    type: "post",
                    dataType: "json",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function(response) {
                    heading = "Alerta!";
                    text = response.msg;
                    icon = "error";
                    if (response.res != $EXIT_ERROR) {
                        inicio = 0;
                        cargar_proyecto(inicio);
                        heading = "Ingresado!";
                        text = response.msg;
                        icon = "info";
                        $('#proyectosModal').modal('hide');
                        $("textarea, input[type=text]").val("");
                        $('#metodologia, #profesor, #grupos, #usuarios').val("").trigger('change.select2');
                        $("#grupos").change();
                    }

                    return mensaje_toast(heading, text, icon);
                });
            }
        }
    });
    //Validate para modificar el usuario con sus datos ya cargados.
    $("#form_update").validate({
        submitHandler: function(form) {

            var formData = new FormData(document.getElementById("form_update"));

            var id = document.getElementById("proyectos_id_update").value.trim();
            var nombre = document.getElementById("nombre_update").value.trim();
            var subtitulo = document.getElementById("subtitulo_update").value.trim();
            var descripcion = document.getElementById("descripcion_update").value.trim();
            var fecha_inicio = document.getElementById("fecha_inicio_update").value.trim();
            var proyecto_base = $(`#form_update input[name=proyecto_base]:checked`).val();
            var contadorInterno = 0;
            $('#metodologia_update option:checked').each(function() {
                formData.append("metodologia[" + contadorInterno + "][idmetodologia]", $(this).val());
                contadorInterno++;
            });

            contadorInterno = 0;
            $('#profesor_update option:checked').each(function() {
                formData.append("profesor[" + contadorInterno + "][idusuario]", $(this).val());
                contadorInterno++;
            });

            var grupos = [];
            contadorInterno = 0;
            $('#grupos_update option:checked').each(function() {
                formData.append("grupos[" + contadorInterno + "][idgrupo]", $(this).val());
                contadorInterno++;
            });

            var usuarios = [];
            contadorInterno = 0;
            $('#usuarios_update option:checked').each(function() {
                formData.append("usuarios[" + contadorInterno + "][idusuario]", $(this).val());
                contadorInterno++;
            });

            formData.append("id", id);
            formData.append("nombre", nombre);
            formData.append("subtitulo", subtitulo);
            formData.append("descripcion", descripcion);
            formData.append("fecha_inicio", fecha_inicio);
            formData.append("proyecto_base", proyecto_base);
            
            $.ajax({
                url: base_url + 'proyectos/actualizar',
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";

                if (response.res == $EXIT_SUCCESS) {

                    var estado = ($("#proyectos-inactivos-1").hasClass("active")) ? false : true;

                    cargar_proyecto(inicio, estado);
                    
                    heading = "Modificado!";
                    icon = "success";
                    $('#proyectosModalupdate').modal('hide');
                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });

    $("#grupos").on('change', function() {
        var contador = 0;
        var grupos = [];
        var data = {};
        $('#grupos option:checked').each(function() {
            grupos[contador] = { "idgrupo": $(this).val() };
            contador++;
        });

        data.grupos = grupos;

        ajax('grupos/consultar_estudiantes_sin_grupo', data, function(response) {
            if (response.res != $EXIT_ERROR) {

                $('#usuarios option').remove();

                for (var i = 0; i < response.data.length; i++) {

                    var data = {
                        id: response.data[i].idusuario,
                        text: response.data[i].correo + " (" + response.data[i].nombre + ")"
                    };

                    var newOption = new Option(data.text, data.id, false, false);
                    $('#usuarios').append(newOption).trigger('change');
                }

            } else {
                return mensaje_toast(heading, text, icon);
            }
        });
    });

    var myDropzoneArchivo = new Dropzone("#archivos", {
        autoProcessQueue: false,
        url: base_url + 'proyectos/crear',
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

                    var nombre = document.getElementById("nombre").value.trim();
                    var fecha_inicio = document.getElementById("fecha_inicio").value.trim();
                    var descripcion = document.getElementById("descripcion").value.trim();
                    var subtitulo = document.getElementById("subtitulo").value.trim();

                    var contadorInterno = 0;
                    $('#metodologia option:checked').each(function() {
                        formData.append("metodologia[" + contadorInterno + "][idmetodologia]", $(this).val());
                        contadorInterno++;
                    });

                    contadorInterno = 0;
                    $('#profesor option:checked').each(function() {
                        formData.append("profesor[" + contadorInterno + "][idusuario]", $(this).val());
                        contadorInterno++;
                    });

                    var grupos = [];
                    contadorInterno = 0;
                    $('#grupos option:checked').each(function() {
                        formData.append("grupos[" + contadorInterno + "][idgrupo]", $(this).val());
                        contadorInterno++;
                    });

                    var usuarios = [];
                    contadorInterno = 0;
                    $('#usuarios option:checked').each(function() {
                        formData.append("usuarios[" + contadorInterno + "][idusuario]", $(this).val());
                        contadorInterno++;
                    });

                    formData.append("nombre", nombre);
                    formData.append("fecha_inicio", fecha_inicio);
                    formData.append("descripcion", descripcion);
                    formData.append("subtitulo", subtitulo);
                    formData.append("imagen", $('#imagen')[0].files[0]);

                    $("button, input[type=button], input[type=submit]").attr("disabled", false);
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
                    $('#proyectosModal').modal('hide');
                    $("textarea, input[type=text]").val("");
                    $('#metodologia, #profesor, #grupos, #usuarios').val("").trigger('change.select2');
                    $("#grupos").change();

                    heading = "Ingresado!";
                    icon = "success";
                    text = response.msg;
                    myDropzoneArchivo.removeAllFiles();
                    inicio = 0;

                    var estado = ($("#proyectos-inactivos-1").hasClass("active")) ? false : true;
                    cargar_proyecto(inicio, estado);

                    mensaje_toast(heading, text, icon);
                    $("button, input[type=button], input[type=submit]").attr("disabled", true);
                }

            }

            contadorAlerta++;
        },
        removedfile: function(file, data) {
            file.previewElement.remove();
        },
    });

    var myDropzoneSlider = new Dropzone("#archivos_update", {
        url: base_url + 'proyectos/upload_file',
        addRemoveLinks: true,
        dictRemoveFile: 'Remover archivo',
        init: function() {

            this.on("sending", function(file, xhr, formData) {
                formData.append("idproyecto", $("#proyectos_id_update").val());
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

            var server_file = $(file.previewTemplate).children('.server_file').text();
            // Do a post request and pass this path and use server-side language to delete the file
            //$.post("delete.php", { file_to_be_deleted: server_file } ); 
            var data = {};

            data.nombre = server_file;
            data.idproyecto = $("#proyectos_id_update").val();

            ajax('proyectos/removed_file', data, function(response) {
                file.previewElement.remove();
            });

        },
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e) {
        e.preventDefault();
        var data = {};
        data.id = $(this).attr("id");
        ajax('proyectos/listar_id', data, function(response) {
            $('#proyectosModalupdate').modal('show');

            $("#url_update").val(response.url);
            $("#nombre_update").val(response.nombre);
            $("#descripcion_update").val(response.descripcion);
            $("#subtitulo_update").val(response.subtitulo);
            $("input[type=file]").val("");
            // $(classes).prop('disabled', true).val('');
            $(`#form_update input[name=proyecto_base][value=${response.proyecto_base}]`).prop('checked', true);

            if (response.ruta_imagen == '' || response.ruta_imagen == null) {
                $("#foto_update").css("background-image", "url(" + base_url + "assets/public/img/imagen3.jpg" + ")");
                $(".image-input-wrapper").css("background-image", "none");
            } else {
                $("#foto_update").css("background-image", "url(" + base_url + "uploads/" + response.ruta_imagen + ")");
                $(".image-input-wrapper").css("background-image", "none");
            }

            $('#fecha_inicio_update').data('daterangepicker').setStartDate(response.fecha_inicio);
            $('#fecha_inicio_update').data('daterangepicker').setEndDate(response.fecha_finalizacion);

            var array_metodologia = [];
            var contador = 0;
            for (var i = 0; i < response.metodologia.length; i++) {
                array_metodologia[contador] = response.metodologia[i]["idmetodologia"];
                contador++;
            }

            var array_profesor = [];
            var contador = 0;
            for (var i = 0; i < response.profesor.length; i++) {
                array_profesor[contador] = response.profesor[i]["idusuario"];
                contador++;
            }

            var array_usuario = [];
            var contador = 0;
            for (var i = 0; i < response.usuario.length; i++) {
                array_usuario[contador] = response.usuario[i]["idusuario"];
                contador++;
            }

            $('#usuarios_update').val(array_usuario).trigger('change');
            $('#profesor_update').val(array_profesor).trigger('change');
            $('#metodologia_update').val(array_metodologia).trigger('change');
            $("#proyectos_id_update").val(response.id);

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
                        name: response.archivos[i].client_name
                    };

                    myDropzoneSlider.emit("addedfile", mockFile);
                    myDropzoneSlider.emit("thumbnail", mockFile, url);
                    myDropzoneSlider.emit("complete", mockFile);
                    myDropzoneSlider.files.push(mockFile);
                    $(myDropzoneSlider.files[i].previewTemplate).append('<span class="server_file hide">' + response.archivos[i].ruta + '</span>');

                }
            }
        });
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.clonar', function(e) {
        e.preventDefault();
        var data = {};
        data.id = $(this).attr("id");
        ajax('proyectos/listar_id', data, function(response) {

            $("#form_ingresar textarea, #form_ingresar input[type=text]").val("");
            $('#form_ingresar #metodologia, #form_ingresar #profesor, #form_ingresar #grupos, #form_ingresar #usuarios').val("").trigger('change.select2');
            $("#form_ingresar #grupos").change();

            $('#proyectosModal').modal('show');

            $("#url").val(response.url);
            $("#nombre").val(response.nombre);
            $("#descripcion").val(response.descripcion);
            $("#subtitulo").val(response.subtitulo);
            $("input[type=file]").val("");
            // $(classes).prop('disabled', true).val('');
            $(`#form input[name=proyecto_base][value=${response.proyecto_base}]`).prop('checked', true);

            $('#fecha_inicio').data('daterangepicker').setStartDate(response.fecha_inicio);
            $('#fecha_inicio').data('daterangepicker').setEndDate(response.fecha_finalizacion);

            var array_metodologia = [];
            var contador = 0;
            for (var i = 0; i < response.metodologia.length; i++) {
                array_metodologia[contador] = response.metodologia[i]["idmetodologia"];
                contador++;
            }

            var array_profesor = [];
            var contador = 0;
            for (var i = 0; i < response.profesor.length; i++) {
                array_profesor[contador] = response.profesor[i]["idusuario"];
                contador++;
            }

            $('#profesor').val(array_profesor).trigger('change');
            $('#metodologia').val(array_metodologia).trigger('change');

            $("#archivos .dz-preview").remove();
            $("#archivos").removeClass("dropzone dz-clickable dz-started dz-max-files-reached");
            $("#archivos").addClass("dropzone dz-clickable dz-max-files-reached");

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
                        name: response.archivos[i].client_name
                    };

                    myDropzoneSlider.emit("addedfile", mockFile);
                    myDropzoneSlider.emit("thumbnail", mockFile, url);
                    myDropzoneSlider.emit("complete", mockFile);
                    myDropzoneSlider.files.push(mockFile);
                    $(myDropzoneSlider.files[i].previewTemplate).append('<span class="server_file hide">' + response.archivos[i].ruta + '</span>');

                }
            }
        });
    });

    //Mostrar la ventana modal y cargar renombrar el texto
    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var data = {};
        data.id = $(this).attr("id");
        ajax('proyectos/listar_id', data, function(response) {
            $('#proyectoModaleliminar').modal('show');
            $("#proyectoModaleliminar > div > div > div > h4").html("Esta seguro de eliminar el proyecto '" + response.nombre + "'");
            $(".eliminar").val("Eliminar");
            $(".eliminar").attr("id", response.id);
        });
    });

    //Enviar los datos al controlador para eliminarlo
    $(document).on('click', '.eliminar', function(e) {
        var data = {};
        data.id = $(this).attr("id");

        ajax('proyectos/eliminar', data, function(response) {
            heading = "Alerta!";
            text = response.msg;
            icon = "error";

            if (response.res != $EXIT_ERROR) {
                heading = "Eliminado!";
                text = response.msg;
                icon = "success";
            }

            inicio = 0;
            var estado = ($("#proyectos-inactivos-1").hasClass("active")) ? false : true;
            cargar_proyecto(inicio, estado);
            $('#proyectoModaleliminar').modal('hide');
            return mensaje_toast(heading, text, icon);
        });
    });

    var controladorTiempo;

    $("#subheader_search_form").on("keyup", function() {
        clearTimeout(controladorTiempo);
        controladorTiempo = setTimeout(doneTyping, 500);
    });

    $("#subheader_search_form").on("keydown", function() {
        clearTimeout(controladorTiempo);
    });

    function doneTyping() {
        inicio = 0;

        var clase_activa = $("#proyectos-activos").hasClass("active");  
        
        console.log(clase_activa);

        if(clase_activa){
            cargar_proyecto(inicio);
        }else{
            cargar_proyecto(inicio, false);
        }

        
    }

    //PAGINADOR
    $(".siguiente").click(function(e) {
        e.preventDefault();

        console.log();

        if (!$(this).hasClass("disabled")) {

            inicio = inicio + length;
            if($(this).parent().parent().attr("id") == "paginador-elementos-activos"){
                cargar_proyecto(inicio);
            }else{
                cargar_proyecto(inicio, false);
            }
            
        }
    });

    $(".anterior").click(function(e) {
        e.preventDefault();
        if (!$(this).hasClass("disabled")) {
            inicio = inicio - length;
            if($(this).parent().parent().attr("id") == "paginador-elementos-activos"){
                cargar_proyecto(inicio);
            }else{
                cargar_proyecto(inicio, false);
            }
        }
    });

    $(".primero").click(function(e) {

        e.preventDefault();

        if (!$(this).hasClass("disabled")) {
            inicio = 0;
            if($(this).parent().parent().attr("id") == "paginador-elementos-activos"){
                cargar_proyecto(inicio);
            }else{
                cargar_proyecto(inicio, false);
            }
        }
    });

    $(".ultimo").click(function(e) {
        e.preventDefault();

        if (!$(this).hasClass("disabled")) {
            inicio = fin;
            if($(this).parent().parent().attr("id") == "paginador-elementos-activos"){
                cargar_proyecto(inicio);
            }else{
                cargar_proyecto(inicio, false);
            }
        }
    });

    $("#length-filter").on("change", function() {    
        length = parseInt($(this).val());
        inicio = 0;
        cargar_proyecto(inicio);
    });

    $("#length-filter-inactivo").on("change", function() {    
        length = parseInt($(this).val());
        inicio = 0;
        cargar_proyecto(inicio, false);
    });

    $("#proyectos-inactivos-1").on("click", function() {
        inicio = 0;
        fin = 10;
        length = 10;
        cargar_proyecto(inicio, false);
    });

    $("#proyectos-activos").on("click", function() {
        inicio = 0;
        fin = 10;
        length = 10;
        cargar_proyecto(inicio, true);
    });

    function capitalizarPrimeraLetra(str) {
        str = str.toLowerCase();
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    

});