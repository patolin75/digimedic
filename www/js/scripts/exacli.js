var textarea = document.getElementById('txtmsg');
var textarea1 = document.getElementById('txtmsg1');

sceditor.create(textarea, {
    format: 'bbcode',
    icons: 'monocons',
    style: 'js/editor/themes/content/default.min.css',
    locale: 'es',
    width: "100%",
    height: "100%",
    toolbar: "bold,italic,underline,strike,subscript,superscript|font,size,color,removeformat|cut,copy,pastetext|horizontalrule|date,time|print"
});   
sceditor.create(textarea1, {
    format: 'bbcode',
    icons: 'monocons',
    style: 'js/editor/themes/content/default.min.css',
    locale: 'es',
    width: "100%",
    height: "100%",
    toolbar: "bold,italic,underline,strike,subscript,superscript|font,size,color,removeformat|cut,copy,pastetext|horizontalrule|date,time|print"
});   
var theme = 'js/editor/themes/office.min.css';
//document.getElementById('theme-style').href = theme;
k=0;

$(function () {
    $('#examenes').click(function () {
        i=0;
        $('.slecttwo').on('change', function() {
            $('.slecttwo').not(this).prop('checked', false);
            if($(this).is(":checked")){
                if (i==0) cargar_examen($(this).val());
                i++;
                k=0;
            }
            if (k==0 && i==0) {
                if ($('#selexamen').val()==0) limpiar();
                limpiar_exam();
            }
        });
    });    
});

function cargar_examenes(id){
    var institu = $('#txtinst').val();
    var parametros = {'code': id,'what': 'exam','institu': institu};

    if(id>0) cargar_datos(id); else {limpiar();  limpiar_exam();}

    $('#examenes').empty();
    $.ajax({
        type: 'post',
        url: 'api/exa_api.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key,value) {
                $('#examenes').append('<li id="liexamenes" class="list-group-item"> <input class="form-check-input me-1 slecttwo" type="checkbox" value="'+ value.id +'"> Cod: '+value.id+' Fecha: ' + value.fechaped +'</li>');
            }); 
        }
    });
}

function cargar_datos(id){
    var parametros = {'code': id,'what': 'datos'};
    $.ajax({
        type: 'post',
        url: 'api/exa_api.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            var i=0;
            $("#frmdatos input,select").each(function () {
                if ($(this).attr("id")!='selexamen'){
                    if (i==2)
                    if (obj[0][i] !=null && obj[0][i].length >5 ) $('#wizardPicturePreview').attr('src', obj[0][i]); 
                        else $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
                    else if(i==17)
                        $('#fregistro').text(obj[0][i]);
                    else 
                        $(this).val(obj[0][i]);
                    i++;
                }
            });
        }
    });
}

function cargar_examen(id){
    var institu = $('#txtinst').val();
    var parametros = {'code': id,'what': 'exm1','institu': institu};
    limpiar_exam();
    $.ajax({
        type: 'post',
        url: 'api/exa_api.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            i=0;
            $.each(obj, function(key,value) {
                idc=value.idcliente;
                idex=value.idexa;
                $('#lblrecepta').text(value.nombrealiza);
                $("#idexma").val(idex);
                $("#idespe").val(value.idespe);
                $("#txtapellidoses").val(value.apellidos);
                $("#txtnombreses").val(value.nombres);
                $("#dtxttelefonoes").val(value.telefono);
                $("#txtmailes").val(value.mail);
                sceditor.instance(textarea1).val(value.detalle);
                i++;
            }); 

            if (i>0 ){
                cargar_datos(idc);
                cargar_resultados(idex);
            }
        }
    });
}

function carga_med(object){
    if (object.value ==0){
        $("#txtapellidoses").val("");
        $("#txtnombreses").val("");
        $("#dtxttelefonoes").val("");
        $("#txtmailes").val("")
        $("#idespe").val(0);
    }else{
        var parametros = {'code': object.value,'what': 'especi'};
        $.ajax({
            type: 'post',
            url: 'api/exa_api.php',
            data: parametros,
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                $.each(obj, function(key,value) {
                    $('#idespe').val(value.id);
                    $("#txtapellidoses").val(value.apellidos);
                    $("#txtnombreses").val(value.nombres);
                    $("#dtxttelefonoes").val(value.telefono);
                    $("#txtmailes").val(value.mail);
                }); 
            }
        });
    }
}

