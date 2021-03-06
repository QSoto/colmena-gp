<div class="modal fade" id="reactivate-form-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="">Activar Actividad</h4>
      </div>
      <div class="modal-body">

		  <div class="text-center">
			  <span class="label label-warning">ATENCIÓN</span><br>
			  Esta a punto de activar la actividad recurrente:
			  <br>
			  <strong>{{ $activity->title }}</strong><br>
			  <br>
			  ¿Desea continuar?
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		<a href="#" onclick="$('#reactivate-activity').submit();" class="btn btn-primary">Continuar</a>
      </div>
    </div>
  </div>
</div>
<form id="reactivate-activity" method="post" action="{{url("/actividades-recurrentes/reactivar")}}">
	{{ csrf_field() }}
	<input type="hidden" name="id" value="{{$activity->id}}">
</form>
