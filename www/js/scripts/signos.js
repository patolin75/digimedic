$(function () {
    $('#especialista').click(function () {
        i=0;
        $('.slectOne').on('change', function() {
            $('.slectOne').not(this).prop('checked', false);
            if($(this).is(":checked")){
                if (i==0){
                    $('#idpac').val($(this).val());
                    recupera_historia($('#idins').val(),$(this).val(),$('#selecpe').val());
                }
                i++;
            }
            if (i==0) limpia1();
        });
    });    
});


$(function () {
    $('#historial').click(function () {
        i=0;
        $('.slecttwo').on('change', function() {
            $('.slecttwo').not(this).prop('checked', false);
            if($(this).is(":checked")){
                if (i==0){
                    cargar_valor_visita($(this).val());
                }
                i++;
            }
            limpiar2();
        });
    });    
});

function recupera_historia(institu,cliente,user){
    var user=$('#selecpe').val();
    user=user.split('-')[1];
    $('#historial').empty();
    if (user>0){
        user=user.split('-')[1];
        var parametros = {'code': cliente,'institu': institu,'user': user,'what': 'hist'};
        $.ajax({
            type: 'post',
            url: 'api/api_hiscli.php',
            data: parametros,
            success: function(response) {
                //numh
                var obj = jQuery.parseJSON(response);
                i=0;
                $.each(obj, function(key,value) {
                        $('#fregistro').text(value.fechah);
                        $('#hiscli').val(value.idh);
                        recupera_visitas($('#selecpe').val(),value.idh);
                        i++;
                });
            }
        });
    }else{
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione un especialista.</p></div>');
        premsg();
    }
   
}

function recupera_visitas(user,idh){
    var user=user.split('-')[1];
    var parametros = {'code': idh, 'user': user, 'what': 'visi'};
    $.ajax({
        type: 'post',
        url: 'api/api_hiscli.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            i=0;
            $.each(obj, function(key,value) {
                    $("#historial").append('<li id="liespeci" class="list-group-item"> <input type="checkbox" class="form-check-input me-1 slecttwo" data-id="'+i+' selected" id="chpacientes" name="chpacientes" type="chpacientes" value="'+value.idvisit+'"> '+value.fecha+'</li>');
                    i++;
            });
        }
    });
}

function cargar_valor_visita(idvisita){
    var parametros = {'code': idvisita, 'what': 'valvisit'};
    $('#visihis').val(0);
    $.ajax({
        type: 'post',
        url: 'api/api_hiscli.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            i=0;
            $.each(obj, function(key,value) {
                var obj1 = jQuery.parseJSON(value.campo1);

                $("#zone0").find('textarea,input,select').each(function() {
                    var object = this.id;
                    if (object.indexOf('txtmsg') > -1 ){
                        textarea = document.getElementById(object);
                        sceditor.instance(textarea).val(obj1['dropzone0'][0][i]);
                        i++;
                    }else
                        if (object.length >0){
                            $(this).val(obj1['dropzone0'][0][i]);
                            i++;
                        } 
                });
                
                $('#visihis').val(value.idvisit);
                i++;
            });
        }
    });
}

function guardar_visit(){
    var user=$('#selecpe').val();
    var idpac = $('#idpac').val();
    var idins = $('#idins').val(); 
    var hiscli = $('#hiscli').val(); 
    var visihis =  $('#visihis').val(); 
    var valor ="";

    user=user.split('-')[1];
    if (user>0 && idpac >0){
        //armar
        var jsonfin="";
        var i = 0;
        var value ="";
        var textarea="";
        valor='{"dropzone0":[{ ';
        $("#zone0").find('textarea,input,select').each(function() {
            var object = this.id;
   
            if (object.indexOf('txtmsg') > -1 ){
                textarea = document.getElementById(object);
                value = sceditor.instance(textarea).val();
                jsonfin+='"'+i+'":"'+value+'",';
                i++;
            }else
                if (object.length >0){
                    jsonfin+='"'+i+'":"'+$(this).val()+'",';
                    i++;
                } 
        });
        jsonfin = jsonfin.substring(0, jsonfin.length - 1);
        jsonfin= valor + jsonfin +"}]}";
        var parametros = {'code': idpac,'institu': idins,'user': user,'hiscli': hiscli,'visihis': visihis,'valor':jsonfin,'what': 'savevis'};
        $.ajax({
            type: 'post',
            url: 'api/api_sigvi.php',
            data: parametros,
            success: function(response) {
               if (response=='e')
                    $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Hubo un problema al guardar, intente nuevamente.</p></div>');
               else{
                    $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                    $('#visihis').val(response);
               }
               premsg();
            }
        });
    }else{
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione un especialista y un paciente.</p></div>');
        premsg();
    }
}

