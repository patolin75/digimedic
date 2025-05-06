var textarea = document.getElementById('txtmsg');
var urlMobile = 'whatsapp://';
sceditor.create(textarea, {
    format: 'bbcode',
    icons: 'monocons',
    style: 'js/editor/themes/content/default.min.css',
    locale: 'es',
    width: "100%",
    height: "100%"
});
    
var theme = 'js/editor/themes/office.min.css';

function validar_telefono(telefono){
    var expreg = /^[0-9]{9}/;
    if (expreg.test(telefono))
        return true;
    else
        return false; 
}

function enviar(){
   let textarea = document.getElementById('txtmsg');
   let value = sceditor.instance(textarea).val();

        $("#especialista input").each(function () {
            if ($(this).prop('checked'))
                if (validar_telefono($(this).val()))
                    startTimer($(this).val(), value);
                else
                    alert("mail");
        });

}


function startTimer(telefono,cadena)
{
    setTimeout(enviar_msg(telefono,cadena), 4000);
}

function enviar_msg(telefono,cadena){
    cadena=cadena.replace(/<[^>]+>/g, '');
    let message = 'send?phone=593' + ''+ telefono + '&text=' + cadena;
        var ventana = window
        ventana.open(urlMobile + ''+ message, '_blank');
        ventana.focus();

        var evt = new KeyboardEvent('keydown', { key: 'Enter'}); 
        ventana.dispatchEvent (evt);
}




