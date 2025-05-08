//var theme = 'js/editor/themes/office.min.css';
window.onload = function () {
    var textpedidos = document.getElementById('txtexma2');
    sceditor.create(textpedidos, {
        format: 'bbcode',
        icons: 'monocons',
        style: 'js/editor/themes/content/default.min.css',
        locale: 'es',
        width: "100%",
        height: "100%",
        toolbar: "bold,italic,underline,strike,subscript,superscript|font,size,color,removeformat|cut,copy,pastetext|horizontalrule,image,email,link,unlink|date,time|maximize"});

    var textarea1 = document.getElementById('txtmsg55');
    sceditor.create(textarea1, {
        format: 'bbcode',
        icons: 'monocons',
        style: 'js/editor/themes/content/default.min.css',
        locale: 'es',
        width: "100%",
        height: "100%",
        toolbar: "bold,italic,underline,strike,subscript,superscript|font,size,color,removeformat|cut,copy,pastetext|horizontalrule,image,email,link,unlink|date,time|maximize"});
    var textarea2 = document.getElementById('txtmsg56');
    sceditor.create(textarea2, {
        format: 'bbcode',
        icons: 'monocons',
        style: 'js/editor/themes/content/default.min.css',
        locale: 'es',
        width: "100%",
        height: "100%",
        toolbar: "bold,italic,underline,strike,subscript,superscript|font,size,color,removeformat|cut,copy,pastetext|horizontalrule,image,email,link,unlink|date,time"}); 

    var txtobserva = document.getElementById('txtobserva');
        sceditor.create(txtobserva, {
            format: 'bbcode',
            icons: 'monocons',
            style: 'js/editor/themes/content/default.min.css',
            locale: 'es',
            width: "100%",
            height: "100%",
            toolbar: "bold,italic,underline,strike,subscript,superscript|font,size,color,removeformat|cut,copy,pastetext|horizontalrule|date,time|print"});
}



                       

