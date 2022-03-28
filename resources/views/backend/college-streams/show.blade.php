@extends('backend.layouts.app')
@section('title', 'College Stream | '.app_name())

@push('after-styles')
    <style>
        .college_stream-detail-content p img{
            margin: 2px;
        }
        .label{
            margin-bottom: 5px;
            display: inline-block;
            border-radius: 0!important;
            font-size: 0.9em;
        }
    </style>
@endpush

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">College Stream</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        
                        <tr>
                            <th>Name</th>
                            <td>{{ $college_stream->name }}</td>
                        </tr>
                        
                        <tr>
                            <th>Created At</th>
                            <td>{{ $college_stream->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->

            <!-- Tab panes -->


            <a href="{{ route('admin.college_stream.index') }}"
               class="btn btn-default border">@lang('strings.backend.general.app_back_to_list')</a>
        </div>
    </div>

@endsection

@push('after-scripts')
@endpush