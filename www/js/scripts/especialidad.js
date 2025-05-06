        function Borrar(ruta,codigo,abrir,idm,sub) 
        {
            if(confirm('¿Desea eliminar registro?')){
                var parametros = {'code': codigo, 'fin': '1','abrir': abrir, 'id': idm,'sub': sub};
                $.ajax({
                    data:parametros,
                    url: 'api/api_especialidades.php',
                    type: 'get',
                    success: function (response) { 
                        location.href="?open="+response;
                    }
                });
                
            }
            else{
                return false;
            }
        }

        function cargar(ruta,code){
            var parametros = {'code': code};
                $.ajax({
                    type: 'get',
                    url: 'api/api_especialidades.php',
                    data: parametros,
                    success: function(response) {
                        var obj = jQuery.parseJSON(response);
                            $.each(obj, function(key,value) {
                                $('#cmbinstitu').val(value.esp_idinstitucion);
                                $('#txtcodigo').val(value.id_especialidad);
                                $('#txtnombres').val(value.esp_nombre);
                                $('#txtobserva').val(value.esp_observacion);
                                mostra_ventana('ModalEmpresa');
                            }); 
                    }
                });
           
        }

        function limpiar(){
            $('#txtcodigo').val(-1);
            $('#txtnombres').val("");
            $('#txtobserva').val("");
            mostra_ventana('ModalEmpresa');
        }