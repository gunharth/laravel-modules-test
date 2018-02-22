@extends('layouts.master')

@section('content-header')
    <h1 class="pull-left">
        {{ trans('dashboard::dashboard.name') }}
    </h1>
    <div class="btn-group pull-right">
        <a class="btn btn-default" id="edit-grid" data-mode="0" href="#">{{ trans('dashboard::dashboard.edit grid') }}</a>
        <a class="btn btn-default" id="reset-grid" href="{{ route('dashboard.grid.reset')  }}">{{ trans('dashboard::dashboard.reset grid') }}</a>
        <a class="btn btn-default hidden" id="add-widget" data-toggle="modal" data-target="#myModal">{{ trans('dashboard::dashboard.add widget') }}</a>
    </div>
    <div class="clearfix"></div>
@stop

@push('css-stack')
    <style>
        .grid-stack-item {
            padding-right: 20px !important;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="grid-stack">
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('dashboard::dashboard.add widget to dashboard') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js-stack')
    @parent

@endpush
