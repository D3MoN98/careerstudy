@extends('backend.layouts.app')
@section('title', 'Notice | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">
    <style>
        .select2-container--default .select2-selection--single {
            height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
        }
        .bootstrap-tagsinput{
            width: 100%!important;
            display: inline-block;
        }
        .bootstrap-tagsinput .tag{
            line-height: 1;
            margin-right: 2px;
            background-color: #2f353a ;
            color: white;
            padding: 3px;
            border-radius: 3px;
        }

    </style>
@endpush

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['admin.notice.store'], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Notice</h3>
            <div class="float-right">
                <a href="{{ route('admin.notice.index') }}"
                   class="btn btn-success">View Notices</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', 'Title *', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Title', 'required' => true]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('content', 'Content *', ['class' => 'control-label']) !!}
                    {!! Form::textarea('content', old('content'), ['class' => 'form-control editor', 'placeholder' => '','id' => 'editor', 'required' => true]) !!}

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('type', 'Type *', ['class' => 'control-label']) !!}
                    {!! Form::select('type', ['primary' => 'Primary',
                    'secondary' => 'Secondary',
                    'success' => 'Success',
                    'warning' => 'Warning',
                    'danger' => 'Danger'], old('type'), ['class' => 'form-control', 'required' => true]) !!}
                </div>

                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('view_by', 'View By *', ['class' => 'control-label']) !!}
                    {!! Form::select('view_by', ['all' => 'All', 'student' => 'Student', 'teacher' => 'Teacher'], old('view_by'), ['class' => 'form-control', 'required' => true]) !!}
                </div>

                <div class="col-12 col-lg-6  form-group">
                    {!! Form::label('opened_at', "Opened At *".' (yyyy-mm-dd)', ['class' => 'control-label']) !!}
                    {!! Form::text('opened_at', old('opened_at'), ['class' => 'form-control date','pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => "Opened At".' (Ex .' . date('Y-m-d') . ' )', 'autocomplete' => 'off', 'required' => true]) !!}
                </div>
                
                <div class="col-12 col-lg-6  form-group">
                    {!! Form::label('closed_at', "Closed At *".' (yyyy-mm-dd)', ['class' => 'control-label']) !!}
                    {!! Form::text('closed_at', old('closed_at'), ['class' => 'form-control date','pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => "Closed At".' (Ex .' . date('Y-m-d') . ' )', 'autocomplete' => 'off', 'required' => true]) !!}
                </div>
            </div>
            
            <div class="row">

                <div class="col-md-12 text-center form-group">
                    <button type="submit" class="btn btn-info waves-effect waves-light ">
                       Publish
                    </button>
                    <button type="reset" class="btn btn-danger waves-effect waves-light ">
                       Cancel
                    </button>
                </div>

            </div>

        </div>
    </div>

@endsection

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script>
        $('.editor').each(function () {

            CKEDITOR.replace($(this).attr('id'), {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',

                extraPlugins: 'smiley,lineutils,widget,codesnippet,prism,flash',
            });

        });

        $('#opened_at').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });

        $('#closed_at').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });

    </script>
@endpush