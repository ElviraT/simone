<!-- Select2 -->
<script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>


<script type="text/javascript">
 $(document).ready(function() {
       var table_agendas = $('#table_agendas').DataTable({
            lengthChange: false,
            responsive: true,
            language: {
                url: "{{ asset('js/Spanish.json') }}",
              },
        });
    });
$(document).ready(function() {
    $('.select2').select2({ 
        theme : "classic",
        closeOnSelect: true,
        dropdownParent: $('#modal_agenda'),
         });
    });


$('#horario').on('select2:select', function (e) {
   $('#modal_agenda').addClass('loading');
   var horario = $('#horario').val();
    $.getJSON('{{ route('horario_datos') }}?horario='+horario, function(objC){
        $('#medico').val(objC['Medico_id']);
        $('#especialidad').val(objC['Especialidad_id']).change();        
    });
    $('#modal_agenda').removeClass('loading');
  });
  $('#especialidad').on('change', function (e) {
   var especialidad = $('#especialidad').val();
   $('#modal_agenda').addClass('loading');
    $.getJSON('{{ route('consultorio_dependiente') }}?especialidad='+especialidad, function(objE){
         var opcion = $('#consultorio').val();
            $('#consultorio').empty();
            $('#consultorio').prop('disabled', true);
            $('#consultorio').change();
        
        if(objE.length > 0){
            $.each(objE, function (i, consultorios) {
            $('#consultorio').append(
                    $('<option>', {
                        value: consultorios.id_Consultorio,
                        text : consultorios.Local
                    })
                );
            });
            $('#consultorio').prop('disabled', false);
            $("#consultorio option:first").attr("selected", "selected");
            $('#modal_agenda').removeClass('loading');
        }else{
            $('#modal_agenda').removeClass('loading');
            Swal.fire(
              '¡Error!',
              'Esta especialidad no tiene consultorio',
              'error'
            );
            $('#modal_agenda').modal('hide');
        }        
    });
    
});
$('#modal_agenda').on('show.bs.modal', function (e) {
    var modal = $(e.delegateTarget),
        data = $(e.relatedTarget).data();
        loading_hide();
    if (data.recordId != undefined) {
        modal.addClass('loading');
        $('.modal_registro_agenda_id', modal).val(data.recordId);
        $.getJSON(modal.data().consulta + '?id=' + data.recordId, function (data) {
            var obj = data[0];
            $('#medico', modal).val(obj.Medico_id);
            $('#medico').change();
            $('#especialidad', modal).val(obj.Especialidad_Medica);
            $('#especialidad').change();
            $('#horario', modal).val(obj.Horario_Cita_id);
            $('#horario').change();
            $('#consultorio', modal).val(obj.Consultorio_id);
            $('#consultorio').change();
            $('#mpaciente', modal).val(obj.Max_pacientes);
            $('#status', modal).val(obj.Status_id);
            $('#status').change();
            $('#nota').val(obj.Nota);
            modal.removeClass('loading');
            loading_hide();
        });
    }
});
$('#modal_agenda').on('hidden.bs.modal', function (e) {
    $('#horario').val('').change();
    $('#medico').val('');
    $('#especialidad').val('');
    $('#consultorio').val('').change();
    $('#mpaciente').val('');
    $('#status').val('').change();
    $('#nota').val('');
});

$('#confirm-delete33').on('click', '.btn-ok', function(e) {
        var $modalDiv = $(e.delegateTarget);
        $modalDiv.addClass('loading');
        setTimeout(function(){
            $('#form_agenda_eliminar').submit();
        }, 2000);
    });
$('#confirm-delete33').on('show.bs.modal', function(e) {
    var data = $(e.relatedTarget).data();
    $("#form_agenda_eliminar",  this).attr('action', data.action);
    $('#modal_registo_agenda_id', this).val(data.recordId);
    $('.title', this).text(data.recordTitle);
    $('.btn-ok', this).data('recordId', data.recordId);
    loading_hide();
});
</script>