/* Default options validate */
$.validator.addClassRules("text", {
    required : true,
    lettersonly : true
});

/* Default options validate */
$.validator.addClassRules("number", {
    required : true,
    number : true,
    min:1
});

/* Default options validate */
$.validator.addClassRules("digits", {
    required : true,
    digits : true,
    min:0
});

$.validator.addClassRules("email", {
    required : true,
    email_safe : true
});

$.validator.addClassRules("cellphone", {
    required : true,
    number : true,
    minlength : 10,
    maxlength : 10,
    cellphone : true
});

$.validator.addClassRules("password", {
    required : true,
    minlength : 6,
    maxlength : 16,
});

$.validator.addClassRules("password_repit", {
    required : true,
    minlength : 6,
    maxlength : 16,
    equalTo:"#change_password"
});

jQuery.validator.addMethod("cellphone", function(value, element) {
    return this.optional(element) || Number(String(value).charAt(0)) == 3;
}, "El número de celular no es válido");

jQuery.validator.addMethod("email_safe", function(value, element) {
	var regcorreo = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;    	
    return this.optional(element) || regcorreo.test(value);
}, "La dirección de correo no es válida.");

jQuery.validator.addMethod("lettersonly", function(value, element) {
  return this.optional(element) || /^[ a-zA-ZáéíóúüñÁÉÍÓÚÜÑ]+$/i.test(value);
}, "Ingrese sólo letras");

jQuery.validator.setDefaults({
	ignore: ".ignore",	
	errorPlacement: function ( error, element ) {
		// Add the `help-block` class to the error element		
        if( element.parent('.form-group').length || element.parent('.input-group').length ){
            error.insertAfter( element.parent() );
        }else if( element.parent('.radio').length ){
            
            error.insertAfter( element.parent().parent() );

        }else{
            error.insertAfter( element );
        }
	},
	success: function( label, element ) {
	    label.parents( ".form-group" ).removeClass('has-error');
	    label.remove(); 
	},
	highlight: function(element, errorClass, validClass){
		$( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
	},
	unhighlight: function(element, errorClass, validClass) {
		$( element ).parents( ".form-group" ).removeClass( "has-error" );
	}
});