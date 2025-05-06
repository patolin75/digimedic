
function ventana(codigo){
    if (codigo>0){
        var parametros = {'code': codigo};
        $.ajax({
            data:parametros,
            url: 'api/api_vademecum.php',
            type: 'POST',
            success: function (response) { 
                var obj = jQuery.parseJSON(response);
                if (obj != null){
                    var j=0;
                    $("#frmvade").find("input,textarea").each(function() {
                        $(this).val(obj[0][j]);
                        j++;
                    });
                }
            }
        });
    }else{
        $("#frmvade").find("input,textarea").each(function() {
            $(this).val("");
        });
        $('#txtcodigo').val(0);
    }
    mostra_ventana('ModalEmpresa');
}


function Borrar(codigo) 
{
    if(confirm('¿Desea eliminar registro?')){
        var parametros = {'code': codigo, 'fin': '1'};
        $.ajax({
            data:parametros,
            url: 'api/api_vademecum.php',
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