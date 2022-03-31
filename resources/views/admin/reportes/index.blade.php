@extends('layouts.Base')
@section('css')
@include('admin.reportes.css.css')
@endsection
@section('banner')
<div class="col-md-8">
  <div class="page-header-title">
      <h5 class="m-b-10">{{'Reportes'}}</h5>
      <p class="m-b-0">{{'Consultas'}}</p>
  </div>
</div>
<div class="col-md-4">
  <ul class="breadcrumb-title">
      <li class="breadcrumb-item">
          <a href="{{ route('reporte_consulta')}}" onclick="loading_show();"> <i class="fa fa-home"></i> </a>
      </li>
      <li class="breadcrumb-item"><a href="#!">{{'Reportes Consulta'}}</a>
      </li>
  </ul>
</div>
@endsection

@section('content')
<div class="container">
      <div class="card">
        <div class="col-md-12 mt-3">
          {!! Form::open(['route' => ['reporte_consulta'],  'method' => 'post', 'autocomplete' =>'off','name'=>'form_transaction','id'=>'form' ]) !!}
          <div class="row">
                    <div class="col-md-3 mb-3">
                      {!! Form::label('id_medico', 'Medico:') !!}
                      {!! Form::select('id_medico',$medico, $id_medico, [
                          'placeholder' => 'Seleccione', 
                          'class' => 'select2 form-control',
                          'id' => 'id_medico'
                          ]) !!}
                    </div>
                    <div class="col-md-3 mb-3">
                      {!! Form::label('especialidad', 'Especialidad:') !!}
                      {!! Form::select('id_especialidad',$especialidad, $id_especialidad, [
                          'placeholder' => 'Seleccione', 
                          'class' => 'select2 form-control',
                          'id' => 'id_especialidad'
                          ]) !!}
                    </div>
                    <div class="col-md-3 mb-3">
                      {!! Form::label('servicio', 'Servicio:') !!}
                      {!! Form::select('id_servicio',$servicio, $id_servicio, [
                          'placeholder' => 'Seleccione', 
                          'class' => 'select2 form-control',
                          'id' => 'id_servicio'
                          ]) !!}
                    </div>
                    <div class="col-md-3 mb-3">
                      {!! Form::label('cerrado', 'Asistio el paciente:') !!}
                      {!! Form::select('cerrado',['1'=> 'Si', '0'=>'No'], $id_cerrado, [
                          'placeholder' => 'Seleccione', 
                          'class' => 'select2 form-control',
                          'id' => 'cerrado'
                          ]) !!}
                    </div> 
                    <div class="col-md-3 mb-3">
                        <label>{{ 'Fecha' }}</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                             {!!
                                Form::text('fecha', $fecha, [
                                     'id' => 'fecha',
                                     'placeholder'=>'Y-M-D',
                                     'class' => 'form-control pull-right datepicker',
                                ] )
                              !!}
                         </div>
                    </div> 
                    <div class="col-md-3 mt-4">
                      <button type="submit" class="mt-1 btn btn-outline-success" id="submit">
                          <span class="btn-icon-wrapper pr-2 opacity-7">
                          </span>{{'Buscar'}}
                      </button>
                      <button type="submit" class="mt-1 btn btn-outline-info" onclick="limpiar();">
                          <span class="btn-icon-wrapper pr-2 opacity-7">
                          </span>{{'Limpiar'}}
                      </button>                   
                    </div>
          </div>
                  {!! Form::close() !!}       
        </div>
      </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
          @include('flash::message')
            <div class="card">
              @if(count($reportes) == 0)
                  <br>
                    <p class="text-center">No se encontraron registros coincidentes</p>
              @else
            <div class="col-md-12 mt-3">
                <table id="table_reportesc" class="table table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>{{'Medico'}}</th>
                            <th>{{'Especialidad'}}</th>
                            <th>{{'Servicio'}}</th>
                            <th>{{'Costo'}}</th>
                            <th>{{'Fecha'}}</th>                            
                            <th>{{'Asistio el'}}<br>{{'paciente'}}</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($reportes as $resultado)
                        <tr>
                            <td>{{ $resultado->UsuarioM->Nombres_Medico.' '.$resultado->UsuarioM->Apellidos_Medicos }}</td>
                            <td>{{ $resultado->Especialidad->Espacialiadad_Medica }}</td>
                            <td>{{ $resultado->Servicio->Servicio }}</td>
                            <td>{{ $resultado->Servicio->Costos.' '.$resultado->Servicio->simbolo }}</td>
                            <td>{{ date('Y-m-d', strtotime($resultado->Fecha)) }}</td>
                            @if($resultado->cerrado == 1)
                            <td>{{'Si'}}</td>
                            @else
                            <td>{{'No'}}</td>
                            @endif
                        </tr>
                      @endforeach

                    </tbody>                   
                </table>
              </div>
               @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
  @include('admin.reportes.js.js')
@endsection