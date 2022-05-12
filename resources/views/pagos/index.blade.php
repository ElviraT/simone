@extends('layouts.Base')
@section('css')
  @include('pagos.css.css')
@endsection
@section('banner')
<div class="col-md-8">
  <div class="page-header-title">
      <h5 class="m-b-10">{{'Pagos'}}</h5>
      <p class="m-b-0">{{'Listado de Pagos'}}</p>
  </div>
</div>
<div class="col-md-4">
  <ul class="breadcrumb-title">
      <li class="breadcrumb-item">
          <a href="{{ route('pago')}}" onclick="loading_show();"> <i class="fa fa-home"></i> </a>
      </li>
      <li class="breadcrumb-item"><a href="#!">{{'Pagos'}}</a>
      </li>
  </ul>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
	    <div class="col-md-12">
	    	<div class="card">
		    	<div class="col-md-12">
		    		{!! Form::open(['route' => ['pago.add'],  'method' => 'post',  'autocomplete'=> 'off','files' => true, 'id'=>'registra_pago' ]) !!}
			    	<div class="row">
			        	<div class="col-md-3 mt-3">
			              {!! Form::label('paciente', 'Usuario:') !!}
			              {!! Form::select('paciente',$pacientes,  $paciente, [
			                  'placeholder' => 'Seleccione', 
			                  'class' => 'select2 form-control',
			                  'id' => 'paciente',
			                  'required'=>'required'
			                  ])
			              !!}
			            </div>
			            <div class="col-md-3 mt-3">
			            	{!! Form::label('telefono', 'Teléfono:') !!}
			            	<input type="text" name="telefono" id="tel" class="form-control" value="{{$telefono}}" readonly>
			            </div>
			            <div class="col-md-3 mt-3">
			            	{!! Form::label('celular', 'Celular:') !!}
			            	<input type="text" name="celular" id="cel" class="form-control" value="{{$celular}}" readonly>
			            </div>
			            <div class="col-md-3 mt-3">
			            	{!! Form::label('correo', 'Correo:') !!}
			            	<input type="text" name="correo" id="correo" class="form-control" value="{{$correo}}" readonly>
			            </div>
			            <div class="col-md-3 mt-3">
			              {!! Form::label('moneda', 'Moneda:') !!}
			              {!! Form::select('moneda',$monedas,  null, [
			                  'placeholder' => 'Seleccione', 
			                  'class' => 'select2 form-control',
			                  'id' => 'moneda',
			                  'required'=>'required'
			                  ])
			              !!}
			            </div>
			            <div class="col-md-3 mt-3">
			            	{!! Form::label('monto', 'Monto:') !!}
			            	<input type="number" name="monto" id="monto" class="form-control" step="0.2">
			            </div>
			            <div class="col-md-3 mt-3">
			            	{!! Form::label('ref', 'Referencia:') !!}
			            	<input type="text" name="referencia" id="referencia" class="form-control">
			            </div>
			            <div class="col-md-3 mt-3">
		                    <label>{{ 'Fecha del pago' }}</label>
		                      <div class="input-group">
		                        <div class="input-group-prepend">
		                            <span class="input-group-text">
		                            <i class="fa fa-calendar"></i>
		                            </span>
		                        </div>
		                         {!!
		                            Form::text('fecha', null, [
		                             'id' => 'fecha',
		                             'placeholder'=>'Y-M-D',
		                             'class' => 'form-control pull-right datepicker',
		                             'required'=>'required'
		                            ] )
		                          !!}
		                     </div>
		                </div>
		                <div class="col-md-3 mt-3">
			                {!! Form::label('tpago', 'Tipo de Pago:') !!}
			                {!! Form::select('tpago',$tpago, null, [
			                    'placeholder' => 'Seleccione', 
			                    'class' => 'select2 form-control',
			                    'id' => 'tpago',
			                    'required'=>'required'
			                    ])
			                !!}
			            </div>
			            <div class="col-md-3 mt-3" id="bs" hidden>
			                {!! Form::label('cbs', 'Cuenta BS:') !!}
			                {!! Form::select('cbs',$cbs, null, [
			                    'placeholder' => 'Seleccione', 
			                    'class' => 'select2 form-control',
			                    'id' => 'cbs'
			                    ])
			                !!}
			            </div>
			            <div class="col-md-3 mt-3" id="usd" hidden>
			                {!! Form::label('cusd', 'Cuenta USD:') !!}
			                {!! Form::select('cusd',$cusd, null, [
			                    'placeholder' => 'Seleccione', 
			                    'class' => 'select2 form-control',
			                    'id' => 'cusd'
			                    ])
			                !!}
			            </div>
			            <div class="col-md-3 mt-3">
			              {!! Form::label('banco', 'De que banco transfirío:') !!}
			              {!! Form::select('banco',$bancos,  null, [
			                  'placeholder' => 'Seleccione', 
			                  'class' => 'select2 form-control',
			                  'id' => 'banco',
			                  'required'=>'required'
			                  ])
			              !!}
			            </div>
			            <div class="col-md-12 mt-3">
				            <div class="row">
				                <div class="col-md-12">
				                    <label>{{ 'Comprobante del pago' }}</label>
				                    <input type="file" name="comprobante" id="comprobante">
				                </div>
				            </div>
				        </div>
			 		</div>
			 		@can('pago.add')
			 		<div class="modal-footer">
				        <button type="submit" class="mt-1 btn btn-outline-success">
				            <span class="btn-icon-wrapper pr-2 opacity-7">
				             <i class="fa fa-floppy-o" aria-hidden="true"></i>
				            </span>{{'Guardar'}}
				        </button>
				    </div> 
				    @endcan
        		{!! Form::close() !!}
		    	</div>
	    	</div>
	    </div>
    </div>
</div>
@endsection
@section('js')
 @include('pagos.js.js')
@endsection