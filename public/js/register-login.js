$(document).ready(function(){    
    (function($) {
        "use strict";

    
    jQuery.validator.addMethod('answercheck', function (value, element) {
        return this.optional(element) || /^\bcat\b$/.test(value)
    }, "type the correct answer -_-");

    // validate contactForm form
    $(function() {
        
        /*==============================
        =           Login            =
        ==============================*/
         $('#login-form').validate({
            rules: {  
                user_name_login: {
                    required: true,
                    email: true
                },
                password_login: {
                    required: true
                }
            },
            messages: {
                user_name_login: {
                    required: "Por favor, introduzca su dirección de correo electrónico para ingresar."
                },
                password_login: {
                    required: "Por favor, Colocar su Contraseña."
                }
            },
            submitHandler: function(form) {
                var route = $("#route").val();
                
                $(form).ajaxSubmit({
                    type:"POST",
                    data: $(form).serialize(),
                    url: route+"controllers/users.controller.php?function=login",
                    success: function(res) {
                        if (res=="ok-login") {
                            localStorage.setItem("countLoging", 0);
                            window.location.href=route;
                        }else if(res=="error:201"){
                            $.notify({
                                message: "¡El usuario o contraseña son incorrectos, por favor validar sus datos!"
                            },{
                                type: 'danger'
                            });
                            return false;
                        }else if(res=="error:200"){
                            $.notify({
                                message: "¡El nombre de usuario debe contener datos validos!"
                            },{
                                type: 'danger'
                            });
                            return false;
                        }else if(res=="ok-correo"){
                            $.notify({
                                message: "¡Aun no se ha validado su correo, favor de revisar su bandeja y validar su correo!"
                            },{
                                type: 'danger'
                            });
                            return false;
                        }
                    },
                    error: function() {
                        $('#register-form').fadeTo( "slow", 1, function() {
                            $.notify({
                                message: "¡No se pudo Iniciar Sesión, por favor intentalo más tarde!"
                            },{
                                type: 'danger'
                            });
                            return false;
                        })
                    }
                })
            }
        })
        /*=====  End of Login  ======*/
        /*==========================================
        =            Register new Users            =
        ==========================================*/
        
        $('#RegistroUsers').validate({
            rules: {
                name_register: {
                    required: true
                },
                last_name_register: {
                    required: true
                },
                user_name_register: {
                    required: true,
                    minlength: 1,
                    email:true
                },
                password_register: {
                    required: true,
                    minlength: 8
                },
                password_register2: {
                    required: true,
                    minlength: 8
                },
            },
            messages: {
                name_register: {
                    required: "Por favor, introduzca su Nombre Completo para completar el registro"
                },
                last_name_register: {
                    required: "Por favor, introduzca sus Apellidos para completar el registro"
                },
                user_name_register: {
                    required: "Por favor, introduzca una dirección de correo electrónico valida.",
                    minlength: "El usuario debe de tener un minimo de 1 caracteres. ",
                    email: "ingrese una dirección de correo valida"
                },
                password_register: {
                    required: "Por favor, introduzca una contraseña valida  para completar el registro",
                    minlength: "La Contraseña debe de tener un minimo de 8 caracteres. "
                },
                password_register2: {
                    required: "Por favor, verifique la contraseña para completar el registro",
                    minlength: "La Contraseña debe de tener un minimo de 8 caracteres. "
                }
            },
            submitHandler: function(form) {
                var route = $("#route").val();
                var password_register = $("#password_register").val();
                var password_register2 = $("#password_register2").val();
                if (password_register!=password_register2) {
                    $.notify({
                        message: "¡No se puede realizar su registro, por favor verifique las contraseñas no coinciden!"
                    },{
                        type: 'danger'
                    });
                    return false;                    
                }
                $(form).ajaxSubmit({
                    type:"POST",
                    data: $(form).serialize(),
                    url: route+"controllers/users.controller.php?function=register",
                    success: function(res) {
                        if (res=='okRegistro') {
                            $.notify({
                                message: "¡Registro Exitoso, validar correo para continuar!"
                            },{
                                type: 'success'
                            });
                            // window.location.href=route+"my-account";
                        }else if(res=="ya_existe"){
                            $.notify({
                                message: "¡Este usuario ya esta registrado en nuestro ecommerce o nuestro sistema POS!"
                            },{
                                type: 'danger'
                            });
                            return false;
                        }
                    },
                    error: function() {
                            $.notify({
                                message: "¡No se puede realizar su registro, por favor intentalo más tarde!"
                            },{
                                type: 'danger'
                            });
                            return false;     
                       
                    }
                })
            }
        })
        
        /*=====  End of Register new Users  ======*/
        /*=============================================
        =            Recover password user            =
        =============================================*/
        
        $('#recover-form').validate({
            rules: {  
                user_name_recover: {
                    required: true,
                    email: true
                }
            },
            messages: {
                user_name_recover: {
                    required: "Por favor, introduzca su dirección de correo electrónico."
                }
            },
            submitHandler: function(form) {
                var route = $("#route").val();
                $(form).ajaxSubmit({
                    type:"POST",
                    data: $(form).serialize(),
                    url: route+"controllers/users.controller.php?function=recover",
                    success: function(res) {
                        if (res=="ok-recover") {
                            $.notify({
                                message: "¡Recuperación Exitosa, Se envio un correo con su nueva contraseña!"
                            },{
                                type: 'success'
                            });
                        }else{
                            $.notify({
                                message: "¡Su correo electrónico no existe o es incorrecto, por favor valide su correo electrónico!"
                            },{
                                type: 'danger'
                            });
                            return false;
                        }
                    },
                    error: function() {
                        $('#recover-form').fadeTo( "slow", 1, function() {
                            $.notify({
                                message: "¡No se pudo Recuperar su contraseña, por favor intentalo más tarde!"
                            },{
                                type: 'danger'
                            });
                            return false;
                        })
                    }
                })
            }
        })
        
        
        /*=====  End of Recover password user  ======*/
        
    })
        
 })(jQuery)

})

