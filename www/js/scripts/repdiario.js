function genera_val(id,fecha){
    var parametros = {'code': id, 'fecha': fecha};
    var sum=0;
    $.ajax({
        type: 'post',
        url: 'api/api_repdiario.php',
        data: parametros,
        success: function(response) {
            var obj = jQuery.parseJSON(response);
            $('#bodyt').empty();
            $.each(obj, function(key,value) {
                    $("#bodyt").append('<tr><td>Paciente: '+value.nombre+' '+value.apellido+' ('+value.cita+')</td><td>'+value.valor+'</td></tr>');
                    sum=sum+parseFloat(value.valor);
            }); 
            $("#bodyt").append('<tr><td><label>TOTAL:</label></td><td>'+sum+' </td></tr>');
        }
    });
}