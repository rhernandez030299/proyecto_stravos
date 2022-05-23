$(document).ready(function() {

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
    });

    $("#form_login").validate();

    $(document.body).on("click", "#btnLogin", function() {
        if (!$("#form_login").valid()) return false;

        var data = {};

        data.usuario = document.getElementById("txt_usuario").value.trim();
        data.password = document.getElementById("txt_pass").value.trim();

        ajax('usuarios/ingresar', data, function(response) {
            if (!response.res) {
                Swal.fire({
                    title: '¡Alerta!',
                    icon: 'error',
                    text: response.msg
                });
                return;
            }
            window.location.href = response.url;
        });

    });

    $(document).on('click', '#login_signup_cancel', function(e) {
        $(".in-login-signin").removeClass('hide');
        $(".in-login-signup").addClass('hide');
    });

    $(document).on('click', '#login_signup', function(e) {
        $(".in-login-signin").addClass('hide');
        $(".in-login-signup").removeClass('hide');
    });


    $("#form_ingresar").validate({

        submitHandler: function(form) {

            var data = {};

            data.nombre = document.getElementById("nombre").value.trim();
            data.apellido = document.getElementById("apellido").value.trim();
            data.user = document.getElementById("user").value.trim();
            data.email = document.getElementById("email").value.trim();
            data.password = document.getElementById("password").value.trim();


            ajax('Usuarios/crearestudiante', data, function(response) {
                heading = "Alerta!";
                text = response.msg;
                icon = "error";
                if (response.res != $EXIT_ERROR) {
                    heading = "Usuario creado exitosamente.";
                    text = response.msg;
                    icon = "info";
                    $("input[type=text], input[type=email], input[type=password]").val("");
                }
                $("#email").focus();

                return mensaje_toast(heading, text, icon);
            });
        }
    });

});