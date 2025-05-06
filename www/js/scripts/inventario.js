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


function ventana(codigo){
    if (codigo>0){
        var parametros = {'code': codigo};
        $.ajax({
            data:parametros,
            url: 'api/api_inventario.php',
            type: 'POST',
            success: function (response) { 
                var obj = jQuery.parseJSON(response);
                if (obj != null){
                    var j=0;
                    $("#frminventario").find("input,textarea").each(function() {
                        if (j== 0){
                            if (obj[0][j])
                                $('#wizardPicturePreview').attr('src',obj[0][j]).fadeIn('slow');
                            else
                                $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
                            $("#txtimage").val(obj[0][j]);
                        }else
                            $(this).val(obj[0][j]);
                        j++;
                    });
                }
            }
        });
    }else{
        $('#txtcodigo').val(0);
        $('#txtnombre').val("");
        $('#txtcantidad').val("");
        $('#txtprecio').val("");
        $('#txtfecha').val("");
        $('#txtubica').val("");
        $('#txtdescrip').val("");
        $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
        $('#txtimage').val("");
    }
    mostra_ventana('ModalEmpresa');
}

function Borrar(codigo) 
{
    if(confirm('¿Desea eliminar registro?')){
        var parametros = {'code': codigo, 'fin': '1'};
        $.ajax({
            data:parametros,
            url: 'api/api_inventario.php',
            type: 'POST',
            success: function (response) { 
                console.log (response);
                if (response ==2){
                    $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, intente mas tarde.</p></div>');
                    premsg();
                }else{
                    $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                    premsg(1);
                }
            }
        });
        
    }
    else{
        return false;
    }
}