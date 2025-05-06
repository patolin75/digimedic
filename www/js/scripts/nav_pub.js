var ahora=1;
var paginas=0;
var pagactual=1;
const paginar=10;
var citas_pa =new Array();

document.addEventListener("DOMContentLoaded", function(){
    const segundos=11000;
    setInterval(function(){
            animar();
    },segundos);
});

function recupera_citas(){
    var j=0;
    institu=$('#txtins').val();
    vertodo=$('#txtver').val();

    if (presentar_citas()==0){
        var parametros = {'code': institu, 'espec': vertodo};
        $.ajax({
            type: 'post',
            url: 'api/api_nav_pub.php',
            data: parametros,
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                citas_pa.length=0;
                pagactual=1;
                paginas=0;

                if(obj == null) {
                    $('#table-cont').empty();
                    $("#table-cont").append('<tr><td colspan="3">No existe información de turnos</td></tr>');
                    $('#paginar').html('página 0 de 0');
                }
                else{
                    j=0;
                    $.each(obj, function(key,value) {
                        citas_pa.push('<tr><td>'+value.nomespec + ' ' +value.apellespec +'</td><td>'+value.nompac + ' ' +value.apepac +'</td><td class="col-sm-2">' + value.hora +'</td></tr>');
                        j++;
                    });
                    paginas = Math.ceil(j /paginar);
                    presentar_citas();
                }
            }
        });
    }
   
}

function presentar_citas(){
    var desde=0;
    var hasta=0;
    if (pagactual<= paginas && citas_pa.length>0){
        $('#table-cont').empty();
        desde = (pagactual*paginar)-paginar;
        hasta = paginar*pagactual;

        $('#paginar').html('página '+pagactual +' de '+ paginas);
        for(i=desde;i<hasta;i++){
            $("#table-cont").append(citas_pa[i]);
        }
        pagactual=pagactual+1;
        return 1;
    }else{
        return 0;
    } 
}

function animar(){
    var i=0;
    $(".footer").find('img').each(function() {
        i++;
    });
    presentar(i);
    fecha_hora();
    recupera_citas();
}

function fecha_hora(){
    var hoy = new Date();
    var fecha = hoy.getDate() + '/' + ( hoy.getMonth() + 1 ) + '/' + hoy.getFullYear();
    var hora = hoy.getHours() + ':' + hoy.getMinutes();
    $('#fechor').html("<br>"+ fecha +" hora: "+ hora);
}

function presentar(total){
    var antes=ahora-1;
    if (ahora ==1) $('#img'+ total).addClass("invisible");
    //$('#img'+ahora).fadeIn(300);
    //$('#img'+antes).fadeOut('slow');
    $('#img'+ahora).removeClass("invisible");
    $('#img'+antes).addClass("invisible");
    if (ahora <total) 
        ahora++; 
    else{
        ahora=1;
    } 
}