$(function () {
    $('#especialista').click(function () {
        i=0;
        $('.slectOne').on('change', function() {
            $('.slectOne').not(this).prop('checked', false);
            if($(this).is(":checked")){
                if (i==0){
                    recupera_historia($(this).val(),$('#txtus').val());
                    cargar_examenes($(this).val());
                }
                i++;
            }
            limpiar();
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

$(function () {
    $('#examenes').click(function () {
        i=0;
        $('.slecttree').on('change', function() {
            $('.slecttree').not(this).prop('checked', false);
            if($(this).is(":checked")){
                if (i==0){
                    cargar_resultados($(this).val());
                }
                i++;
            }
            limpiar_exam();
        });
    });    
});


$(function () {
    $('.imprimerec').click(function () {
        //patolin
        let textarea    = document.getElementById('txtmsg55'),
            textarea1   = document.getElementById('txtmsg56'),
            nombre      = "";
            
        var listItems = $("#especialista li");
        for (let li of listItems) {
            let product = $(li);
            if(product[0].firstElementChild.checked)
                nombre=product[0].innerText;
        }
        $(".receta").find("fecha").html('Fecha : '+fecha_actual());
        $(".receta").find("nombre").html('Nombre : '+nombre);
        $(".receta").find("diagnostico").html('Diagnóstico : ');
        
        $('.receta').removeAttr('hidden');
        $(".receta").find("medicamentos").html(sceditor.instance(textarea).val());
        $(".receta").find("prescripcion").html(sceditor.instance(textarea1).val());
            jQuery('.receta').print();
        $('.receta').attr('hidden',true);  
    });    
});




function fecha_actual(){
    var fecha   = new Date(),
        hora    = 0,
        minutos = 0,
        segundo = 0,
        mes = fecha.getMonth()+1,
        dia = fecha.getDate(),
        ano = fecha.getFullYear();
    if(dia<10) dia='0'+dia; 
    if(mes<10) mes='0'+mes;
    hora=(fecha.getHours() < 10) ? ("0" + fecha.getHours()) : fecha.getHours();
    minutos=(fecha.getMinutes() < 10) ? ("0" + fecha.getMinutes()) : fecha.getMinutes();
    segundo=(fecha.getSeconds() < 10) ? ("0" + fecha.getSeconds()) : fecha.getSeconds();
    return ano+"-"+mes+"-"+dia+"T"+hora+ ":" + minutos;
}

function recupera_historia(cliente,user){
    var institu = $('#idins').val();
    $('#historial').empty();
    $('#idpac').val(cliente);
    if (user>0){
        var parametros = {'code': cliente,'institu': institu,'user': user,'what': 'hist'};
        $.ajax({
            type: 'post',
            url: 'api/api_hiscli.php',
            data: parametros,
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                i=0;
                $.each(obj, function(key,value) {
                        $('#fregistro').text(value.fechah);
                        $('#hiscli').val(value.idh);
                       // $('#idpac').val(cliente);
                        recupera_visitas($('#txtus').val(),value.idh);
                        i++;
                });
                genera_qr(cliente,institu);
            }
        });
    }else{
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione un especialista.</p></div>');
        premsg();
    }
}

function genera_qr(cliente,institu){
    var parametros = {'code': cliente, 'institu':institu,'what': 'qr'};
    $.ajax({
        type: 'post',
        url: 'api/api_hiscli.php',
        data: parametros,
        success: function(response) {
            $('#imgqr').attr('src','data:image/png;base64,'+response);
        }
    });
}

function recupera_visitas(user,idh){
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
            if (response){
                var obj = jQuery.parseJSON(response);
                $('#visihis').val(idvisita);
                var zona=0
                $.each(obj, function(key,value) {
                    if (value.campo1){
                        var obj1 = jQuery.parseJSON(value.campo1);
                        llenado(zona,obj1);
                        zona++;
                    }
                    if (value.campo2){
                        var obj1 = jQuery.parseJSON(value.campo2);
                        llenado(zona,obj1);
                        zona++;
                    }
                    if (value.campo3){
                        var obj1 = jQuery.parseJSON(value.campo3);
                        llenado(zona,obj1);
                        zona++;
                    }
                    if (value.campo4){
                        var obj1 = jQuery.parseJSON(value.campo4);
                        llenado(zona,obj1);
                        zona++;
                    }
                    if (value.campo5){
                        var obj1 = jQuery.parseJSON(value.campo5);
                        llenado(zona,obj1);
                        zona++;
                    }
                    if (value.campo6){
                        var obj1 = jQuery.parseJSON(value.campo6);
                        llenado(zona,obj1);
                        zona++;
                    }
                    $('#visihis').val(value.idvisit);
                });
            }
        }
    });
}

function llenado(zona,obj1){
    var i=0;
    $("#zone"+zona).find('textarea,input,select').each(function() {
        var object = this.id;
        if (object.indexOf('txtmsg') > -1 ){
            textarea = document.getElementById(object);
            sceditor.instance(textarea).val(obj1['dropzone'+zona][0][i]);
            i++;
        }else if (this.type=='checkbox'){
            if (obj1['dropzone'+zona][0][i]==1)
                this.checked = true;
            else
                this.checked = false;
            i++
        }else
            if (object.length >0){
                $(this).val(obj1['dropzone'+zona][0][i]);
                i++;
            } 
    });
}

function cargar_examenes(id){
    var institu = $('#idins').val();
    var usuario = $('#txtus').val();
    var parametros = {'code': id,'institu': institu, 'user': usuario, 'what': 'exam'};
    $('#examenes').empty();
    $.ajax({
        type: 'post',
        url: 'api/api_hiscli.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key,value) {
                $('#examenes').append('<li id="liexamenes" class="list-group-item"> <input class="form-check-input me-1 slecttree" type="checkbox" value="'+ value.id +'"> Cod: '+value.id+' Fecha: ' + value.fechas +'</li>');
            }); 
        }
    });
}


