var institu =$('#valtmp').val();
var vertodo =$('#vertodo').val();
var codus =$('#codus').val();
var code=""
//var busco=0;

$(document).ready(function(){
        $('#suggestions').fadeOut(1000);
        $("#txtapellidos").blur(function(){
            $('#suggestions').fadeOut(1000);
        });
        $('#txtapellidos').on('keyup', function() {
            var dataString = $('#txtapellidos').val();
            if (dataString.length > 2 && event.keyCode != 9){ // && busco==0){
                var parametros = {'code': dataString, 'institu': institu.trim(), 'acc': 'sear'};
                $.ajax({
                    type: 'post',
                    url: "api/api_calendar.php",
                    data: parametros,
                    success: function(data) {
                        if (data.length > 1){
                            $('#suggestions').fadeIn(1000).html(data);
                        }else{
                            $('#suggestions').fadeOut(1000);
                        }
                    }
                });
            }else{
                $('#suggestions').fadeOut(1000);
            }
        });

        $("#txtced").blur(function(){
            cargar_cliente($(this).val(),2)
            $('#suggestions').fadeOut(100);
	    });
       
});

function cargar_cliente(id,tipo){
    var parametros = {'code': id, 'acc': 'cargc', 'vertodo': tipo};
    $.ajax({
        data:parametros,
        url: 'api/api_calendar.php',
        type: 'POST',
        success: function (response) { 
            var obj = jQuery.parseJSON(response);
            if (obj){
                var i=0;
                var j=0;
                $("#txtced").css("background-color", "#FFFFCC");
                $("#frmcitas input").each(function () {
                    if (j >0 && j<6){
                        $(this).val(obj[0][i]);
                        i++;
                    }
                    j++;
                });
                //busco=1;
                $('#suggestions').fadeOut(100);
            }//else busco=0;
        }
    });  
}

