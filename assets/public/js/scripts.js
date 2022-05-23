//DESHABILITAR MENU CONTEXTUAL
//document.oncontextmenu = document.body.oncontextmenu = function() {return false;}
$(".select2").select2();
//FUNCION AJAX BASADA EN JQUERY
function ajax(url,data,done,isglobal,timeout){
    if(isglobal === "undefined")isglobal = true;
    if(timeout === "undefined")timeout = 10000;
    $("button, input[type=button], input[type=submit]").attr("disabled",true);

    $.ajax({
        url: base_url+url,
        context : document.body,
        dataType: "json",
        global: isglobal,
        type: "POST",
        data: data,
        timeout: timeout
    }).done(function(data,textStatus,jqXHR){
        
        $("button, input[type=button], input[type=submit]").attr("disabled",false);
        done(data);
        $('.tooltip').hide();
    }).fail(function(jqXHR,status,msg){
    	$("body").removeClass("loading");
    	alert("Error conexion: "+status+" - "+msg);
        $("button, input[type=button], input[type=submit]").attr("disabled",false);
    });
}

//FUNCION SECURE AJAX BASADA EN JQUERY con CSRF
function secure_ajax(url,data,done,isglobal,timeout){
    if(isglobal === "undefined"){ isglobal = true; }
    if(timeout === "undefined"){ timeout = 10000; }
    data[csrf_token_name] = csrf_hash;//hash y name deben ir en el scripts
    
    $.ajax({
        url: base_url+url,
        context : document.body,
        dataType: "json",
        global: isglobal,
        type: "POST",
        data: data,
        timeout: timeout
    }).done(function(data,textStatus,jqXHR){
        
        if( data.csrf_token !== undefined ){
            csrf_hash = data.csrf_token;
            delete data.csrf_token;
        }
        done(data);
    }).fail(function(jqXHR, textStatus, errorThrown){
        $("body").removeClass("loading");
        alert("Error conexion: "+textStatus+" - "+errorThrown);
    });
}

//ACCION A REALIZAR CUANDO SE INICIA UNA PETICION AJAX
$(document).ajaxStart(function(){
	$("body").addClass("loading");
});

//ACCION A REALIZAR CUANDO TERMINAN TODAS LAS PETICIONES AJAX
$(document).ajaxStop(function(){
	$("body").removeClass("loading");
});

//PERMITE SÓLO NÚMEROS
//UTILIZAR EN EVENTO ONKEYPRESS
function onlyNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode==37||charCode==39) {
        return true;
    }else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

//PERMITE SOLO NUMEROS Y COMA
function onlyNumberfloat(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode==46||charCode==37||charCode==39) {
        return true;
    }else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

//PERMITE SÓLO NÚMEROS Y GUIÓN (NITS)
function onlyNumberHyphen(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode==45) {
        return true;
    }else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

//PERMITE LETRAS Y CARACTERES ESPECIALES
//UTILIZAR EN EVENTO ONKEYPRESS
function onlyText(e){
   key = e.keyCode || e.which;
   tecla = String.fromCharCode(key).toLowerCase();
   letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
   especiales = "8-37-39-46";
   tecla_especial = false
   for(var i in especiales){
        if(key == especiales[i]){
            tecla_especial = true;
            break;
        }
    }
    if(letras.indexOf(tecla)==-1 && !tecla_especial){
        return false;
    }
}

//PERMITE LETRAS Y NUMEROS
//UTILIZAR EN EVENTO ONKEYPRESS
function  onlyTextNumber(e){
    if( e.which == 8 || e.which == 46 ) {return true;}
    var regex = new RegExp("^[a-zA-Z0-9]*$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
}

//RETORNA EL TRUE SI EL CORREO ES VÁLIDO, FALSE EN CASO CONTRARIO
function validarCorreo(correo){
    var regcorreo = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!regcorreo.test(correo))return false;
    return true;
}

/************************************
*               MODALES             *
************************************/
var modalwindow = $("#modal-msj");
var modalcontent = document.getElementById("modal-msj-content");
var modaltitle = document.getElementById("modal-msj-title");
var modalactions = document.getElementById("modal-msj-actions");