function cargar_resultados(id){
    var parametros = {'code': id,'what': 'result'};
    var host = window.location.host;
    var path = window.location.pathname;
    var URLactual = 'http://'+host+path;
    $('#previews').empty();

    contador=0;
    $.ajax({
        type: 'post',
        url: 'api/exa_api.php',
        data: parametros,
        success: function(response) {
            if (response){
                var obj = jQuery.parseJSON(response);
                $.each(obj, function(key,value) {
                    if (value.archivo1){
                        var ext = value.archivo1.split('.').pop();
                        contador++;
                        agregar_prev(contador,value.archivo1);
                        presentar_img('',contador,ext,URLactual+value.archivo1);
                        document.getElementById('linkfile'+contador).href = URLactual + value.archivo1;
                    }
                    if (value.archivo2){
                        var ext = value.archivo2.split('.').pop();
                        contador++;
                        agregar_prev(contador,value.archivo1);
                        presentar_img('',contador,ext,URLactual+value.archivo2);
                        document.getElementById('linkfile'+contador).href = URLactual + value.archivo2;
                    }
                    if (value.archivo3){
                        var ext = value.archivo3.split('.').pop();
                        contador++;
                        agregar_prev(contador,value.archivo1);
                        presentar_img('',contador,ext,URLactual+value.archivo3);
                        document.getElementById('linkfile'+contador).href = URLactual + value.archivo3;
                    }
                    if (value.archivo4){
                        var ext = value.archivo4.split('.').pop();
                        contador++;
                        agregar_prev(contador,value.archivo1);
                        presentar_img('',contador,ext,URLactual+value.archivo4);
                        document.getElementById('linkfile'+contador).href = URLactual + value.archivo4;
                    }
                  
                    $('#idexam').val(id);
                    sceditor.instance(txtobserva).val(value.resultados);
                }); 
            }
        }
    });
}

function agregar_prev(id,name){
    var preview = document.querySelector('#previews');
    var template = document.createElement("div");
    template.className = 'template'; 
    template.id= "template"+id;
    var texto = document.innerHTML=elemento(id,name);
    template.innerHTML=texto;
    preview.appendChild(template);
} 

function presentar_img(e="",id,ext,ruta=""){
    if (ext =='doc' || ext == 'docx')
        $('#prevdoc_exa'+id).attr('src', 'images/word.png').fadeIn('slow');
    else if(ext =='pdf')
        $('#prevdoc_exa'+id).attr('src', 'images/pdf.png').fadeIn('slow');
    else{
        if (ruta)
            $('#prevdoc_exa'+id).attr('src', ruta).fadeIn('slow');
        else
            $('#prevdoc_exa'+id).attr('src', e.target.result).fadeIn('slow');
    }
       
}

function elemento(id,name){
    element='<div id="template" class="file-row"><div class="col-xs-3 col-lg-3">' +
            '<span class="preview" style="width:100px;height:100px;"><a id="linkfile'+id+'" href="" target="_blank"><img class="prevdoc_exa" style="width:100px;height:100px;" id="prevdoc_exa'+id+'"/></a>'+
             '<input type="text" class="form-control" id="file'+id+'" name="file'+id+'" value="'+name+'" readonly></span><br/><strong class="error text-danger" id="msgerror'+id+'" data-dz-errormessage></strong></div></div></div>';
    return element;
}


function limpiar2(){
    $('#visihis').val(0);
    for (var elemen=0; elemen <=5; elemen++){
        $("#zone"+elemen).find('textarea,input,select').each(function() {
            var object = this.id;
            $(this).prop('checked', false);
            if (object.indexOf('txtmsg') > -1 ){
                textarea = document.getElementById(object);
                sceditor.instance(textarea).val("");
                i++;
            }else
                $(this).val(""); 
        });
    }
}

function finalizar_cita(){
    var idpac = $('#idpac').val();
    if (idpac == null || idpac ==0){
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, seleccione un paciente por favor.</p></div>');
        premsg();
    }
    else{
        var parametros = {'code': idpac, 'what': 'cerrarse'};
        $.ajax({
            type: 'post',
            url: 'api/api_hiscli.php',
            data: parametros,
            success: function(response) {
                if (response=='e'){
                    $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Hubo un problema al guardar, intente nuevamente.</p></div>');
                    premsg();
                }
                else{
                    $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                    premsg(1);
                } 
            }
        });
    }  
}


