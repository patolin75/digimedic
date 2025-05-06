$(function () {
    $('#especialista').click(function () {
        i=0;
        $('.slectOne').on('change', function() {
            $('.slectOne').not(this).prop('checked', false);
            $('#result').html($(this).data( "id" ));
            if($(this).is(":checked")){
                if (i==0){
                    id=$(this).val();
                    cargar_datos(id);
                    $('#result').html($(this).data( "id" ));
                }
                i++;
            }
            limpiar();
        });
    });    
});

function cita_actua(code){
    var parametros = {'code': code,'what': 'cit'};
    $.ajax({
        type: 'post',
        url: 'api/api_pacientes.php',
        data: parametros,
        success: function(response) {
            $("#bannercitas").html(response);
        }
    });
}

function cargar_datos(id){
    var parametros = {'code': id,'what': 'datos'};
    $.ajax({
        type: 'post',
        url: 'api/api_pacientes.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            $("#formahis").attr("action",obj[1]['link']);
            var i=0;
            $("#frmdatos input,select").each(function () {
                if (i==2)
                    if (obj[0][i] !=null && obj[0][i].length >5 ) $('#wizardPicturePreview').attr('src', obj[0][i]); 
                    else $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
                else if(i==17)
                    $('#fregistro').text(obj[0][i]);
                else 
                    $(this).val(obj[0][i]);
                i++;
            });
            cargar_residencia(id);
        }
    });
}

function cargar_residencia(id){
    var parametros = {'code': id,'what': 'resid'};
    $.ajax({
        type: 'post',
        url: 'api/api_pacientes.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            var j=0;
            if (obj != null){
                //$("#frmresidencia input").each(function () {
                $("#frmresidencia").find('input').each(function() {
                    $(this).val(obj[0][j]);
                    j++;
                });
            }
            cargar_datosadicion(id);
        }
    });
}

function cargar_datosadicion(id){
    var valor=0;
    var parametros = {'code': id,'what': 'adici'};
    $.ajax({
        type: 'post',
        url: 'api/api_pacientes.php',
        data: parametros,
        success: function(response) {
            if (response !=null){
                var obj = jQuery.parseJSON(response);
                var k=0;
                if (obj != null){
                    $("#frmadicionales").find('input,select').each(function() {
                        if (k==7){
                            valor = obj[0][k];
                            $(this).val(obj[0][k]);
                        }
                        else
                            $(this).val(obj[0][k]);
                        k++;
                    });
                }
            }
            range.value=valor;
            cargar_contacto(id);
        }
    });
}


function cargar_contacto(id){
    var parametros = {'code': id,'what': 'conta'};
    $.ajax({
        type: 'post',
        url: 'api/api_pacientes.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            var l=0;
            if (obj != null){
                //$("#frmcontacto input").each(function () {
                $("#frmcontacto").find('input,select').each(function() {
                        $(this).val(obj[0][l]);
                        l++;
                });
            }
        }
    });
}

function limpiar(si=0){
    $('#range').val('0');
    $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
    $("#frmdatos input,select").each(function () { cambiar(this); });
    $("#frmresidencia input").each(function () { cambiar(this); });
    $("#frmadicionales input,select").each(function () { cambiar(this); });
    $("#frmcontacto input,select").each(function () { cambiar(this); });
    $('#idpac').val("0");
    $('#fregistro').text("");
    if (si==1) $('.slectOne').not(this).prop('checked', false);
}
function cambiar(objeto){
    if ($(objeto).attr('type') =='text' || $(objeto).attr('type') =='date' || $(objeto).attr('type') =='file')
        $(objeto).val("");
    else
        $(objeto).val(0);
}

function guardar_cliente(permiso){
    if (permiso ==0){
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione un paciente para ejecutar esta tarea.</p></div>');
        premsg();
    }else{
        var jsonData = new Array();
        jsonData.push({"id":$('#valtmp').attr("name"),"name":$('#valtmp').val()});
        $("#frmdatos").find('input,select').each(function() {jsonData.push({"id":$(this).attr("name"),"name":$(this).val()});});
        var objdata = JSON.stringify(jsonData);
        $.ajax({
            type: 'post',
            url: 'api/api_pacientes.php',
            data: objdata,
            success: function(response) {
                if (response=='r'){
                    $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, numero de identificacion ya existe.</p></div>');
                    premsg();
                }else if (response =='f'){
                    $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revise la pestaña datos personales que no contenga vacios.</p></div>');
                    premsg(); 
                }else{
                    guardar_residencia(response);
                }
            }
        });
    }
}

function guardar_residencia(id){
    var jsonResidencia =new Array();
    jsonResidencia.push({"id":$('#idpac').attr("name"),"name": id+'-'+$('#idpac').val()});
    $("#frmresidencia").find('input,select').each(function() {jsonResidencia.push({"id":$(this).attr("name"),"name":$(this).val()});});
    var objResiden = JSON.stringify(jsonResidencia);
    $.ajax({
        type: 'post',
        url: 'api/api_pacientes.php',
        data: objResiden,
        success: function(response) {
            if (response =='e'){
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revise pestaña datos de residencia que no contenga vacios.</p></div>');
                premsg();
            }else{
                guardar_adicionales(id);
            }
        }
    });
}

function guardar_adicionales(id){
    var jsonAdicional=new Array();
    jsonAdicional.push({"id":$('#idpac').attr("name"),"name":id+'-'+$('#idpac').val()});
    $("#frmadicionales").find('input,select').each(function() {jsonAdicional.push({"id":$(this).attr("name"),"name":$(this).val()});});
    var objAdicional = JSON.stringify(jsonAdicional);
    $.ajax({
        type: 'post',
        url: 'api/api_pacientes.php',
        data: objAdicional,
        success: function(response) {
            if (response =='e'){
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revise pestaña datos adicionales que no tenga vacios.</p></div>');
                premsg();
            }else{
                guardar_contacto(id);
            }
        }
    });
}

function guardar_contacto(id){
    var jsonContacto=new Array();
    jsonContacto.push({"id":$('#idpac').attr("name"),"name":id+'-'+$('#idpac').val()});
    $("#frmcontacto").find('input,select').each(function() { jsonContacto.push({"id":$(this).attr("name"),"name":$(this).val()});});
    var objContacto = JSON.stringify(jsonContacto);
    $.ajax({
        type: 'post',
        url: 'api/api_pacientes.php',
        data: objContacto,
        success: function(response) {
            if (response =='e'){
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revise pestaña datos contacto que no tenga vacios.</p></div>');
            }else{
                $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                $('#idpac').val(id);
            }
            premsg();
        }
    });
}

function eliminar_cliente(){
    if ($('#idpac').val() !=null && $('#idpac').val() >0){
        if(confirm('¿Desea eliminar registro?')){
            var parametros = {'code': $('#idpac').val(),'what': 'delta'};
            $.ajax({
                type: 'post',
                url: 'api/api_pacientes.php',
                data: parametros,
                success: function(response) {
                    if (response =='e'){
                        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revise pestaña datos contacto que no tenga vacios.</p></div>');
                        premsg();
                    }else{
                        $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                        premsg(1);
                    }
                }
            });
        }
    }
}

function ir_historia(){
    if ($('#idpac').val() !=null && $('#idpac').val() >0){
            $('#idp').val($('#idpac').val());
            document.getElementById('formahis').submit();
    }else{
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Por favor seleccione un paciente.</p></div>');
        premsg();
    }
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
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Tamaño máximo de imágen 1 MB.</p></div>');
        premsg();
    }
}
