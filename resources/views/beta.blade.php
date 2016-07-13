@extends('app')
@section('content')
<div ng-controller="CalculadoraController as calc">
    @{{calc.cdi.name }}: @{{ calc.cdi.value }}
</div>
@endsection