var ok = document.createElement("button");
ok.innerHTML = "Aceptar";
ok.setAttribute("data-dismiss","modal");
ok.className = "btn btn-default";

var cancel = document.createElement("button");
cancel.innerHTML = "Cancelar";
cancel.setAttribute("data-dismiss","modal");
cancel.className = "btn btn-default";

//MODAL DE MENSAJES TIPO ALERT
function mensaje(msj,callback){
    console.log(modalcontent);
    modalwindow.off("hidden.bs.modal");
    if(msj === "undefined")msj = "";
    if(callback === "undefined")callback = false;

    modaltitle.innerHTML = '<i class="fa fa-commenting-o"></i> Mensaje';
    modalcontent.innerHTML = msj;
    modalactions.innerHTML = "";
    ok.onclick = function(){ return false; };
    modalactions.appendChild(ok);
    modalwindow.modal();
    if(callback){
        modalwindow.on("hidden.bs.modal",function(){
            callback();
        });
    }
}

/*
btnnames = {
    ok : "Aceptar",
    cancel : "Cancelar"
}
*/
//MODAL DE MENSAJES TIPO CONFIRM
function confirmar(msj, okfunction, cancelfunction, btnnames){
    if(msj === "undefined" || msj == false)msj = "";
    if(okfunction === "undefined" || okfunction == false)okfunction = false;
    if(cancelfunction === "undefined" || cancelfunction == false )cancelfunction = false;
    if(btnnames === "undefined" || btnnames == false )btnnames = false;
    modaltitle.innerHTML = '<i class="fa fa-question-circle-o"></i> Mensaje Confirmación';
    modalcontent.innerHTML = msj;
    var btnok = ok.cloneNode(true);
    var btncancel = cancel.cloneNode(true);
    if(okfunction)btnok.onclick = okfunction;
    if(cancelfunction)btncancel.onclick = cancelfunction;
    if(btnnames){
        if(btnnames.ok)btnok.innerHTML = btnnames.ok;
        if(btnnames.cancel)btncancel.innerHTML = btnnames.cancel;    
    }    
    modalactions.innerHTML = "";    
    modalactions.appendChild(btncancel);
    modalactions.appendChild(btnok);
    modalwindow.modal();
}



/*
var params = [];
params.push({
    name    : "btncustom",
    value   : "Mi boton",
    class   : "btn btn-primary",
    action  : function(){
        //mi función a ejecutar con el boton
    }
});
*/
// MODAL CUSTOM
// CREA EL MODAL DE ACUERDO A LOS PARÁMETROS ENVIADOS
function custom(msj , title, buttons){
    if(msj === "undefined")msj = "";
    if(title === "undefined")title = "";
    if(buttons === "undefined")buttons = false;
    modaltitle.innerHTML = title;
    modalcontent.innerHTML = msj;
    modalactions.innerHTML = "";
    if(buttons){
        for(var i = 0; i < buttons.length; i++){
            if(buttons[i]){                
                eval("var "+buttons[i].name+" = document.createElement('button');");
                eval(buttons[i].name+".innerHTML = '"+buttons[i].value+"';");
                eval(buttons[i].name+".className = '"+buttons[i].class+"';");
                if(buttons[i].action){
                    eval(buttons[i].name+".onclick = "+buttons[i].action+";");
                }
                eval(buttons[i].name+".setAttribute('data-dismiss','modal');");
                eval("modalactions.appendChild("+buttons[i].name+");");
            }            
        }
    }else{
        modalactions.appendChild(ok);
    }
    modalwindow.modal();
}



function mensaje_toast(heading,text,icon,callback){    
    $.toast({
        heading: heading,
        text: text,
        icon: icon,
        position:'top-right',
        showHideTransition: 'slide',
        loader: true,        // Change it to false to disable loader
        loaderBg: '#ffffff',  // To change the background
        beforeHide: callback
    });

}

$(document).on('click', '.enlace-notificacion', function(e) {
    
    e.preventDefault();
    var data = {};
    let that = $(this);
    data.id = that.attr('attr-id');
    ajax('historia/cambiar_vista', data, function(response) {
        location.href = that.attr('href');
    });

    // location.reload(true);
});