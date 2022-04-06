@extends('backend.layouts.app')
@section('title', 'College Stream | '.app_name())

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
    {!! Form::model($college_stream, ['method' => 'PUT', 'route' => ['admin.college_stream.update', $college_stream->id], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">College Stream</h3>
            <div class="float-right">
                <a href="{{ route('admin.college_stream.index') }}"
                   class="btn btn-success">View College Streams</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('name', 'Name *', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Name', 'required' => true]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    College/University
                    @php
                        $colleges = App\Models\College::all();
                    @endphp
                    <select name="college_id" id="college_id" class="form-control mb-0 select2-tag-college" required>
                        <option></option>
                        @foreach ($colleges as $college)
                            <option value="{{ $college->id }}" {{ $college_stream->college_id == $college->id ? 'selected' : ''}}>{{ $college->name }}</option>
                        @endforeach
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
    {!! Form::close() !!}

@endsection


@push('after-scripts')
<script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>

<script>
    $(document).ready(function() {

        $(".select2-tag-college").select2({
            placeholder: "Select a college"
        });
        
    });
</script>
@endpush