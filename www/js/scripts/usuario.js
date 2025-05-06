function Borrar(ruta,codigo,abrir,idm,sub,tipous) 
{
    if(confirm('¿Desea eliminar registro?')){
        var parametros = {'code': codigo, 'fin': '1','abrir': abrir, 'id': idm,'sub': sub,'tipous': tipous};
        $.ajax({
            data:parametros,
            url: 'api/usuarios_api.php',
            type: 'get',
            success: function (response) { 
                location.href="?open="+response;
            }
        });
        
    }
    else{
        return false;
    }
}

function cerrar_sesion(ruta,codigo,abrir,idm,sub){
    if(confirm('¿Desea cerrar sesion?')){
        var parametros = {'code': codigo, 'sesion': '1','abrir': abrir, 'id': idm,'sub': sub};
        $.ajax({
            data:parametros,
            url: 'api/usuarios_api.php',
            type: 'get',
            success: function (response) { 
                location.href="?open="+response;
            }
        });
        
    }
    else{
        return false;
    }
}

function cargar(ruta,code){
    var parametros = {'code': code};
        $.ajax({
            type: 'get',
            url: 'api/usuarios_api.php',
            data: parametros,
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                    $.each(obj, function(key,value) {
                        cargar_especialidad(value.id_institucion,value.id_especialidad);
                        $('#txtiduser').val(value.id_usuarios);
                        $('#cmbinstitu').val(value.id_institucion);
                        $('#txtcodigo').val(value.identificacion_usuarios);
                        $('#txtnombres').val(value.nombres_usuarios);
                        $('#txtapellidos').val(value.apellidos_usuarios);
                        $('#txtdireccion').val(value.direccion_usuarios);
                        $('#txtmail').val(value.email_usuarios);
                        $('#txttelefono').val(value.telefono_usuarios);
                        $('#txtsexo').val(value.sexo_usuarios);
                        $('#cmbtipo').val(value.tipo_usuarios);
                        $('#txtimage').val(value.foto_usuarios);
                        if (value.tipo_usuarios==2 && value.foto_usuarios !=null){
                            if (value.foto_usuarios.length > 0){
                                $("#wizardPicturePreview").attr("src",value.foto_usuarios);
                            }else{
                                $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
                            }
                        }else if (value.tipo_usuarios==1){
                                $("#wizardPicturePreview").attr("src",value.foto_usuarios);
                        }else{
                            if (value.foto_usuarios==null || value.foto_usuarios.length==0){
                                $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
                            }
                            else{
                                $("#wizardPicturePreview").attr("src","images/usuarios/"+value.foto_usuarios);
                            }
                        }
                        if (value.tipo_usuarios==1) $("#txtcodigo").prop("readonly" , true); else $("#txtcodigo").prop("readonly" , false);
                        $('#cmbestado').val(value.activo_usuarios);
                        if (value.rol_permiso ==null){
                            $('#cmbroles').val(0);
                        }else{
                            $('#cmbroles').val(value.rol_permiso);
                        }
                        $('#cmbespe').val(value.id_especialidad);
                        $('#txtuser').val(value.usuario_usuario);
                        valida_us();
                        //$('#txtcodigo').attr('readonly','readonly');
                        mostra_ventana('ModalEmpresa');
                    }); 
            }
        });
}

function limpiar(){
    $('#txtiduser').val(0);
    $('#cmbinstitu').val(0);
    $('#txtcodigo').val("");
    $('#txtnombres').val("");
    $('#txtapellidos').val("");
    $('#txtdireccion').val("");
    $('#txtmail').val("");
    $('#txttelefono').val("");
    $('#txtsexo').val("");
    $('#cmbtipo').val(2);
    $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
    $('#cmbestado').val(0);
    $('#cmbroles').val(0);
    $('#txtuser').val("");
    $('#txtpass').val("");
    $('#txtrepass').val("");
    $('#cmbtipo option:not(:selected)').attr('disabled',true);
    valida_us();
    mostra_ventana('ModalEmpresa');
}

function valida_us() {
    tipous = $("#cmbtipo :selected").val();
    if (tipous ==2){
        $("#usein").css("visibility","visible"); 
    }else{
        $("#usein").css("visibility","hidden");
    }
}

function cargar_especialidad(code,selec){
    var parametros = {'code': code, 'espe': '1'};
    $.ajax({
        data:parametros,
        url: 'api/usuarios_api.php',
        type: 'get',
        success: function (response) { 
            $('#cmbespe').empty();
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key,value) {
                $("#cmbespe").append('<option value='+value.id_especialidad+'>'+value.esp_nombre+'</option>');
                if(selec==0) selec=value.id_especialidad;
            }); 
            
            $('#cmbespe').val(selec);
        }
    });
}

$(document).ready(function(){
    $("#txtpassw").on("input", function(){
        valor = document.getElementById("txtpassw").value;
        if (valor.length >0){
            if (valor.length < 6){
                show_alertac('Recuerde que su contraseña debe tener al menos 6 caracteres');
            }else if (tiene_numeros(valor) ==0) { 
                show_alertac('Debe contener al menos un numero');
            }else if (tiene_mayusculas(valor) ==0 ){
                show_alertac('Debe contener al menos una letra mayúscula');
            }else{
                $(form_errorsu).hide();
                $('#bguardar').show();
            }
        }
    });


    $("#txtpaswr").on("input", function(){
        valor = document.getElementById("txtpassw").value;
        valor1 = document.getElementById("txtpaswr").value;

        if (valor1.length == valor.length){
            if (valor1 != valor){
                show_alertac('Re-ingreso no coincide con la clave ingresada');
            }else{
                $(form_errorsu).hide();
                $('#bguardar').show();
            }
        }else{
            show_alertac('Recuerde que el re-ingreso debe coincidir con su clave ingresada');
        }
    });
});


$("#wizard-picture").change(function(){
    readURL(this);
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
            $('#txtimage').val(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}