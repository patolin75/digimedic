function Borrar(ruta,codigo,abrir,idm,sub) 
        {
            if(confirm('¿Desea eliminar registro?')){
                var parametros = {'code': codigo, 'fin': '1','abrir': abrir, 'id': idm,'sub': sub};
                $.ajax({
                    data:parametros,
                    url: 'api/rol_api.php',
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
                    url: 'api/rol_api.php',
                    data: parametros,
                    success: function(response) {
                        var obj = jQuery.parseJSON(response);
                            $.each(obj, function(key,value) {
                                $('#txtcodigo').val(value.id_roles);
                                $('#txtnombres').val(value.nombre_roles);
                                $('#txtobserva').val(value.observacion_roles);
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