$(function () {
    $('[name="SearchDualList"]').keyup(function (e) {
        var code = e.keyCode || e.which;
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('.list-group li');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $('[name="SearchDualList1"]').keyup(function (e) {
        var code = e.keyCode || e.which;
        if (code == '9') return;
        if (code == '27') $(this).val(null);
        var $rows = $(this).closest('.dual-list').find('.list-group li');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

});

function premsg(actu=""){
    $("#frmmsg").modal({ backdrop: "toggle"});
    if (actu==1) 
        setTimeout(function() { $(function(){$("#frmmsg").modal("hide"); window.location.reload();}); }, 3300);
    if (actu==2)
        $("#frmmsg").modal({ backdrop: "toggle"});
    else
        setTimeout(function() { $(function(){$("#frmmsg").modal("hide");}); }, 4000);
}

function mostra_ventana(nombre){
    $(function(){$("#"+nombre).modal({ backdrop: "static", keyboard: false });});
}