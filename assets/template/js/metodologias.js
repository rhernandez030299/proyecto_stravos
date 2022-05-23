Dropzone.autoDiscover = false;
$(document).ready(function() {
    //Declaración de variables
    var heading = "";
    var icon = "";
    var text = "";
    var estado = "";
    var data = {};

    var pathname = window.location.pathname;
    $(".metodologias-menu").addClass("menu-item-active");

    $(document).on('click', '#add_button', function(e) {
        e.preventDefault();
        $('#metodologiasModal').modal('show');
    });

    //Creacion de la tabla metodologia
    var dataTable = $('#metodologia_table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: base_url + "metodologias/listar_metodologias",
            type: "POST"
        },
        "columnDefs": [{
            "targets": [3],
            "orderable": false,
        }, ],
        "rowCallback": function(row, data) {
            $(row).addClass('xs-block');
            $('td:nth-child(1)', row).attr("data-title", "Nombre");
            $('td:nth-child(2)', row).attr("data-title", "Url");
            $('td:nth-child(3)', row).attr("data-title", "Acción");
        }
    });

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form) {
            if ($(".dz-image").length == 0) {
                var data = {};

                data.nombre = document.getElementById("nombre").value.trim();

                ajax('metodologias/crear', data, function(response) {
                    heading = "Alerta!";
                    text = response.msg;
                    icon = "error";
                    if (response.res != $EXIT_ERROR) {
                        dataTable.ajax.reload();
                        heading = "Ingresado!";
                        text = response.msg;
                        icon = "info";
                        $("input[type=text]").val("");
                        $('#metodologiasModal').modal('hide');
                    }

                    return mensaje_toast(heading, text, icon);
                });
            }
        }
    });

    var myDropzoneArchivo = new Dropzone("#archivos", {
        autoProcessQueue: false,
        url: base_url + 'metodologias/crear',
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
                    formData.append("nombre", nombre);

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
                    $('#metodologiasModal').modal('hide');
                    $("input[type=text]").val("");

                    heading = "Ingresado!";
                    icon = "success";
                    text = response.msg;
                    myDropzoneArchivo.removeAllFiles();
                    dataTable.ajax.reload();
                    mensaje_toast(heading, text, icon);

                }

            }

            contadorAlerta++;
        },
        removedfile: function(file, data) {
            file.previewElement.remove();
        },
    });

    //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e) {
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('metodologias/listar_id', data, function(response) {
            $('#metodologiasModalupdate').modal('show');
            $("#nombre_update").val(response.nombre);
            $("#metodologias_id_update").val(response.id);

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

    //Validate para modificar la metodologia con sus datos ya cargados.
    $("#form_update").validate({
        submitHandler: function(form) {
            var data = {};

            data.id = document.getElementById("metodologias_id_update").value.trim();
            data.nombre = document.getElementById("nombre_update").value.trim();

            ajax('metodologias/actualizar', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";

                if (response.res == $EXIT_SUCCESS) {
                    heading = "Modificado!";
                    icon = "success";
                    dataTable.ajax.reload();
                    $('#metodologiasModalupdate').modal('hide');

                }

                return mensaje_toast(heading, text, icon);
            });
        }
    });

    var myDropzoneSlider = new Dropzone("#archivos_update", {
        url: base_url + 'metodologias/upload_file',
        addRemoveLinks: true,
        dictRemoveFile: 'Remover archivo',
        init: function() {

            this.on("sending", function(file, xhr, formData) {
                formData.append("idmetodologia", $("#metodologias_id_update").val());
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
            data.idmetodologia = $("#metodologias_id_update").val();

            ajax('metodologias/removed_file', data, function(response) {
                file.previewElement.remove();
            });

        },
    });


});