function cargar_calendar(boton1,boton2,boton3){
    var hoy = new Date();
    document.addEventListener('DOMContentLoaded', function() {
            var initialLocaleCode = 'es';
            var calendarEl = document.getElementById('calendar'); 

            var calendar = new FullCalendar.Calendar(calendarEl, { 
                headerToolbar: {left: 'prev next today', center: 'title', right: 'resourceTimelineDay,resourceTimelineThreeDays,timeGridWeek,dayGridMonth'},
                allDayText: 'hora',
                initialDate: hoy,
                editable: true,
                height: 'auto',
                locales: 'es',
                //schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                locale: initialLocaleCode, navLinks: true, businessHours: false, allDaySlot: true, 
                views: {
                    resourceTimelineThreeDays: {
                      type: 'resourceTimeline',
                      duration: { days: 3 },
                      buttonText: '3 días'
                    }
                  },
                  resourceAreaHeaderContent: 'Especialistas',
                  resourceLabelDidMount: function(arg) {
                    var resource = arg.resource;
            
                    arg.el.addEventListener('click', function() {
                        if ($.isNumeric(resource.id)){
                            horarios_trabajo(resource.id);
                            mostra_ventana('frmhorarios');
                        }
                    });
                  },
                initialView: 'resourceTimelineDay',
                editable: false, selectable: true,businessHours: true, dayMaxEvents: true,

                select: function(arg) {
                    //busco=0;
                    var resource = arg.resource;
                    fechaini=arg.start.getFullYear() + "-"+ (arg.start.getMonth()+1) +"-"+arg.start.getDate();
                    fechafin= arg.end.getFullYear() + "-"+ (arg.end.getMonth()+1) +"-"+arg.end.getDate();
                    fechahoy=hoy.getFullYear() + "-"+ (hoy.getMonth()+1) + "-"+ hoy.getDate();
                    if ($.isNumeric(resource.id) && resource.id>0){
                        if (arg.start < hoy) {
                            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>No se pueden generar reservas en fechas pasadas!</p></div>');
                            premsg();
                            calendar.unselect();
                        }
                        else if (fechaini != fechafin) {
                            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Puede seleccionar varias horas, no un dia completo</p></div>');
                            premsg();
                            calendar.unselect();
                        }
                        else{
                            $("#bguardar").show();
                            limpiar();
                                var mes = arg.end.getMonth()+1; 
                                var dia = arg.end.getDate(); 
                                var ano = arg.end.getFullYear(); 
                                if(dia<10)
                                    dia='0'+dia; 
                                if(mes<10)
                                    mes='0'+mes  

                            if (arg.start.getMinutes() ==0) minutei="00"; else minutei =arg.start.getMinutes();
                            if (arg.end.getMinutes() ==0) minutef="00"; else  minutef=arg.end.getMinutes()
                            if (arg.start.getHours() <10) hora = "0"+arg.start.getHours(); else hora=arg.start.getHours();
                            if (arg.end.getHours() <10) horaf = "0"+arg.end.getHours(); else horaf=arg.end.getHours();
                            $('#txtfechacita').val(ano+"-"+mes+"-"+dia);
                            cargar_costos(resource.id);
                            $('#idespecialista').val(resource.id);
                            $('#txtespecialista').val(resource.title);
                            $('#txthorai').val(hora+":"+minutei);
                            $('#txthoraf').val(horaf+":"+minutef);
                            mostra_ventana('ModalEmpresa');
                           /* calendar.addEvent({
                                resourceId: resource.id,
                                title: 'cita',
                                start: arg.start,
                                end: arg.end,
                                allDay: false,
                                color: 'yellow'
                            });*/
                        }
                    }
                },

                eventClick: function(info,element){
                    var activar
                    id = info.event.id;
                    if (boton2 ==1) $("#bguardar").show(); else $("#bguardar").hide();
                    if (boton3 ==1) activar=false; else activar=true;

                    $.contextMenu({
                        selector: '.fc-event', 
                        trigger: 'left',
                        autoHide: true,
                        callback: function(key, options) {
                            var m = key;
                            if (m=='delete'){
                                elimina_agenda(id,info);
                            }
                            if (m=='infor'){
                                if (boton2 ==1) $("#bguardar").show(); else $("#bguardar").hide();
                                limpiar();
                                $('#idcita').val(id);
                                recuperar_agenda(id);
                                mostra_ventana('ModalEmpresa');
                            }
                          },
                        items: {
                            "infor": {name: "Editar", icon: "fa-edit"},
                            "separator": "-----",
                            "delete": {name: "Eliminar", icon: "delete", disabled: activar},
                        }
                    });
                },
                resources: {
                    url: 'api/api_calendar.php',  method: 'POST',extraParams: {institu: institu, vertodo:  vertodo, codus: codus, code: code,  acc: 'reso' },
                },
                events: {
                    url: 'api/api_calendar.php',  method: 'POST',extraParams: {institu: institu, vertodo:  vertodo, codus: codus, code: code,  acc: 'event' },
                }
            });
            calendar.render();
        });
}

function horarios_trabajo(id_es){
    var cadena ="";
    $('#hcontenido').empty();
    var parametros = {'code': id_es, 'acc': 'horar'};
    $.ajax({
        data:parametros,
        url: 'api/api_calendar.php',
        type: 'POST',
        success: function (response) { 
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key,value) {
                cadena = cadena + 'Desde: '+value.hoariosatencion_horainicio + ' Hasta: '+value.horarioatencion_horahasta +'<br>';
            });
            $('#hcontenido').html('<p><b>Horarios de trabajo:</b></p>' + cadena);
        }
    });  
}

function elimina_agenda(id_ag,argum){
    if(confirm('¿Desea eliminar registro seleccionado?')){
        $('#msg').html('<div class="alert alert-info" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Eliminando y notificando al cliente, por favor espere...</p></div>');
        premsg(2);
            var parametros = {'code': id_ag, 'institu':institu, 'acc': 'eli'};
            $.ajax({
                data:parametros,
                url: 'api/api_calendar.php',
                type: 'POST',
                success: function (response){ 
                    if (response ==1){
                        argum.event.remove();
                        $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente.</p></div>');
                        premsg(2);
                        window.location.reload();
                    }
                    else{
                        $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, por favor validar la información</p></div>');
                        premsg();
                    }
                }
            });
      }
}

