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
        $('#formLogin').validate({
            rules: {  
                Correo: {
                    required: true,
                    email: true
                },
                Password: {
                    required: true
                }
            },
            messages: {
                Correo: {
                    required: "Por favor, introduzca su dirección de correo electrónico para ingresar.",
                    email: "ingrese una dirección de correo valida"
                },
                Password: {
                    required: "Por favor, Colocar su Contraseña."
                }
            },
            submitHandler: function(form) {        
                var route = $("#route").val();
                $(form).ajaxSubmit({
                    type:"POST",
                    data: $(form).serialize(),
                    url: "ingreso_data",
                    dataType: "json",
                    success: function(res) {
                        if (res.status=='400') {
                            $.notify({
                                message: res.message
                            },{
                                type: 'danger'
                            });
                        }else if (res.status=='200') {
                            $.notify({
                                message: res.message
                            },{
                                type: 'success'
                            });
                            window.location.href = route;
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
        
        $('#formRegister').validate({
            rules: {
                nnombreame_user: {
                    required: true
                },
                apellido: {
                    required: true
                },
                correo: {
                    required: true,
                    email:true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                rol: {
                    required: true,
                },
            },
            messages: {
                nombre: {
                    required: "Por favor, introduzca su Nombre Completo para completar el registro"
                },
                apellido: {
                    required: "Por favor, introduzca sus Apellidos para completar el registro"
                },
                correo: {
                    required: "Por favor, introduzca una dirección de correo electrónico valida.",
                    email: "ingrese una dirección de correo valida"
                },
                password: {
                    required: "Por favor, introduzca una contraseña valida  para completar el registro",
                    minlength: "La Contraseña debe de tener un minimo de 8 caracteres. "
                },
                rol: {
                    required: "Por favor, seleccione un rol"
                }
            },
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                    type:"POST",
                    data: $(form).serialize(),
                    url: "registro_data",
                    dataType: "json",
                    success: function(res) {
                        if (res.status=='400') {
                            $.notify({
                                message: res.message
                            },{
                                type: 'danger'
                            });
                        }else if (res.status=='201') {
                            $.notify({
                                message: res.message
                            },{
                                type: 'success'
                            });
                            $(form)[0].reset();
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
        /*==========================================
        =            Edit Users            =
        ==========================================*/
        
        $('#formRegisterEdit').validate({
            rules: {
                nnombreame_user: {
                    required: true
                },
                apellido: {
                    required: true
                },
                correo: {
                    required: true,
                    email:true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                rol: {
                    required: true,
                },
            },
            messages: {
                nombre: {
                    required: "Por favor, introduzca su Nombre Completo para completar el registro"
                },
                apellido: {
                    required: "Por favor, introduzca sus Apellidos para completar el registro"
                },
                correo: {
                    required: "Por favor, introduzca una dirección de correo electrónico valida.",
                    email: "ingrese una dirección de correo valida"
                },
                password: {
                    required: "Por favor, introduzca una contraseña valida  para completar el registro",
                    minlength: "La Contraseña debe de tener un minimo de 8 caracteres. "
                },
                rol: {
                    required: "Por favor, seleccione un rol"
                }
            },
            submitHandler: function(form) {
                var id_register = $("#id_register").val()
                var route = $("#route").val();
                $(form).ajaxSubmit({
                    type:"PUT",
                    data: $(form).serialize(),
                    url: route+"users/edit_user/"+id_register,
                    dataType: "json",
                    success: function(res) {
                        if (res.status=='400') {
                            $.notify({
                                message: res.message
                            },{
                                type: 'danger'
                            });
                        }else if (res.status=='200') {
                            $.notify({
                                message: res.message
                            },{
                                type: 'success'
                            });
                            $(form)[0].reset();
                        }                        
                    },
                    error: function() {
                        $.notify({
                            message: "¡No se puede actualizar su registro, por favor intentalo más tarde!"
                        },{
                            type: 'danger'
                        });
                        return false;
                    }
                })
            }
        })
        
        /*=====  End of Edit Users  ======*/
        
    })
        
})(jQuery)

    $(".delete_user").click(function (e) { 
        e.preventDefault();
        var id_user = $(this).data("id_user");
        var route = $("#route").val();
        var deleted = 0;
        $.ajax({
            type: "DELETE",
            url: route+"users/delete_user/"+id_user,
            data: "",
            dataType: "json",
            async: false,
            success: function (response) {
                if(response.status=="200"){
                    deleted = 1;
                }
            }
        }).fail(function (error) {
            $.notify({
                message: "¡No se puedo eliminar su registro, por favor intentelo más tarde!"
            },{
                type: 'danger'
            });
            return false;
        });
        if(deleted=="1"){
            $(this).parent().parent().remove(); 
        }
    });
})

