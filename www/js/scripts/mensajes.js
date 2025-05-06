$(function () {

    $("#txtfechacita" ).change(function() {
        $("#especialista").find('input').each(function () {
            $(this).prop('checked', false);
        });

        $('#pacientes').empty();
        limpiar();
    });

    $('#pacientes').click(function () {
        i=0;
        $('.slectOne').on('change', function() {
            $('.slectOne').not(this).prop('checked', false);
            $('#result').html($(this).data( "id" ));
            if($(this).is(":checked")){
                if (i==0){
                    presentar_datos($(this).val());
                    $('#result').html($(this).data( "id" ));
                }
                i++
            }
            limpiar();   
        });
    });

    $('#especialista').click(function () {
        var chkAsientos = document.getElementsByName("chespecial");
        var filtro="";
        var fecha = $('#txtfechacita').val();
        for(i=0;i<chkAsientos.length;i++)
            if(chkAsientos[i].checked)
                   filtro=chkAsientos[i].value+','+filtro;

                    var parametros = {'code': filtro,'tipo':'filtr','fecha':fecha};
                    limpiar();
                    $('#pacientes').empty();
                    if (filtro.length >0){
                        $.ajax({
                            data:parametros,
                            url: 'api/api_mensajes.php',
                            type: 'POST',
                            success: function (response) { 
                                //console.log(response);
                                var obj = jQuery.parseJSON(response);
                                i=0;
                                $.each(obj, function(key,value) {
                                        $("#pacientes").append('<li id="liespeci" class="list-group-item"> <input type="checkbox" class="form-check-input me-1 slectOne" data-id="'+i+' selected" id="chpacientes" name="chpacientes" type="chpacientes" value="'+value.id_citas+'"> '+value.cita_nombrepaciente+' '+value.cita_apellidopaciente+'</li>');
                                        i++;
                                });
                            }
                        });  
                    }
    });
});

function presentar_datos(datos){
    var parametros = {'code': datos,'tipo':'busc','fecha':''};
    limpiar();
        $.ajax({
            data:parametros,
            url: 'api/api_mensajes.php',
            type: 'POST',
            success: function (response) { 
                var obj = jQuery.parseJSON(response);
                var i=0;
                var idespe=0;
                $("#frmcitas input").each(function () {
                    if (i==0){
                        idespe=obj[0][i];
                        $("#idespecialista").val(obj[0][i]);
                    } 
                    else if (i==7)   $('#txttipocita').val(obj[0][i]);
                    else if (i == 8)  $('#cmbconfirma').val(obj[0][i]);
                    else if (i == 9)  $('#txtobservacion').val(obj[0][i]);
                    else if (i == 10){
                        $('#txtfechacitad').val(obj[0][i].split(' ')[0]);
                        $('#txthorai').val(obj[0][i].split(' ')[1].substring(0, 5));
                    }
                    else if (i==11) $('#txthoraf').val(obj[0][i].split(' ')[1].substring(0, 5));
                    else if (i==12) $('#idcita').val(obj[0][i]);
                    else $(this).val(obj[0][i]);
                    i++;
                });
            }
        });  1  
}


function limpiar(){
    var i=0;
    $('#idcita').val("0");
    $("#frmcitas input").each(function () {
        $(this).val("");
       i++;
    });
}