function guarda_agenda(){
        var tipog =$('#cbtipocita').val();
        var cod =$('#cmbconfirma').val();
        var institu=$('#idcita').val();
        var siguardo = true;
        if (tipog >0){
            var arr = [];
            cod=' '+cod;
            $("#frmcitas input").each(function () {
                arr.push($(this).val());
                if ($(this).val().trim().length ==0) siguardo =false;
            });
            arr.push($('#txtempresa').val());
            if (siguardo){
                $('#msg').html('<div class="alert alert-info" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Procesando información, por favor espere...</p></div>');
                premsg(2);

                var datos = JSON.stringify(arr);
                var parametros = {'code': datos, 'tipoc': tipog+' ', 'acc': 'alma','institu': institu,'codus': cod, 'medio': '3'};
                $.ajax({
                    data:parametros,
                    url: 'api/api_calendar.php',
                    type: 'POST',
                    success: function (response) { 
                        console.log('resp '+response);
                        if (response ==1){
                            $('#msg').html('<div class="alert alert-success" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso ejecutado correctamente, se notifico al paciente.</p></div>');
                            if (institu ==0){
                                premsg(2);
                                window.location.reload();
                            } 
                            else premsg();
                        }
                        else{
                            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, por favor validar la información</p></div>');
                            premsg();
                        } 
                    }
                });
            } else  {
                $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, revisar que no existan campos vacios</p></div>');
                premsg();
            } 
        }else {
            $('#msg').html('<div class="alert alert-danger" role="alert"><h4 class="alert-heading">Acción!!</h4><p>Proceso no ejecutado, por favor validar tipo de cita</p></div>');
            premsg();
        }   
}

function recuperar_agenda(id_ag){
    var arr = [];
    var parametros = {'code': id_ag, 'acc': 'recu'};
    $.ajax({
        data:parametros,
        url: 'api/api_calendar.php',
        type: 'POST',
        success: function (response) { 
            var obj = jQuery.parseJSON(response);
            var i=0;
            var idespe=0;
           
            $("#frmcitas input").each(function () {
                if (i==0){
                    idespe=obj[0][i];
                    $("#idespecialista").val(obj[0][i]);
                    recupera_especia(obj[0][i]);
                } 
                else if (i==6)  cargar_costos(idespe,obj[0][i]);
                else if (i == 7)  $('#cmbconfirma').val(obj[0][i]);
                else if (i == 8)  $('#txtobservacion').val(obj[0][i]);
                else if (i == 9){
                    $('#txtfechacita').val(obj[0][i].split(' ')[0]);
                    $('#txthorai').val(obj[0][i].split(' ')[1].substring(0, 5));
                }
                else if (i==10) $('#txthoraf').val(obj[0][i].split(' ')[1].substring(0, 5));
                else if (i==12) $('#idcita').val(obj[0][i]);
                else {
                    $(this).val(obj[0][i]);
                }
                i++;
            });
        }
    });   
}

function recupera_especia(id_med){
    var parametros = {'code': id_med, 'acc': 'espe'};
    $.ajax({
        data:parametros,
        url: 'api/api_calendar.php',
        type: 'POST',
        success: function (response) { 
           $('#txtespecialista').val(response);
        }
    }); 
}

function cargar_costos(id_med,sleccion){
    var parametros = {'code': id_med, 'acc': 'cos'};
    $.ajax({
        data:parametros,
        url: 'api/api_calendar.php',
        type: 'POST',
        success: function (response) { 
            lista = $('#cbtipocita');
            var obj = jQuery.parseJSON(response);
            lista.empty();
            lista.append('<option value="0">Seleccione</option>');
            $.each(obj, function(key,value) {
                lista.append('<option value="'+value.id_tipocitas+'">'+value.tipocitas_nombre+ ' costo: ' + value.tipocitas_valor +' USD</option>');
            });
            if (sleccion > 0) $("#cbtipocita").val(sleccion); else  $("#cbtipocita").val(0);
        }
    });   
}

function limpiar(){
    $("#txtced").css("background-color", "");
    $('#idcita').val("0");
    $("#frmcitas input").each(function () {
        if ($(this).attr('id') != 'txtespecialista')
            $(this).val(" ");
    });
}

function prueba(){
    var parametros = {'institu': institu, 'vertodo':  vertodo, 'codus': codus, 'code': code,  'acc': 'event'};
    $.ajax({
        data:parametros,
        url: 'api/api_calendar.php',
        type: 'POST',
        success: function (response) { 
                console.log(response);
        }
    }); 

}