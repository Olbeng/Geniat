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
        $('#formPublicationEdit').validate({
            rules: {  
                Titulo: {
                    required: true
                },
                Descripcion: {
                    required: true
                }
            },
            messages: {
                Titulo: {
                    required: "Por favor, introduzca el titulo de la publicación."
                },
                Descripcion: {
                    required: "Por favor, Colocar una descripción."
                }
            },
            submitHandler: function(form) {    
                var route = $("#route").val()
                var id_register = $("#id_register").val()
                if(id_register==""){
                    var method = "registro_data";
                    var type = "POST";
                }else{
                    var method = "edit_publication/"+id_register;
                    var type = "PUT";
                }            
                $(form).ajaxSubmit({
                    type: type,
                    data: $(form).serialize(),
                    url: route+"publications/"+method,
                    dataType: "json",
                    success: function(res) {
                        if (res.status=='400') {
                            $.notify({
                                message: res.message
                            },{
                                type: 'danger'
                            });
                        }else if (res.status=='200' || res.status=='201') {
                            $.notify({
                                message: res.message
                            },{
                                type: 'success'
                            });
                            location.reload();
                        }                        
                    },
                    error: function() {
                        $('#formPublicationEdit').fadeTo( "slow", 1, function() {
                            $.notify({
                                message: "¡No se pude realizar la actualizacion/registro de publicación, por favor intentalo más tarde!"
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
    })
        
})(jQuery)

    $(".delete_publication").click(function (e) { 
        e.preventDefault();
        var id_publication = $(this).data("id_publication");
        var route = $("#route").val();
        var deleted = 0;
        $.ajax({
            type: "DELETE",
            url: route+"publications/delete_publication/"+id_publication,
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