function cargar_resultados(id){
    var parametros = {'code': id,'what': 'result'};
    var valores =  new Array(); 
    var host = window.location.host;
    var path = window.location.pathname;
    var URLactual = 'http://'+host+path;

    eliminados.length ==0
    contador=0;
    $('#previews').empty();
    $.ajax({
        type: 'post',
        url: 'api/exa_api.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key,value) {
                if (value.archivo1){
                    var ext = value.archivo1.split('.').pop();
                    contador++;
                    agregar_prev(contador,value.archivo1);
                    presentar_img('',contador,ext,URLactual+value.archivo1);
                    document.getElementById('linkfile'+contador).href = URLactual + value.archivo1;
                    document.querySelector('#progress-bar1').style.width = 100 + '%'
                }else eliminados[1]=1;
                if (value.archivo2){
                    var ext = value.archivo2.split('.').pop();
                    contador++;
                    agregar_prev(contador,value.archivo2);
                    presentar_img('',contador,ext,URLactual+value.archivo2);
                    document.getElementById('linkfile'+contador).href = URLactual + value.archivo2;
                    document.querySelector('#progress-bar2').style.width = 100 + '%'
                }else eliminados[2]=2;
                if (value.archivo3){
                    var ext = value.archivo3.split('.').pop();
                    contador++;
                    agregar_prev(contador,value.archivo3);
                    presentar_img('',contador,ext,URLactual+value.archivo3);
                    document.getElementById('linkfile'+contador).href = URLactual + value.archivo3;
                    document.querySelector('#progress-bar3').style.width = 100 + '%'
                }else eliminados[3]=3;
                if (value.archivo4){
                    var ext = value.archivo4.split('.').pop();
                    contador++;
                    agregar_prev(contador,value.archivo4);
                    presentar_img('',contador,ext,URLactual+value.archivo4);
                    document.getElementById('linkfile'+contador).href = URLactual + value.archivo4;
                    document.querySelector('#progress-bar4').style.width = 100 + '%'
                }else eliminados[4]=4;
            
                sceditor.instance(textarea).val(value.resultados);
            }); 
        }
    });
}


function limpiar(){
    $('#fregistro').text("");
    $("#wizardPicturePreview").attr("src","images/noimagen.jpg");
    $("#frmdatos input,select").each(function () { cambiar(this); });
    $('#idpac').val("0");
}

function limpiar_exam(si=0){
    //div = document.getElementById('presentation');
    //div.style.display = 'none';
    $('#previews').empty();
    sceditor.instance(textarea1).val("");
    sceditor.instance(textarea).val("");
    $('#lblrecepta').text("");
    $("#txtapellidoses").val("");
    $("#txtnombreses").val("");
    $("#dtxttelefonoes").val("");
    $("#txtmailes").val("")
    $("#idexma").val(0);
    $("#idespe").val(0);
    if (si==1) $('.slecttwo').not(this).prop('checked', false);
}

function cambiar(objeto){
    if ($(objeto).attr('type') =='text' || $(objeto).attr('type') =='date' || $(objeto).attr('type') =='file')
        $(objeto).val("");
    else
        $(objeto).val(0);
}

function guardar_datos(){
    var valores =  new Array(); 
    valores.push({"id":"guardar_pac","name":"0"});
    $("#frmdatos").find('input,select').each(function() {valores.push({"id":$(this).attr("name"),"name":$(this).val()});});
    var parametros = {'code': JSON.stringify(valores),'what': 'almac'};
    $.ajax({
        type: 'post',
        url: 'api/exa_api.php',
        data: parametros,
        success: function(response) {
            if (response=='r'){
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, numero de identificacion ya existe.</p></div>');
                premsg();
            }else if (response =='f'){
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revise la pestaña informacion general que no contenga vacios.</p></div>');
                premsg(); 
            }else{
                guardar_exmane(response);
                
            }
        }
    });
}

function guardar_exmane(id){
    var valores =  new Array();
    $("#frmexsol").find('input').each(function() { if ($(this).attr("name")) valores.push({"id":$(this).attr("name"),"name":$(this).val()})  });
    valores.push({"id":"txtmsg","name":sceditor.instance(textarea1).val()});
    valores.push({"id":"idcliente","name":id});
    valores.push({"id":"idistitu","name":$('#txtinst').val()});
    valores.push({"id":"txtmsg1","name":sceditor.instance(textarea).val()});
    var parametros = {'code': JSON.stringify(valores),'what': 'almaex'};

    $.ajax({
        type: 'post',
        url: 'api/exa_api.php',
        data: parametros,
        success: function(response) {
            console.log(response);
            if (response =='f'){
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revise la pestaña solicitante.</p></div>');
                premsg(); 
            }else{
                $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                premsg(1);
            }
        }
    });
}


function subir_imagen(file,valor){
    var datos = new FormData();
    datos.append('file', file.files[0]);
    datos.set('what','filesub');
    datos.set('institu',$('#txtinst').val());
    datos.set('code',$('#idexma').val());
    datos.set('campo',valor);
    var host = window.location.host;
    var path = window.location.pathname;
    var URLactual = 'http://'+host+path;


    $.ajax({
        url: 'api/exa_api.php',
        method:'POST',
        dataType: "html",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
           if (response =='f'){
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado.</p></div>');
                premsg();
           }else{
                $('#file'+valor).val(response);
                document.getElementById('linkfile'+valor).href = URLactual + response;
                $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                premsg();
           }
        },
    });
}

