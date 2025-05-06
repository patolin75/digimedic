function pintar_sel(ades=0){
    if (ades==1){
        if($('#chktodos').prop('checked')) 
            $('#chktodos').prop('checked', false);
        else
            $('#chktodos').prop('checked', true);
    }

    var a = document.querySelectorAll('input[type="checkbox"]');
    for (var i=0; i<a.length; i++){
        if($('#chktodos').prop('checked'))  
            a[i].checked = true;
        else  
            a[i].checked = false;
    }
      
}