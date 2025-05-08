;(function($){
    'use strict';
    let $document       = $(document),
        direc           = 'api/configurar_api.php',
        $window         = $(window),
        elemento        = "#dropzone0",
        elemento1       = "#colum0",
        elemento2       = "#colum1",
        elemento3       = "#colum2";
        
    $document.on('click','.list-group .list-group-item', function (e){
        $(this).toggleClass('active');
    });
    
    $document.on('change','#filtroa', function (e){
        cargar_listaemp($(this),$('#filtrou'),3);
    });

    $document.on('change','#filtrou', function (e){
        cargar_accesos($(this).val());
    });

    $document.on('change','#filtro', function (e){
        cargar_listaemp($(this),$('#filtroe'),4);
    });

    $document.on('change','#filtroe', function (e){
        listar_costos($(this).val());
    });

    $document.on('change','#filtroh', function (e){
        cargar_listaemp($(this),$('#filtroeeh'),4);
    });
   
    $document.on('change','#filtroeeh', function (e){
        listar_horarios($(this).val());
    });

    $document.on('change','#cmbinst', function (e){
        cargar_especialidades($(this),$('#cmbespecl'),5);
    });
    
    $document.on('change','#cmbespecl', function (e){
        recuperar_campos($(this));
    });
    
    $document.on('click','.bguardar', function (e){
        guardar_campos();
    });
    
    $document.on('change','#cmbinstex', function (e){
        cargar_especialidades($(this),$('#cmbespeclex'),5);
    });

    $document.on('change','#cmbespeclex', function (e){
        recuperar_camposex($(this));
    });
   
    

    $('.list-arrows button').click(function () {
        var $button = $(this), 
            actives = '',
            id      = 0,
            texto   = '';

        if ($button.hasClass('move-left')) {
            actives = $('.list-right ul li.active');    
           
            actives.clone().appendTo('.list-left ul');
            actives.remove();

            $.each(actives, (index, value) =>{
                texto=$("<li>").html(value).text();
                id = $(value).attr('id');
                accion('del',id)
            });

        } else if ($button.hasClass('move-right')) {
            actives = $('.list-left ul li.active');
            actives.clone().appendTo('.list-right ul');
            actives.remove();

            $.each(actives, (index, value) =>{
                texto=$("<li>").html(value).text();
                id = $(value).attr('id');
                accion('add',id)
            });

        }
    });

    //OTRA AGENDAS
    $('.list-arrowsa button').click(function () {
        var $button = $(this), actives = '',
            id=$('#filtrou').val();

        if ($button.hasClass('move-left0')) {
            if (id >0){
                actives = $('.list-right0 ul li.active');    
                actives.clone().appendTo('.list-left0 ul');
                actives.remove();
            }
        } else if ($button.hasClass('move-right0')) {
            if (id >0){
                actives = $('.list-left0 ul li.active');
                actives.clone().appendTo('.list-right0 ul');
                actives.remove();
            }
        }
       
        if (id >0){
            var lista="";
            $("#agendar li").each(function(){
        	    lista = $(this).attr('id')+','+lista;
        	});
            actualiza_permisos(id,lista);
        }
    });
    //FIN OTRAS AGENDAS

    $('.list-arrowsb button').click(function () {
        var $button = $(this), actives = '';
        if ($button.hasClass('move-left1')) {
            //ELIMINAR
            actives = $('.list-right1 ul li.active');    
            actives.remove();
            $.each(actives, (index, value) =>{
                //texto=$("<li>").html(value).text();
                accions_cos('del',$(value).attr('id'));
            });
        } else if ($button.hasClass('move-right1')) {
            //AGREGAR
           accions_cos('add');
        }
    });

    //horarios
    $('.list-arrowsh button').click(function () {
        var $button = $(this), actives = '';
        if ($button.hasClass('move-lefth')) {
            //ELIMINAR
            actives = $('.list-righth ul li.active');    
            actives.remove();
          
            $.each(actives, (index, value) =>{
                let texto=$("<li>").html(value).text(),
                    id = $(value).attr('id');
                accions_ho('delh',id);
            });
           
        } else if ($button.hasClass('move-righth')) {
            //AGREGAR
             accions_ho('addh');
        }
    });


    function cargar_accesos(code){
        let insti = $('#filtroa').val();
        if (code >0){
            let parametros = {'code': code, 'insti': insti, 'permisis': '1'};
            $.ajax({
                data:parametros,
                url: direc,
                type: 'POST',
                success: function (response) {
                    $('#agendar').empty();
                    let obj = jQuery.parseJSON(response);
                    $.each(obj, function(key,value) {
                        $("#agendar").append('<li id="'+value.id_usuarios+'" class="list-group-item">'+value.nombres_usuarios+' '+value.apellidos_usuarios+'</li>');
                    });
                }
            }); 
            cargar_izquierda(code);
        }else{
            $('#agendar').empty();
            $('#agendarl').empty();
        }
    
    }

    function cargar_izquierda(code){
        let insti = $('#filtroa').val();
        var parametros = {'code': code, 'insti': insti, 'permisis': '2'};
        $.ajax({
            data:parametros,
            url: direc,
            type: 'POST',
            success: function (response) {
                $('#agendarl').empty();
                let obj = jQuery.parseJSON(response);
                $.each(obj, function(key,value) {
                    $("#agendarl").append('<li id="'+value.id_usuarios+'" class="list-group-item">'+value.nombres_usuarios+' '+value.apellidos_usuarios+'</li>');
                });
            }
        }); 
    }

    function cargar_listaemp(objeto,lista,permi){  //esto es para los combos
        var code = objeto.val();
        var parametros = {'code': code, 'permisis': permi};
        $('#valores').empty();
        $('#agendarl').empty();
        $('#agendar').empty();
        $('#valoresh').empty();
        
        if (code>0){
            $.ajax({
                data:parametros,
                url: direc,
                type: 'POST',
                success: function (response) {
                    lista.empty();
                    lista.append('<option value="0">Seleccione</option>');
                    var obj = jQuery.parseJSON(response);
                    $.each(obj, function(key,value) {
                        lista.append('<option value="'+value.id_usuarios+'">'+value.nombres_usuarios+' '+value.apellidos_usuarios+'</option>');
                    });
                }
            });
        }else{
            lista.empty();
            $('#agendar').empty();
            $('#agendarl').empty();   
        }
    }

    function actualiza_permisos(code, listado){
        var parametros = {'code': code, 'listado': listado, 'permisi': '1'};
        $.ajax({
            data:parametros,
            url: direc,
            type: 'POST',
            success: function (response) { 
                
            }
        }); 
    }

    function accions_cos(accion,code=""){
        var si=0,
            id=$('#filtroe').val();

        if (id > 0 && accion=="del"){
            var parametros = {'code': code, 'accionc': accion};
            si=1;
        }
        if (id > 0 && accion=="add"){
            let nombre=$('#nconsulta').val(),
                valor=$('#valorc').val(),
                observa=$('#nobserva').val();

            if (nombre.length > 0 && valor.length >0){
                var parametros = {'code': id, 'nombre': nombre,'valor': valor, 'observa' : observa, 'accionc': accion};
                si=1;
            }
        }
        if (si==1){
            $.ajax({
                data:parametros,
                url: direc,
                type: 'POST',
                success: function (response) { 
                    listar_costos();
                    $('#nconsulta').val("");
                    $('#valorc').val("");
                    $('#nobserva').val("");
                }
            }); 
        }
    }

    function accions_ho(accion,code=""){
        var si=0,
            id=$('#filtroeeh').val();

        if (id > 0 && accion=="delh"){
            var parametros = {'code': code, 'accionc': accion};
            si=1;
        }
        if (id > 0 && accion=="addh"){
            let hdesde=$('#thoradesde').val(),
                hhasta=$('#thorahasta').val(),
                observa=$('#tobserva').val();

            if (hdesde.length > 0 && hhasta.length >0){
                var parametros = {'code': id, 'hdesde': hdesde,'hhasta': hhasta, 'observa' : observa, 'accionc': accion};
                si=1;
            }
        }
        if (si==1){
            $.ajax({
                data:parametros,
                url: direc,
                type: 'POST',
                success: function (response) { 
                    listar_horarios();
                    $('#thoradesde').val("");
                    $('#thorahasta').val("");
                    $('#tobserva').val("");
                }
            }); 
        }
    }

    function listar_costos(){
        let codigo = $('#filtroe').val();
        
            var parametros = {'code': codigo};
            $.ajax({
                data:parametros,
                url: direc,
                type: 'POST',
                success: function (response) { 
                    $('#valores').empty();
                    var obj = jQuery.parseJSON(response);
                    $.each(obj, function(key,value) {
                        $("#valores").append('<li id="'+value.id_tipocitas+'" class="list-group-item">'+value.tipocitas_nombre+' USD: '+value.tipocitas_valor+'</li>');
                    });
                }
            }); 
    }

    function listar_horarios(){
        let codigo = $('#filtroeeh').val();
        
            var parametros = {'code': codigo,'accionc': 'chorario'};
            $.ajax({
                data:parametros,
                url: direc,
                type: 'POST',
                success: function (response) { 
                    $('#valoresh').empty();
                    var obj = jQuery.parseJSON(response);
                    $.each(obj, function(key,value) {
                        $("#valoresh").append('<li id="'+value.id_horarioatencion+'" class="list-group-item">'+value.hoariosatencion_horainicio+' - '+value.horarioatencion_horahasta+'</li>');
                    });
                }
            }); 
    }

    function accion(accion,codigo) 
    {
            var parametros = {'code': codigo, 'accion': accion};
            $.ajax({
                data:parametros,
                url: direc,
                type: 'POST',
                success: function (response) { 
                
                }
            });
    }

    function cargar_especialidades(objeto,lista,permi){
        limpiar();
        var parametros = {'code': objeto.val(), 'permisis': permi};
        $.ajax({
            data:parametros,
            url: direc,
            type: 'POST',
            success: function (response) { 
                lista.empty();
                var obj = jQuery.parseJSON(response);
                lista.append('<option value="0">seleccione</option>');
                $.each(obj, function(key,value) {
                    lista.append('<option value="'+value.id+'">'+value.nombre+'</option>');
                });
            }
        });
    }

    function guardar_campos(){
        var total = 4;
        if ($('#cmbinst').val() >0 && $('#cmbespecl').val()>0){
            var valor ="";
            var jsonfin="{";

            for (let j=0;j<=total;j++){
                if ($('#titulo'+j).val().length >0){
                    var i = 0;
                    var json ="";
                    $("#dropzone" + j).find('input,label').each(function() {
                        if ($(this).val()) valor = $(this).val(); else valor= $(this).html();
                        json= json + '"'+i+'":"' + valor +'",';
                        i++;
                    });
                    if (i>0){
                        json = json.substring(0, json.length - 1);
                    jsonfin= jsonfin+'"titulo'+j+'":"'+$('#titulo'+j).val()+'","dropzone'+j+'" :[{'+json+'}],';
                    }
                }
            }
            jsonfin = jsonfin.substring(0, jsonfin.length - 1);
            if(jsonfin.length>0) jsonfin= jsonfin +"}";

            var institu = $('#cmbinst').val();
            var especiali = $('#cmbespecl').val();
            var parametros = {'hdesde': institu, 'hhasta': especiali, 'code': jsonfin, 'permisis': '6'};
            $.ajax({
                data:parametros,
                url: direc,
                type: 'POST',
                success: function (response) { 
                    if (response ==1)
                        $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                    else
                        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>No se pudo ejecutar la tarea, por favor revise los valores.</p></div>');         
                    premsg();
                }
            });

        }else{
            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Seleccione una institucion y especialidad.</p></div>');
            premsg();
        }
    }

    function recuperar_campos(objeto){
        var institu = $('#cmbinst').val();
        var especiali = $(objeto).val();

        var parametros = {'hdesde': institu, 'hhasta': especiali, 'permisis': '7'};
        $.ajax({
            data:parametros,
            url: direc,
            type: 'POST',
            success: function (response) { 
                limpiar();
                if (response){
                    var obj = jQuery.parseJSON(response);
                    var i=0;
                    var elemento;
                    $.each(obj, function(key,value) {
                        elemento = $('#dropzone'+i);

                        if (typeof value === 'string') {
                            $('#titulo'+i).val(value);
                        }else{
                            var j =0;
                            var val1="";
                            var val2="";
                            var $el="";
                            for (let valor in value[0]){
                                //console.log("La clave es " + valor+ " y el valor es " + value[0][valor]);
                                if (j==0) {
                                    val1 = value[0][valor];
                                }
                                else{
                                    val2 = value[0][valor];
                                    if (val1 == 'Agrupar')
                                        var $el = $('<div class="well drop-item"> <span class="glyphicon glyphicon glyphicon-chevron-down"></span><label>' + val1 + '</label>: <input class="campo" placeholder="Titulo de agrupamiento" value="'+val2+'"/></div>');
                                    else
                                        $el= $('<div class="drop-item"><details><label>' + val1 + '</label><div><summary>Detalle del campo: (nombre del campo, si es catalogo elementos separados por coma)</summary><input class="form-control campo" placeholder="Ejem: Peso: Peso1, Peso2, Peso3" value="'+val2+'" type="text"/></div></details></div>');
                                    $el.append($('<button type="button" class="btn btn-default btn-xs remove"><span class="glyphicon glyphicon-trash"></span></button>').click(function () { $(this).parent().detach(); }));
                                    elemento.append($el);
                                }
                                j++;
                                if (j>1) j=0;
                            }
                            i++;
                        }
                        
                    });
                }
            }
        });
    }


    function recuperar_camposex(objeto){
        limpiar_ex();
    }

    function limpiar(){
        for (let i=0;i<=4;i++){
            $("#dropzone"+i).empty();
            $("#titulo"+i).val("");
        }
    }

    function limpiar_ex(){
        for (let i=0;i<=3;i++){
            $("#colum"+i).empty();
        }
    }


    //PARA ARMAR LA HISTORIA CLINICA//
    $('.tabh').click(function(){
        var href = $(this).attr('href');
        dragable(href);
    })

    function dragablexa(href){
        elemento =href;
        $('.drag').draggable({ 
            appendTo: 'body',
            helper: 'clone'
        });

        $(elemento).droppable({
        //activeClass: 'active',
            hoverClass: 'hover',
            accept: ":not(.ui-sortable-helper)",
            drop: function (e, ui) {
                if (ui.draggable.text() == 'Agrupar')
                    var $el = $('<div class="well drop-item"> <span class="glyphicon glyphicon glyphicon-chevron-down"></span><label>' + ui.draggable.text() + '</label>: <input class="campo" placeholder="Titulo de agrupamiento"/></div>');
                else
                    var $el = $('<div class="drop-item"><details><label>' + ui.draggable.text() + '</label><div><summary>Detalle del campo: (nombre del campo, si es catalogo elementos separados por coma)</summary><input class="form-control campo" placeholder="Ejem: Peso: Peso1, Peso2, Peso3" type="text"/></div></details></div>');
                $el.append($('<button type="button" class="btn btn-default btn-xs remove"><span class="glyphicon glyphicon-trash"></span></button>').click(function () { $(this).parent().detach(); }));
                $(this).append($el);
        }
        }).sortable({
        items: '.drop-item',
        sort: function() {
            $( this ).removeClass( "active" );
        }
        });
    }

    function dragable(href){
        elemento =href;
        $('.drag').draggable({ 
            appendTo: 'body',
            helper: 'clone'
        });

        $(elemento).droppable({
        //activeClass: 'active',
            hoverClass: 'hover',
            accept: ":not(.ui-sortable-helper)",
            drop: function (e, ui) {
                if (ui.draggable.text() == 'Agrupar')
                    var $el = $('<div class="well drop-item"> <span class="glyphicon glyphicon glyphicon-chevron-down"></span><label>' + ui.draggable.text() + '</label>: <input class="campo" placeholder="Titulo de agrupamiento"/></div>');
                else
                    var $el = $('<div class="drop-item"><details><label>' + ui.draggable.text() + '</label><div><summary>Detalle del campo: (nombre del campo, si es catalogo elementos separados por coma)</summary><input class="form-control campo" placeholder="Ejem: Peso: Peso1, Peso2, Peso3" type="text"/></div></details></div>');
                $el.append($('<button type="button" class="btn btn-default btn-xs remove"><span class="glyphicon glyphicon-trash"></span></button>').click(function () { $(this).parent().detach(); }));
                $(this).append($el);
        }
        }).sortable({
        items: '.drop-item',
        sort: function() {
            $( this ).removeClass( "active" );
        }
        });
    }

    $(document).ready(function() {
        $('.fileinput-button').on('click', function() {
            $("#fileexa").click();
        });
    });

    $("#fileexa").change(function(){
        readURL(this);
    });

    function readURL(input) {
        var ext = input.files[0].name.split('.').pop();
            ext = ext.toLowerCase();
        if (['isco'].indexOf(ext) >= 0) {    
            var reader = new FileReader();
            reader.readAsText(input.files[0]);
            reader.onload = function (e) {
                recupera_formato(e);
            }
        }
        else{
            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Archivo no soportado.</p></div>');
            premsg();
        }
    }

    function recupera_formato(response){
        var obj = jQuery.parseJSON(response.target.result);
        var i=0;
        var elemento;
        $.each(obj, function(key,value) {
                elemento = $('#dropzone'+i);
            if (typeof value === 'string') {
                $('#titulo'+i).val(value);
            }else{
                var j =0;
                var val1="";
                var val2="";
                var $el="";
                for (let valor in value[0]){
                    if (j==0) {
                        val1 = value[0][valor];
                    }
                    else{
                        val2 = value[0][valor];
                        if (val1 == 'Agrupar')
                            $el = $('<div class="well drop-item"> <span class="glyphicon glyphicon glyphicon-chevron-down"></span><label>' + val1 + '</label>: <input class="campo" placeholder="Titulo de agrupamiento" value="'+val2+'"/></div>');
                        else
                            $el= $('<div class="drop-item"><details><label>' + val1 + '</label><div><summary>Detalle del campo: (nombre del campo, si es catalogo elementos separados por coma)</summary><input class="form-control campo" placeholder="Ejem: Peso: Peso1, Peso2, Peso3" value="'+val2+'" type="text"/></div></details></div>');
                        $el.append($('<button type="button" class="btn btn-default btn-xs remove"><span class="glyphicon glyphicon-trash"></span></button>').click(function () { $(this).parent().detach(); }));
                        elemento.append($el);
                    }
                    j++;
                    if (j>1) j=0;
                }
                i++;
            }
        });
    }


    $window.on('load', function() {
        if (navigator.userAgent.indexOf("Firefox") <=-1 ){
            dragable(elemento);
            dragablexa(elemento1);
            dragablexa(elemento2);
            dragablexa(elemento3);
        }
    });

    /* PARA FIREFOX*/
    window.onpageshow = function () {
        if (navigator.userAgent.indexOf("Firefox") >-1 ){
            dragable(elemento);
            dragablexa(elemento1);
            dragablexa(elemento2);
            dragablexa(elemento3);
        }
    };
})(jQuery);