function eliminar_exa(){
    if ($('#idexma').val() >0){
        if(confirm('¿Desea eliminar registro?')){
            id=$('#idexma').val();

            var parametros = {'code': id,'what': 'eliexa'};

            $.ajax({
                type: 'post',
                url: 'api/exa_api.php',
                data: parametros,
                success: function(response) {
                    if (response =='e'){
                        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revise la pestaña solicitante.</p></div>');
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

function eliminar_img(idimg){
    if(confirm('¿Desea eliminar registro?')){
        var id=$('#idexma').val();
        var archivo = $('#file'+idimg).val();

        var parametros = {'code': id,'what': 'eliima','campo': idimg, 'institu': archivo};
        $.ajax({
            type: 'post',
            url: 'api/exa_api.php',
            data: parametros,
            success: function(response) {
                if (response =='f'){
                    $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado.</p></div>');
                    premsg(); 
                }else{
                    $("#template"+idimg).remove();
                    eliminados[idimg]=idimg;
                    $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                    premsg();
                }
            }
        });
    }
}



//ESTO ES PARA LOS ARCHIVOS
var preview = document.querySelector('#previews');
var eliminados = new Array();
var contador=0;
var totalima=4;

$(document).ready(function() {
    $('.fileinput-button').on('click', function() {
        $("#fileexa").click();
    });
});

$("#fileexa").change(function(){
    readURL(this);
});

function readURL(input) {
    const MAXIMO_TAMANIO_BYTES = 2700000; //3MB
    var ext = input.files[0].name.split('.').pop();
		ext = ext.toLowerCase();
    if (['jpg','jpeg', 'png', 'svg', 'doc','pdf','docx'].indexOf(ext) >= 0) {
                if (input.files[0].size <= MAXIMO_TAMANIO_BYTES) {
                        if (input.files && input.files[0]) {
                            if (eliminados.length ==0){
                                contador=contador+1
                                valor=contador;
                            }
                            else{
                                var si=0;
                                for(j=0;j<=eliminados.length;j++){
                                    if (eliminados[j]>0){
                                        valor = eliminados[j];
                                        j=eliminados.length;
                                    } 
                                }
                                for(j=0;j<=eliminados.length;j++)
                                    if (eliminados[j]>0 && eliminados[j].length >0) si++;
                                if (si==0) eliminados.length = 0;
                            }
                            if (contador <= totalima){
                                    agregar_prev(valor,input.files[0].name);
                                    var reader = new FileReader();
                                    reader.addEventListener('progress',e => document.querySelector('#progress-bar'+valor).style.width = (e.loaded/e.total) * 100 + '%');
                                    reader.addEventListener('error',err => {$('#msgerror'+valor).val(err)});

                                    reader.onload = function (e) {
                                        presentar_img(e,valor,ext);
                                        subir_imagen(input,valor);
                                    };
                                    reader.readAsDataURL(input.files[0]);
                            }else{
                                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Máximo permitido 5 archivos.</p></div>');
                                premsg();
                            }
                        }
                
                }else{
                    $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Tamaño máximo de imágen 3 MB.</p></div>');
                    premsg();
                }
    }
    else{
        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Archivo no soportado.</p></div>');
        premsg();
    }
}

function agregar_prev(id,name){
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
    element='<div id="template" class="file-row"><div class="col-xs-4 col-lg-4">' +
            '<span class="preview" style="width:180px;height:180px;"><a id="linkfile'+id+'" href="" target="_blank"><img class="prevdoc_exa" style="width:180px;height:180px;" id="prevdoc_exa'+id+'"/></a>'+
             '<input type="text" class="form-control" id="file'+id+'" name="file'+id+'" value="'+name+'" readonly></span><br/><strong class="error text-danger" id="msgerror'+id+'" data-dz-errormessage></strong><div class="progress" id="progress'+id+'">'+
            '<div class="progress-bar" id="progress-bar'+id+'" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:20%"></div>'+
            '</div><button class="btn btn-danger" id="btn-danger'+id+'" onclick="eliminar_img('+id+')"><i class="icon-trash fa fa-trash"></i> </button></div></div></div>';
    return element;
}

function premsg(recar=""){
    mostra_ventana('frmmsg');
    if (recar)
        setTimeout(function() { $(function(){$("#frmmsg").modal("hide");  window.location.reload();}); }, 4000);
    else
        setTimeout(function() { $(function(){$("#frmmsg").modal("hide");}); }, 4000);
}