function limpiar(){
    //$('#idpac').val(0);
    $('#fregistro').text('');
    $('#historial').empty();
    $('#examenes').empty();
    $('#imgqr').attr('src', '');
    limpiar2();
    limpiar_exam();
}
function limpiar_exam(){
    $('#previews').empty();
    $('#idexam').val(0);
    sceditor.instance(txtobserva).val("");
}

function guardar_his(){
    if ($('#idpac').val() == null || $('#idpac').val() ==0){
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, seleccione un paciente por favor.</p></div>');
        premsg();
    }else{
            var user=$('#txtus').val();
            var idpac = $('#idpac').val();
            var idins = $('#idins').val(); 
            var hiscli = $('#hiscli').val(); 
            var visihis =  $('#visihis').val(); 
            var valor ="";
            var unir = "";
            if (user>0 && idpac >0){
                for (var element=0; element <=5; element++){
                    var elemento = $("#zone"+element);
                    var jsonfin="";
                    var i = 0;
                    var value ="";
                    var textarea="";
                    valor='{"dropzone'+element+'":[{ ';
                    elemento.find('textarea,input,select').each(function() {
                        var object = this.id;
                        if (object.indexOf('txtmsg') > -1 ){
                            textarea = document.getElementById(object);
                            value = sceditor.instance(textarea).val();
                            jsonfin+='"'+i+'":"'+value+'",';
                            i++;
                        }else
                            if (object.length >0){
                                if ($(this).is(":checked"))
                                    jsonfin+='"'+i+'":"1",';
                                else
                                    jsonfin+='"'+i+'":"'+$(this).val()+'",';
                                i++;
                            } 
                    });
                    jsonfin= jsonfin.substring(0, jsonfin.length - 1);
                    jsonfin= valor + jsonfin +"}]}";
                    unir= unir + jsonfin + 'ª';
                }
                unir =unir.substring(0, unir.length - 1);
                var parametros = {'code': idpac,'institu': idins,'user': user,'hiscli': hiscli,'visihis': visihis,'valor':unir, 'what': 'grabar'};
                $.ajax({
                    type: 'post',
                    url: 'api/api_hiscli.php',
                    data: parametros,
                    success: function(response) {
                        if (response=='e'){
                            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Hubo un problema al guardar, intente nuevamente.</p></div>');
                            premsg();
                        }
                        else{
                            $('#visihis').val(response);
                            guardar_examen();
                            $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                            premsg();
                        } 
                    }
                });
            }else{
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione un especialista y un paciente.</p></div>');
                premsg();
            }
    }
}


function guardar_examen(){
    var idexam = $('#idexam').val();
    var value = sceditor.instance(document.getElementById('txtobserva')).val();
    if (idexam >0){
            var parametros = {'code': idexam,'valor':value, 'what': 'grabarexa'};
            $.ajax({
                type: 'post',
                url: 'api/api_hiscli.php',
                data: parametros,
                success: function(response) {

                }
            });
    }
}

function eliminar_his(){
    var idhisvi = $('#visihis').val();
    if (($('#idpac').val() == null || $('#idpac').val() ==0) || (idhisvi==null || idhisvi ==0)){
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, seleccione un paciente o un historial por favor.</p></div>');
        premsg();
    }else{
        if(confirm('¿Desea eliminar registro?')){
            var parametros = {'code': idhisvi,'what': 'eliminvi'};
            $.ajax({
                type: 'post',
                url: 'api/api_sigvi.php',
                data: parametros,
                success: function(response) {
                    if (response=='e'){
                        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Hubo un problema al eliminar, intente nuevamente.</p></div>');
                        premsg();
                    }
                    else{
                        $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                        premsg(1);
                    }
                
                }
            });
        }
    }
}

