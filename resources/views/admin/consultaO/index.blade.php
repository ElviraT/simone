@extends('layouts.Base')
@section('css')
@include('admin.consultaO.css.css')
@endsection
@section('banner')
<div class="col-md-8">
  <div class="page-header-title">
      <h5 class="m-b-10">{{'Consulta Online'}}</h5>
      <p class="m-b-0">{{'...'}}</p>
  </div>
</div>
<div class="col-md-4">
  <ul class="breadcrumb-title">
      <li class="breadcrumb-item">
          <a href="{{ route('consultao')}}" onclick="loading_show();"> <i class="fa fa-home"></i> </a>
      </li>
      <li class="breadcrumb-item"><a href="#!">{{'consulta Online'}}</a>
      </li>
  </ul>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          @include('flash::message')
          @if(auth()->user()->esAdmin != '1')
           <div class="card" style="background: #cdcdcd; padding: 10px;">
              <div class="col-md-12 mb-2">
                <div class="row">
                  <div class="col-md-3">
                    <label>{{'Nombre'}}</label><br>
                    <label><strong>{{$medico->Nombres_Medico.' '.$medico->Apellidos_Medicos}}</strong></label>
                  </div>
                  <div class="col-md-3">
                    <label>{{'Especialidad'}}</label><br>
                    <label><strong>{{$especialidad->name}}</strong></label>
                  </div>
                  <div class="col-md-3">
                    <label>{{'Número Colegio de Medicos'}}</label><br>
                    <label><strong>{{$medico->Numero_Colegio_de_Medico}}</strong></label>
                  </div>
                  <div class="col-md-3">
                    <img src="{{ ("avatars/".str_replace('\\','/',$medico->Foto_Medico))}}" alt="foto perfil" class="img-circle" width="15%">
                  </div>
                </div>
              </div>
            </div>
          @endif
            <div class="card">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4 mb-3">
                        {!! Form::label('paciente', 'Paciente:') !!}
                        {!! Form::select('Paciente_id',$pacientes, null, [
                            'placeholder' => 'Seleccione', 
                            'class' => 'select2 form-control',
                            'id' => 'paciente',
                            'required'=>'required'
                        ]) !!}
                    </div>
                    <div class="col-md-4 mb-3">
                        {!! Form::label('pacienteE', 'Paciente Especial:') !!}
                        {!! Form::select('Paciente_Especial_id',$pacientesE, null, [
                            'placeholder' => 'Seleccione', 
                            'class' => 'select2 form-control',
                            'id' => 'pacienteE',
                            'disabled'=>'disabled'
                        ]) !!}
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
    {{--@include('admin.consultaO.modal_consultao')
            @include('admin.modales.elimina_consultao')--}}
@endsection
@section('js')
  @include('admin.consultaO.js.js')
@endsection