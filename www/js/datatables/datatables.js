
$(document).ready(function() {
    $('#dataTable').dataTable({
      "language": {
        "lengthMenu": "Mostrar _MENU_ registro por página",
        "zeroRecords": "Bandeja vacia",
        "info": "Páginas _PAGE_ de _PAGES_",
        "infoEmpty": "Información no disponible",
        "infoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sSearch":      "Buscar:",
        "sInfoEmpty":    "Mostrando registros del 0 al 0 de un total de 0 registros",
        "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "Siguiente",
          "sPrevious": "Anterior"
          }
        },
        pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                dom: 'Bfrtip',
                buttons: [
                    {extend: 'copy', title: 'Sistema medico'},
                    {extend: 'csv', title: 'Sistema medico'},
                    {extend: 'excel', title: 'Sistema medico'},
                    {extend: 'print', title: 'Sistema medico'}
                ]
    });
} );