function get_paciente(id){
    var parametros = {'code': id,'what': 'pac'};
    $.ajax({
        type: 'post',
        url: 'api/api_hiscli.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key,value) {
                if (value.foto !=null && value.foto.length >5 ) $('#wizardPicturePreview').attr('src', value.foto); 
                else $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
                $('#txtnombres').val(value.nombres);
                $('#txtapellidos').val(value.apellidos);
                $('#edad').text(value.edad);
            }); 
        }
    });
}

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


function recuperar_campos(institu,especiali){
    var parametros = {'hdesde': institu, 'hhasta': especiali, 'permisis': '7'};
    $.ajax({
        data:parametros,
        url: 'api/configurar_api.php',
        type: 'POST',
        success: function (response) { 
            if (response){
                var obj = jQuery.parseJSON(response);
                var i=0;
                var contador=0;
                var elemento;
                var elemento1;
                var elemento2;
                var atexto = new Array();
                //limpiar();
                $.each(obj, function(key,value) {
                    elemento = $('#zone'+i);
                    if (typeof value === 'string') {
                        elemento1= document.getElementById('titulo'+i);
                        elemento2= document.getElementById('li'+i);
                        elemento1.innerHTML = value;
                        elemento2.classList.remove("hidden");
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
                                $el= $el + get_objeto(val1,val2,contador,agrup);
                                //elemento.append($el);
                                if (val1=='Textarea') atexto.push(contador); 
                                if (val1=='Number') $('#sgrafica').append($("<option>", {value: contador,text: i + "-" +val2}));
                                contador++;
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
                });
                for ( x in atexto)
                    crear_textarea(atexto[x]);
            }
        }
    });
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
        //select= '<select class="form-control" id="'+tipo+' '+i+'" name="'+tipo+' '+i+'">';
        select= '<select class="form-control" id="'+etiqueta+'" name="'+etiqueta+'">';
        for (var t=0; t< recorrer.length;t++){
            select = select +'<option value="'+recorrer[t]+'">'+recorrer[t]+'</option>';
        }
        select = select + '</select>';
        objeto = objeto + select;
    }else if (tipo =='Agrupar'){
        if (agrup>1) objeto = '</div></div>';
        objeto = objeto+ '<div class="panel panel-primary"><div class="panel-heading"> <span class="pull-left clickable"><h3 class="panel-title"> '+etiqueta+' </h3></span>'+
                        '<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span><br></div><div class="panel-body collapse">';
    }else if (tipo =='Check'){
         objeto = objeto +' <input type="checkbox" value="1" id="'+etiqueta+'" name="'+etiqueta+'" class="form-check-input checkbox-xl"> ';
    }else
        objeto = objeto +'<input class="form-control" type="'+tipo+'" id="'+etiqueta+'" name="'+etiqueta+'">';

    return objeto;
}

$(document).on('change','input[type="checkbox"]' ,function(e) {
    //console.log($(this).val());
});

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


function solicitar_exa(){
        mostra_ventana('Modalpedidos');
}

function vademecum(){
    var parametros = {'what': 'vade'};
    var i =0;
    $.ajax({
        type: 'post',
        url: 'api/api_hiscli.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key,value) {
                $('#vademecum').append('<li id="livademecum" class="list-group-item"><h6>' + value.nombreprod +'</h6></li>');
                i++;
            });
        }
    });

    $(function(){mostra_ventana('ModalVade');});
}



//GRAFICA
window.Apex = {
    chart: {
      foreColor: '#fff',
      toolbar: {
        show: false
      },
    },
    stroke: {
      width: 3
    },
    dataLabels: {
      enabled: false
    },
    /*tooltip: {
      theme: 'dark'
    },*/
    grid: {
      borderColor: "#ABDCFF",
      xaxis: {
        lines: {
          show: true
        }
      }
    }
  };
  