function eliminar_visit(){
    var idhisvi = $('#visihis').val();
    if (idhisvi >0){
        if(confirm('¿Desea eliminar registro?')){
            var parametros = {'code': idhisvi,'what': 'eliminvi'};
            $.ajax({
                type: 'post',
                url: 'api/api_sigvi.php',
                data: parametros,
                success: function(response) {
                if (response=='e')
                        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Hubo un problema al eliminar, intente nuevamente.</p></div>');
                else{
                        $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                        limpiar_n();
                }
                premsg();
                }
            });
        }
    }else{
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione un elemento de la seccion historial.</p></div>');
        premsg();
    }
    
   
}

function recuperar_campos(institu,especiali){
    var especial=especiali.split('-')[0]
    var parametros = {'hdesde': institu, 'hhasta': especial, 'permisis': '7'};
    $.ajax({
        data:parametros,
        url: 'api/configurar_api.php',
        type: 'POST',
        success: function (response) { 
            limpiar();
            if (response){
                var obj = jQuery.parseJSON(response);
                var i=0;
                var elemento;
                var elemento1;
                var elemento2;
                $.each(obj, function(key,value) {
                    if (i==0){
                        elemento = $('#zone'+i);
                        if (typeof value === 'string') {
                            elemento1= document.getElementById('titulo'+i);
                            elemento2= document.getElementById('li'+i);
                            elemento1.innerHTML = value;
                            elemento2.classList.remove("hidden");
                            if (value != 'SIGNOS VITALES') return false;
                        }else{
                            var j =0;
                            var val1="";
                            var val2="";
                            var $el="";
                            var agrup =0;
                            for (let valor in value[0]){
                                if (j==0) 
                                    val1 = value[0][valor];
                                else{
                                    val2 = value[0][valor];
                                    $el= $el + get_objeto(val1,val2,i,agrup);
                                    //elemento.append($el);
                                    if (val1=='Textarea') crear_textarea(i);
                                }
                                j++;
                                if (j>1) j=0;
                                if (val1 =='Agrupar') agrup++;
                            }
                            if (agrup>0) {
                                $el = $el +'</div></div>';
                            } 
                            elemento.append($el);
                            i++;
                        }
                    }
                });
            }
        }
    });
}

function limpiar_n(){
    document.getElementById("selecpe").value =0;
    $('#selecpe').selectpicker('val', '0');
    limpiar();
}

function limpiar2(){
    $('#visihis').val(0);
    $("#zone0").find('textarea,input,select').each(function() {
        var object = this.id;
        if (object.indexOf('txtmsg') > -1 ){
            textarea = document.getElementById(object);
            sceditor.instance(textarea).val("");
            i++;
        }else
            $(this).val(""); 
    });
}

function limpia1(){
   // limpiar2();
    $('#fregistro').text("");
    $('#hiscli').val(0);
    $('#idpac').val(0);
    $('#historial').empty();
}

function limpiar(){
    limpia1();
    $('#zone0').empty();
    $('.slectOne').prop('checked', false);
}


function get_objeto(tipo,etiqueta,i,agrup){
    var objeto="";
    var resto
    var select 
    if (tipo =='Catalogo'){
        resto=etiqueta.split(':')[1]
        etiqueta =etiqueta.split(':')[0]
    }
    if (tipo !='Agrupar') objeto = '<label>' + etiqueta + ' :</label>';

    if (tipo == 'Textarea')
        objeto = objeto +'<textarea class="form-control textarea" id="txtmsg'+i+'" name="txtmsg'+i+'" rows="3"></textarea>';
    else if (tipo =='Catalogo'){
        var recorrer = resto.split(',');
        select= '<select class="form-control" id="'+tipo+' '+i+'" name="'+tipo+' '+i+'">';
        for (var t=0; t< recorrer.length;t++){
            select = select +'<option value="'+recorrer[t]+'">'+recorrer[t]+'</option>';
        }
        select = select + '</select>';
        objeto = objeto + select;
    }
    else if (tipo =='Agrupar'){
        if (agrup>1) objeto = '</div></div>';
        objeto = objeto+ '  <div class="panel panel-primary"><div class="panel-heading"> <span class="pull-left clickable"><h3 class="panel-title"> '+etiqueta+' </h3></span>'+
                        '<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span><br></div><div class="panel-body collapse">';
    }
    else
        objeto = objeto +'<input class="form-control" type="'+tipo+'" id="'+tipo+' '+i+'" name="'+tipo+' '+i+'">';

    return objeto;
}

$(document).on('click', '.panel-heading span.clickable', function(e){
    var $this = $(this);
	if(!$this.hasClass('panel-collapsed')) {
        $this.parents('.panel').find('.panel-body').slideDown();
		$this.addClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
	} else {
		$this.parents('.panel').find('.panel-body').slideUp();
		$this.removeClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
	}
})


function crear_textarea(i){
    var textarea = document.getElementById('txtmsg'+i);
    sceditor.create(textarea, {
        format: 'bbcode',
        icons: 'monocons',
        style: 'js/editor/themes/content/default.min.css',
        locale: 'es',
        width: "100%",
        height: "100%",
        toolbar: "bold,italic,underline,strike,subscript,superscript|font,size,color,removeformat|cut,copy,pastetext|horizontalrule|date,time"
    });
    //document.getElementById('theme-style').href = theme;
}