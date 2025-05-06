function cargar(code,nombre){
    $("#roles").empty();
    var parametros = {'code': code};
        $.ajax({
            type: 'get',
            url: 'api/api_perfil.php',
            data: parametros,
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                    $("#cmbmenup").empty();
                    $("#cmbmenup").append('<option value="0">Seleccione un menú...</option>');
                    $.each(obj, function(key,value) {
                        $('#txtcodigo').val(code);
                        $('#txtnombres').val(nombre);
                        
                        $("#cmbmenup").append('<option value="'+value.id_menus+'">'+value.menus_nombre+'</option>');
                        mostra_ventana('ModalEmpresa');
                    }); 
            }
        });
}

function cargar_permisos(ruta,code){
    rol = $("#txtcodigo").val();
    menu = $("#cmbmenup :selected").val();
    if (menu >0){
            $("#roles").empty();
            campo = '<tr><td><label>Submenu</label></td><td><label>Activo</label></td><td><label>Crear</label></td><td><label>Modificar</label></td><td><label>Eliminar</label></td></tr>';
            $("#roles").append(campo);
            var parametros = {'perm': code,'rol': rol};
                $.ajax({
                    type: 'get',
                    url: 'api/api_perfil.php',
                    data: parametros,
                    success: function(response) {
                        var obj = jQuery.parseJSON(response);
                            $.each(obj, function(key,value) {
                                if (value.id_menus != null){
                                    if (value.id_menus >0)
                                        mchek = 'checked';
                                    else
                                        mchek = '';
                                }else
                                    mchek = '';
                                
                                if (value.crear_menus != null){
                                    if (value.crear_menus >0)
                                        mc = 'checked';
                                    else
                                        mc = '';
                                }else
                                    mc = '';
                                
                                if (value.modificar_menus != null){
                                    if (value.modificar_menus >0)
                                        md = 'checked';
                                    else
                                        md = '';
                                }else
                                    md = '';
                                
                                if (value.eliminar_menus != null){
                                    if (value.eliminar_menus >0)
                                        me = 'checked';
                                    else
                                        me = '';
                                }else
                                    me = '';
                                
                                direc="'"+ruta+"'";
                                campo = '<tr><td>'+value.menus_nombre+'</td><td><input class="form-check-input" type="checkbox" onclick="javascript:permisos('+direc+',this)" id="'+value.codmen+'" '+mchek+'></td><td><input class="form-check-input" type="checkbox" onclick="javascript:permisos('+direc+',this)" id="c'+value.codmen+'" '+mc+'></td><td><input class="form-check-input" type="checkbox" onclick="javascript:permisos('+direc+',this)" id="m'+value.codmen+'" '+md+'></td><td><input class="form-check-input" type="checkbox" onclick="javascript:permisos('+direc+',this)" id="e'+value.codmen+'" '+me+'></td></tr>';
                                $("#roles").append(campo);
                            });
                    }
                });
    }else{
        $("#roles").empty();
    }
   
}

function permisos(ruta,submenu){
    menu = $("#cmbmenup :selected").val();
    id=$(submenu).attr('id');
    rol=$("#txtcodigo").val(); //rol

    if (Number(id)){
        //principal
        if (submenu.checked){
            ejecutar(ruta,rol,menu,id,'f','add');
        }else{
            if(confirm('¿Desea remover todos los permisos de este submenú?')){
                ejecutar(ruta,rol,menu,id,'f','del');
                $("#c"+id).prop("checked", false);
                $("#m"+id).prop("checked", false);
                $("#e"+id).prop("checked", false);
            }else {
                $(submenu).prop("checked", true);
            }
        }
    }else{
        ids = id.substr(1,id.length); //code menu
        tipo = id.substr(0,1); //accion C M E
        if (submenu.checked){
            if ($("#"+ids).prop('checked')){
                ejecutar(ruta,rol,menu,ids,tipo,'add');
            }else{
                alert ("se asignaran permisos al submenu");
                $("#"+ids).prop("checked", true);
                ejecutar(ruta,rol,menu,ids,tipo,'adds');
            } 
        }else ejecutar(ruta,rol,menu,ids,tipo,'del');
    }
}

function ejecutar(ruta,rol,menu,ids,tipo,accion){
    var parametros = {'rol': rol,'menu':menu,'sub': ids, 'tipo': tipo, 'accion': accion};
    $.ajax({
        type: 'get',
        url: 'api/api_perfil.php',
        data: parametros,
        success: function(response) {
            if(response != 1) alert ('No se generaron los permisos, por favor intente mas tarde');
        }
    });
}