function generar_grafica(valor){
    texto=$('#sgrafica :selected').text();
    texto = texto.split("-");

    var idcli = $('#idpac').val();
    var hiscli = $('#hiscli').val(); 
    if ((idcli == null || idcli ==0) || (hiscli==null || hiscli ==0)){
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, seleccione un paciente o no tiene creada historia clinica.</p></div>');
        premsg();
        $("#sgrafica").val([]);
    }else{
        var parametros = {'code': idcli, 'hiscli':hiscli, 'what': 'grafica'};
        var i =0;
        var series= new Array();
        var labels = new Array();
        $.ajax({
            type: 'post',
            url: 'api/api_hiscli.php',
            data: parametros,
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                $.each(obj, function(key,value) {
                    var zona=0;
                        if (value.campo1 && Number(texto[0]) == zona){
                            var obj1 = jQuery.parseJSON(value.campo1);
                            series.push(cargar_parametros(zona,obj1,texto[1]));
                            labels.push(value.fecha);
                        }
                        zona++;
                        if (value.campo2 && Number(texto[0]) == zona){
                            var obj1 = jQuery.parseJSON(value.campo2);
                            series.push(cargar_parametros(zona,obj1,texto[1]));
                            labels.push(value.fecha);
                        }
                        zona++;
                        if (value.campo3 && Number(texto[0]) == zona){
                            var obj1 = jQuery.parseJSON(value.campo3);
                            series.push(cargar_parametros(zona,obj1,texto[1]));
                            labels.push(value.fecha);
                        }
                        zona++;
                        if (value.campo4 && Number(texto[0]) == zona){
                            var obj1 = jQuery.parseJSON(value.campo4);
                            series.push(cargar_parametros(zona,obj1,texto[1]));
                            labels.push(value.fecha);
                        }
                        zona++;
                        if (value.campo5 && Number(texto[0]) == zona){
                            var obj1 = jQuery.parseJSON(value.campo5);
                            series.push(cargar_parametros(zona,obj1,texto[1]));
                            labels.push(value.fecha);
                        }
                        zona++;
                        if (value.campo6 && Number(texto[0]) == zona){
                            var obj1 = jQuery.parseJSON(value.campo6);
                            series.push(cargar_parametros(zona,obj1,texto[1]));
                            labels.push(value.fecha);
                        }
                        zona++;
                });
                dibujar_grafica (texto[1],series,labels);
            }
        });
    }
}


function cargar_parametros(zona,obj1,name){
    var i=0;
    var valor="";
    $("#zone"+zona).find('textarea,input,select').each(function() {
        var object = this.id;
        if (object){
            if (object==name && this.type == 'number') {
                valor =   obj1['dropzone'+zona][0][i];
            }
            i++;
        }
    });
    return valor;
}

function dibujar_grafica(titulo,valores,labeles){
    //<div id="line-adwords"></div>
    $('#contentgraf').remove();
    $('#impresionG').append('<div class="box shadow mt-4" id="contentgraf"><div id="redrawing"></div></div>');

    var optionsLine = {
        chart: {
            toolbar: {
                show: true,
                offsetX: 0,
                offsetY: 0,
                tools: {
                  download: true,
                  selection: true,
                  zoom: true,
                  zoomin: true,
                  zoomout: true,
                  pan: true
                },
                autoSelected: 'zoom' 
              },
            height: 328,
            type: 'line'
        },
        stroke: {
          curve: 'smooth',
          width: 2
        },
        title: {
          text: titulo,
          align: 'left',
          offsetY: 25,
          offsetX: 20
        },
        subtitle: {
          text: ' ',
          offsetY: 55,
          offsetX: 20
        },
        markers: {
          size: 6,
          strokeWidth: 0,
          hover: {
            size: 9
          }
        },
        grid: {
          show: true,
          padding: {
            bottom: 0
          }
        },
        series: [{
            name: titulo,
            color: '#53B4D8',
            data:  valores
          }
        ],
        labels: labeles,
        xaxis: {
          tooltip: {
            enabled: false
          }
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          offsetY: -20
        }
      }
      
      var chartLine = new ApexCharts(document.querySelector('#redrawing'), optionsLine);
      chartLine.render();
}
  
  
  