function notificacion(total,msg1,msg2) {

    var obj = jQuery.parseJSON(total);
        $.each(obj, function(key,value) {
            noti = obj[0];
            confir = obj[1];
        }); 
    if (noti >0){
        var x = document.getElementById("snackbar");
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        $("#snackbar").html(msg1 +''+ noti+''+ msg2);
    }

    if (confir > 0){
        $('#contadorcita').text(confir);
    }else{
        $('#contadorcita').text('');
    }
}

function validar_datos(espe,msg1,msg2){
    espe = espe.replace('"', '');
    espe = espe.replace('"', '');
    var parametros = {'code': espe};
        $.ajax({
            type: 'post',
            url: 'api/api_footer.php',
            data: parametros,
            success: function(response) {
                notificacion(response,msg1,msg2);
            }
        });
}