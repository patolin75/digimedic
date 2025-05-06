function Borrar(ruta,codigo,abrir,idm,sub) 
{
    if(confirm('¿Desea eliminar registro?')){
        var parametros = {'code': codigo, 'fin': '1','abrir': abrir, 'id': idm,' sub': sub};
        $.ajax({
            data:parametros,
            url: 'api/empresas_api.php',
            type: 'POST',
            success: function (response) { 
                location.href="?open="+response;
            }
        });
        
    }
    else{
        return false;
    }
}

function ventana(ruta,codigo){
    $('#txtcodigo').val(codigo);
    if (codigo>0){
        var parametros = {'code': codigo};
        $.ajax({
            data:parametros,
            url: 'api/empresas_api.php',
            type: 'POST',
            success: function (response) { 
                var obj = jQuery.parseJSON(response);
                $.each(obj, function(key,value) {
                    $('#txtempresa').val(value.name_institucion);
                    $('#txtdetalle').val(value.detalle_institucion);
                    $('#txtdirecciom').val(value.direccion_institucion);
                    $('#txtmail').val(value.mail_institucion);
                    $('#txttelefono').val(value.telefono_institucion);
                    $('#cmbestado').val(value.estado);
                    $('#txtimage').val(value.logo);
                    if (value.logo.length > 0){
                        $('#wizardPicturePreview').attr('src',value.logo).fadeIn('slow');
                    }else{
                        $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
                    }
                    
                }); 
            }
        });
    }else{
        $('#txtcodigo').val(0);
        $('#txtempresa').val("");
        $('#txtdetalle').val("");
        $('#txtdirecciom').val("");
        $('#txtmail').val("");
        $('#txttelefono').val("");
        $('#cmbestado').val(0);
        $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
        $('#txtNamaFileH').val("");
    }
    mostra_ventana('ModalEmpresa');
}


    
$("#wizard-picture").change(function(){
    readURL(this);
});

function readURL(input) {
    const MAXIMO_TAMANIO_BYTES = 900000;
    if (input.files[0].size <= MAXIMO_TAMANIO_BYTES) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                $('#txtimage').val(e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }else{
        alerta('Tamaño máximo de imágen 1 MB.');
    }
}

function alerta(message) {
    var alertita = document.getElementById('alerta')
    var wrapper = document.createElement('div')
    $(alertita).empty();
    wrapper.innerHTML = '<div class="alert alert-warning fade in"><strong>Alerta!</strong> '+message+'</div>';
    alertita.append(wrapper);
    window.setTimeout(function() { $(".alert").alert('close'); }, 2000);
}

