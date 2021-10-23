<div class="modal fade" id="confirm-delete13" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Confirmación de Eliminado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
                {!! Form::open(['action' => ['Admin\configuracion\status\StatusTController@destroy'], 'id'=>'form_statust_eliminar']) !!}
            <div class="modal-body">
                {{ Form::hidden('id', null, ['id'=>'modal_registo_statust_id'] ) }}

                    <p>Vas a eliminar el status tasas <b><i class="title"></i></b>, este proceso es irreversible.</p>
                    <p>¿Deseas continuar?</p>
                      
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-transition btn btn-outline-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                     <i class="fa fa-reply-all"></i>
                    </span>{{'Cancelar'}}
                </button>
                <button type="submit" class="btn-transition btn btn-outline-danger">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-eraser"></i>
                        </span>{{'Eliminar'}}</a>
                    </button>
            </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>
