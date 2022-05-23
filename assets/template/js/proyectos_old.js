Dropzone.autoDiscover = false;
$(document).ready(function(){
    //Declaración de variables
    var heading="";
    var icon="";
    var text="";
    var estado = "";
    var data = {};

    var pathname = window.location.pathname;
    $(".proyectos-menu").addClass("menu-item-active");

    var FotoEdit = { init: function() { new KTImageInput("foto") } };
    var FotoEditUpdate = { init: function() { new KTImageInput("foto_update") } };
    jQuery(document).ready(function() {
        FotoEdit.init();
        FotoEditUpdate.init();
    });

    $(document).on('click', '#add_button', function(e){
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

    //Creacion de la tabla usuario activos,
    var dataTable = $('#proyectos_table').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: base_url + "proyectos/listar_proyectos",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets": [5],
                "orderable":false,
            },
        ],    
        "rowCallback": function( row, data ) {
            $(row).addClass('xs-block');
            $('td:nth-child(1)', row).attr("data-title","Url");
            $('td:nth-child(2)', row).attr("data-title","Nombre");
            $('td:nth-child(3)', row).attr("data-title","% Cumplimiento");
            $('td:nth-child(4)', row).attr("data-title","Fecha inicio");
            $('td:nth-child(5)', row).attr("data-title","Fecha Finalización");            
            $('td:nth-child(5)', row).attr("data-title","Acción");
        }
    });

    //Validate para validar los datos del formulario ingresar  
    $("#form_ingresar").validate({

        submitHandler: function(form){
            if($(".dz-image").length == 0){

                var formData = new FormData(document.getElementById("form_ingresar"));

                var nombre = document.getElementById("nombre").value.trim();
                var fecha_inicio = document.getElementById("fecha_inicio").value.trim();

                var contadorInterno = 0;
                $('#metodologia option:checked').each(function(){
                    formData.append("metodologia["+contadorInterno+"][idmetodologia]", $(this).val());
                    contadorInterno++;
                });

                contadorInterno = 0;
                $('#profesor option:checked').each(function(){
                    formData.append("profesor["+contadorInterno+"][idusuario]", $(this).val());
                    contadorInterno++;
                });

                var grupos = [];
                contadorInterno = 0;
                $('#grupos option:checked').each(function(){
                    formData.append("grupos["+contadorInterno+"][idgrupo]", $(this).val()); 
                    contadorInterno++;
                });

                var usuarios = [];
                contadorInterno = 0;
                $('#usuarios option:checked').each(function(){
                    formData.append("usuarios["+contadorInterno+"][idusuario]", $(this).val());
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
                    if(response.res != $EXIT_ERROR){
                        dataTable.ajax.reload();
                        heading = "Ingresado!";
                        text = response.msg;
                        icon = "info";
                        $('#proyectosModal').modal('hide');
                        $("textarea, input[type=text]").val("");
                        $('#metodologia, #profesor, #grupos, #usuarios').val("").trigger('change.select2');
                        $("#grupos").change();
                    }
                    
                    return mensaje_toast(heading,text,icon);
                });
            }
        }
    });
    //Validate para modificar el usuario con sus datos ya cargados.
    $("#form_update").validate({        
        submitHandler: function(form){

            var formData = new FormData(document.getElementById("form_update"));

            var id = document.getElementById("proyectos_id_update").value.trim();
            var nombre = document.getElementById("nombre_update").value.trim(); 
            var subtitulo = document.getElementById("subtitulo_update").value.trim();
            var descripcion = document.getElementById("descripcion_update").value.trim(); 
            var fecha_inicio = document.getElementById("fecha_inicio_update").value.trim();

            var contadorInterno = 0;
            $('#metodologia_update option:checked').each(function(){
                formData.append("metodologia["+contadorInterno+"][idmetodologia]", $(this).val());
                contadorInterno++;
            });

            contadorInterno = 0;
            $('#profesor_update option:checked').each(function(){
                formData.append("profesor["+contadorInterno+"][idusuario]", $(this).val());
                contadorInterno++;
            });

            var grupos = [];
            contadorInterno = 0;
            $('#grupos_update option:checked').each(function(){
                formData.append("grupos["+contadorInterno+"][idgrupo]", $(this).val()); 
                contadorInterno++;
            });

            var usuarios = [];
            contadorInterno = 0;
            $('#usuarios_update option:checked').each(function(){
                formData.append("usuarios["+contadorInterno+"][idusuario]", $(this).val());
                contadorInterno++;
            });

            formData.append("id", id);
            formData.append("nombre", nombre);
            formData.append("subtitulo", subtitulo);
            formData.append("descripcion", descripcion);
            formData.append("fecha_inicio", fecha_inicio);

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
       
                if(response.res == $EXIT_SUCCESS){
                    dataTable.ajax.reload();
                    heading = "Modificado!";
                    icon = "success";
                    dataTable.ajax.reload();
                    $('#proyectosModalupdate').modal('hide');

                }

                return mensaje_toast(heading,text,icon);
            });
        }
    });

    $("#grupos").on('change',function(){
        var contador = 0;
        var grupos = [];
        var data = {};
        $('#grupos option:checked').each(function(){
            grupos[contador] = {"idgrupo":$(this).val()}; 
            contador++;
        });               

        data.grupos = grupos;

        ajax('grupos/consultar_estudiantes_sin_grupo',data,function(response){ 
            if(response.res != $EXIT_ERROR){         

                $('#usuarios option').remove();

                for (var i = 0; i < response.data.length; i++) {
                    
                    var data = {
                        id: response.data[i].idusuario,
                        text: response.data[i].correo + " (" + response.data[i].nombre + ")"
                    };

                    var newOption = new Option(data.text, data.id, false, false);
                    $('#usuarios').append(newOption).trigger('change');
                }

            }else{
                return mensaje_toast(heading,text,icon);
            }
        });
    });

    var myDropzoneArchivo = new Dropzone("#archivos", {
        autoProcessQueue: false,
        url: base_url + 'proyectos/crear',
        addRemoveLinks: true,
        dictRemoveFile: 'Remover archivo',
        dictMaxFilesExceeded: 'No se puede ingresar más de tres fotos',
        dictFileTooBig : 'El archivo es demasiado grande ({{filesize}}MiB). Tamaño máximo de archivo: {{maxFilesize}}MiB.',
        uploadMultiple: true,
        parallelUploads: 30,       
        init: function () {
    
            var myDropzoneArchivo = this;
            var contador = 0;
            
            // Update selector to match your button
            $("#action").click(function (e) { 
                contador = 0;
                if($("#form_ingresar").valid()) {
                    contadorAlerta = 1;
                    myDropzoneArchivo.processQueue();
                }
            });

            this.on('sending', function(file, xhr, formData) {
                
                var data = $('#frmTarget').serializeArray();    
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });
                
                if(contador == 0 ){
                    
                    var nombre = document.getElementById("nombre").value.trim();
                    var fecha_inicio = document.getElementById("fecha_inicio").value.trim();
                    var descripcion = document.getElementById("descripcion").value.trim();
                    var subtitulo = document.getElementById("subtitulo").value.trim();

                    var contadorInterno = 0;
                    $('#metodologia option:checked').each(function(){
                        formData.append("metodologia["+contadorInterno+"][idmetodologia]", $(this).val());
                        contadorInterno++;
                    });

                    contadorInterno = 0;
                    $('#profesor option:checked').each(function(){
                        formData.append("profesor["+contadorInterno+"][idusuario]", $(this).val());
                        contadorInterno++;
                    });

                    var grupos = [];
                    contadorInterno = 0;
                    $('#grupos option:checked').each(function(){
                        formData.append("grupos["+contadorInterno+"][idgrupo]", $(this).val()); 
                        contadorInterno++;
                    });

                    var usuarios = [];
                    contadorInterno = 0;
                    $('#usuarios option:checked').each(function(){
                        formData.append("usuarios["+contadorInterno+"][idusuario]", $(this).val());
                        contadorInterno++;
                    });

                    formData.append("nombre", nombre);
                    formData.append("fecha_inicio", fecha_inicio);
                    formData.append("descripcion", descripcion);
                    formData.append("subtitulo", subtitulo);
                    formData.append("imagen", $('#imagen')[0].files[0]);
                    
                    $("button, input[type=button], input[type=submit]").attr("disabled",false);
                }
                
                contador++;
            });
        },
        success: function(file, response) {
            var response = JSON.parse(response);
            if(response.res == 0 ){
                
                this.removeFile(this.files[0]);
                file.status = undefined;
                myDropzoneArchivo.addFile(file);

                if(contadorAlerta  == 1){
                    heading = "Alerta!";
                    text = response.msg;
                    icon = "error";
                    $.toast({
                        heading: heading,
                        text: text,
                        icon: icon,
                        position:'top-right',
                        showHideTransition: 'slide',
                        loader: true,        // Change it to false to disable loader
                        loaderBg: '#ffffff',  // To change the background
                    });

                }

            }else{

                if(contadorAlerta  == 1){
                    $('#proyectosModal').modal('hide');
                    $("textarea, input[type=text]").val("");
                    $('#metodologia, #profesor, #grupos, #usuarios').val("").trigger('change.select2');
                    $("#grupos").change();
                  
                    heading = "Ingresado!";
                    icon = "success";
                    text = response.msg;
                    myDropzoneArchivo.removeAllFiles();
                    dataTable.ajax.reload();
                    mensaje_toast(heading,text,icon);
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
                if($("#form_update").valid()){
                    $(".dz-preview").remove();
                    $("#archivos_update").removeClass("dropzone dz-clickable dz-started dz-max-files-reached");
                    $("#archivos_update").addClass("dropzone dz-clickable dz-max-files-reached");
                }
            });
        },

        success: function(file, response) {
            var response = JSON.parse(response);
            $(file.previewTemplate).append('<span class="server_file hide">'+response.name+'</span>');
        },

        removedfile: function(file, data) {
     
            var server_file = $(file.previewTemplate).children('.server_file').text();
            // Do a post request and pass this path and use server-side language to delete the file
            //$.post("delete.php", { file_to_be_deleted: server_file } ); 
            var data = {};
            
            data.nombre = server_file;
            data.idproyecto =  $("#proyectos_id_update").val();

            ajax('proyectos/removed_file',data,function(response){
                console.log(response);
                file.previewElement.remove();
            });
            
       },
    });

      //Mostrar la ventana modal y cargar los datos en la ventana modal del validate
    $(document).on('click', '.update', function(e){
        e.preventDefault();
        data.id = $(this).attr("id");
        ajax('proyectos/listar_id',data,function(response){
            $('#proyectosModalupdate').modal('show');

            $("#url_update").val(response.url);
            $("#nombre_update").val(response.nombre);
            $("#descripcion_update").val(response.descripcion);
            $("#subtitulo_update").val(response.subtitulo);
            $("input[type=file]").val("");

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
            
            if(response.archivos != "" && response.archivos != null && response.archivos.length > 0){   
                for (var i = 0; i < response.archivos.length; i++) {

                    var ext = response.archivos[i].ruta.split('.').pop();
                    var url = base_url + 'uploads/' + response.archivos[i].ruta;
                    if (ext == "pdf") {
                        url = base_url +  "assets/public/img/pdf.jpg";
                    } else if (ext.indexOf("doc") != -1) {
                        url = base_url +  "assets/public/img/word.png";
                    } else if (ext.indexOf("xls") != -1) {
                        url = base_url +  "assets/public/img/excel.png";
                    }

                    var mockFile = {
                        size: response.archivos[i].peso,
                        name: response.archivos[i].client_name
                    };

                    myDropzoneSlider.emit("addedfile", mockFile);
                    myDropzoneSlider.emit("thumbnail", mockFile, url);
                    myDropzoneSlider.emit("complete", mockFile);
                    myDropzoneSlider.files.push(mockFile);
                    $(myDropzoneSlider.files[i].previewTemplate).append('<span class="server_file hide">'+response.archivos[i].ruta+'</span>');

                }
            }
        });
    });
});