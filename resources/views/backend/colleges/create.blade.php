@extends('backend.layouts.app')
@section('title', 'College | '.app_name())

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
    {!! Form::open(['method' => 'POST', 'route' => ['admin.college.store'], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">College</h3>
            <div class="float-right">
                <a href="{{ route('admin.college.index') }}"
                   class="btn btn-success">View Colleges</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('name', 'Name *', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Name', 'required' => true]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    Type
                    <select name="type" id="" class="form-control">
                        <option value="college">College</option>
                        <option value="university">University</option>
                    </select>
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
@endpush