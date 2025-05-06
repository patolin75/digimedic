$(document).ready(function () {
    window.setTimeout(function() {
        $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
            $(this).remove(); 
        });
    }, 3000);
});


$(window).resize(function() {
    if ($(window).width() >= 1100){
        $('#opciones').show();
    }
    else{
        $('#opciones').hide();
    }
});


function ventanita(codigo){
    $(function(){mostra_ventana('Modaluserconfig');});
}

$(document).ready(function(){
    $("#txtpasswa").on("input", function(){
        valor = document.getElementById("txtpasswa").value;
        valor1 = document.getElementById("txtpassan").value;

        if (valor.length < 6){
            show_alertac('Recuerde que su contraseña debe tener al menos 6 caracteres');
        }else if (tiene_numeros(valor) ==0) { 
            show_alertac('Debe contener al menos un numero');
        }else if (tiene_mayusculas(valor) ==0 ){
            show_alertac('Debe contener al menos una letra mayúscula');
        }else if (valor == valor1){
            show_alertac('Nueva contraseña no debe ser igual al anterior');
        }else{
            $(form_errorsu).hide();
            //$('#btnregistra').show();
        }
    });
    $("#txtpasr").on("input", function(){
        valor = document.getElementById("txtpasswa").value;
        valor1 = document.getElementById("txtpasr").value;
        valor2 = document.getElementById("txtpassan").value;

        if (valor1.length == valor.length){
            if (valor1 != valor){
                show_alertac('Re-ingreso no coincide con la clave ingresada');
            }else if (tiene_numeros(valor1) ==0) { 
                show_alertac('Debe contener al menos un numero');
            }else if (tiene_mayusculas(valor1) ==0 ){
                show_alertac('Debe contener al menos una letra mayúscula');
            }else if (valor1 == valor2){
                show_alertac('Nueva contraseña no debe ser igual al anterior');
            }else{
                $(form_errorsu).hide();
                $('#btnregistra').show();
            }
            
        }else{
            show_alertac('Recuerde que el re-ingreso debe coincidir con su clave ingresada');
        }

    });
});

function tiene_numeros(texto){
    var numeros="0123456789";
    for(i=0; i<texto.length; i++){
        if (numeros.indexOf(texto.charAt(i),0)!=-1){
            return 1;
        }
    }
    return 0;
}

function tiene_mayusculas(texto){
    var letras="ABCDEFGHYJKLMNÑOPQRSTUVWXYZ";
    for(i=0; i<texto.length; i++){
        if (letras.indexOf(texto.charAt(i),0)!=-1){
            return 1;
        }
    }
    return 0;
}

function show_alertac(message) {
    $(form_errorsu).show().html('<div class="alerta" style="color: #f00808;"><span>'+message+'</span></div>');
    $('#btnregistra').hide();
    $('#bguardar').hide();
};
            
/*let input = document.querySelector('#txtpasr');
    input.addEventListener('paste', (e) => {
        e.preventDefault(); 
});*/