
var avance = 0;
var elemento ="#elementos";

$(function () {
    $('#especialista').click(function () {
        i=0;
        $('.slectOne').on('change', function() {
            $('.slectOne').not(this).prop('checked', false);
            if($(this).is(":checked")){
                if (i==0){
                    id=$(this).val();
                    $('#idespecialista').val(id);
                }
                i++;
            }
            //limpiar();
        });
    });    
});


$(function () {
    $('#entregas').click(function () {
        i=0;
        $('.slectTwo').on('change', function() {
            $('.slectTwo').not(this).prop('checked', false);
            if($(this).is(":checked")){
                if (i==0){
                    id=$(this).val();
                }
                i++;
            }
            //limpiar();
        });
    });    
});



function recuperar_campos(codigo){
    var parametros = {'code': codigo,'what': 'recupera'};
    limpiar();
    $.ajax({
        type: 'post',
        url: 'api/api_entregainv.php',
        data: parametros,
        success: function(response) {
            if (response){
                var obj = jQuery.parseJSON(response);
                var i=0;
                if (obj){
                    $("#frmprod input,textarea").each(function () {
                        if (i==1) 
                            if (obj[0][i] ==0){
                                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>No existe cantidad (0) de producto en inventario.</p></div>');
                                premsg();
                                limpiar();
                                return false;
                            }
                        if (i==2){
                            if (obj[0][i] !=null && obj[0][i].length >5 ) $('#wizardPicturePreview').attr('src', obj[0][i]); 
                            else $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
                            i++;
                        }
                        $(this).val(obj[0][i]);
                        i++;
                    });
                }
               
            }
        }
    });
    
}

function validar_existencias(cantidad){
    var total = 0;
    total = $('#txtcantidad').val();
    if (total.length >0 && total>0){
        if (Number(cantidad) > Number(total)){
            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Esta solicitando más de lo que hay en existencia.</p></div>');
            premsg();
            cantidad= total;
            $('#txtcanti').val(cantidad);
        }
    }else{
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione producto que tenga existencia.</p></div>');
        premsg();
        $('#txtcanti').val(1);
    }
}

function limpiar(){
    $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
    $("#frmprod input,textarea").each(function () {
        $(this).val("");
    });
}

function entregar(entrega){
    var codigo = $('#selecpe').val(); //codprod
    var recibe = $('#idespecialista').val();
    var total = $('#txtcantidad').val(); //cant existencia
    var totale = $('#txtcanti').val(); //cant entregar
    var observa = $('#txtobserva').val();

    if (recibe >0){
        if (codigo >0){
            if (total.length >0 && total>0){
                if (totale<=total){
                    var parametros = {'code': codigo, 'uentre': entrega, 'recib': recibe, 'existe': total,'quita':totale,'observa': observa, 'what': 'entrega'};
                    $.ajax({
                        type: 'post',
                        url: 'api/api_entregainv.php',
                        data: parametros,
                        success: function(response) {
                            if (response ==2){
                                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Error al realizar la entrega, intente mas tarde.</p></div>');
                                premsg();
                            }else{
                                if(confirm('¿Desea imprimir entrega?'))
                                    impresion();
                                $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>'); 
                                premsg(1);
                            }
                        }
                    });
                }else{
                    $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Debe entregar lo que hay en existencia.</p></div>'); 
                    premsg();
                }
            }else{
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione producto que tenga existencia.</p></div>');
                premsg();
            }
        }else{
            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione producto que tenga existencia.</p></div>'); 
            premsg();
        }
    }else{
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione especialista.</p></div>'); 
        premsg();
    }
        

}

function impresion(){
    jQuery('#impresion').print();
}


function agregar_prod(){
   avance = avance+1;
    if ($('#selecpe').val() >0){

        var uno = $('#txtcanti').val();
        var dos = $('#txtcantidad').val();

        if (Number(uno) <= Number(dos)){
            var $el= $('<div class="drop-item"><div id="elements'+avance+'"><input value="'+avance+'" type="text"/> <input value="'+$('#selecpe').val()+'-'+ $("select[name='selecpe'] option:selected").text()+'" type="text"/> <input value="'+$('#txtcanti').val()+'" type="text"/> <input value="'+$('#txtobserva').val()+'" type="text"/> </div></div>');
            $el.append($('<button type="button" class="btn btn-default btn-xs remove"><span class="glyphicon glyphicon-trash"></span></button>').click(function () { elimina_prod($(this).parent(),avance); }));
            $(elemento).append($el);
    
            $('#txtcantidad').val($('#txtcantidad').val()-$('#txtcanti').val());
        }else{
            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Esta solicitando más de lo que hay en existencia.</p></div>');
            premsg();
        }
      
    }
}

function elimina_prod(objeto,avance){
    $("#elements"+avance+" input").each(function () {
        alert ($(this).val());
    });
    objeto.detach();
}



