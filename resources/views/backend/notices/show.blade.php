@extends('backend.layouts.app')

@php
    $segment = Request::segment(2)
@endphp


@section('title', ucwords($segment) . ' | '.app_name())

@push('after-styles')
    <style>
        .notice-detail-content p img{
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
            <h3 class="page-title float-left mb-0">{{ ucwords($segment) }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">

                        <tr>
                            <th>Active</th>
                            <td>
                                @if ($notice->is_active)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-warning">In Active</span>
                                @endif
                            </td>
                        </tr>
                        
                        <tr>
                            <th>Title</th>
                            <td>{{ $notice->title }}</td>
                        </tr>
                        <tr>
                            <th>Content</th>
                            <td>{!! $notice->content !!}</td>
                        </tr>

                        <tr>
                            <th>Type</th>
                            <td>
                                <span class="badge badge-{{ $notice->type }}">{{ $notice->type }}</span>
                            </td>
                        </tr>

                        <tr>
                            <th>View By</th>
                            <td>{{ $notice->view_by }}</td>
                        </tr>
                        
                        <tr>
                            <th>Opened At</th>
                            <td>{{ $notice->opened_at->format('d M Y') }}</td>
                        </tr>

                        <tr>
                            <th>Closed At</th>
                            <td>{{ $notice->closed_at->format('d M Y') }}</td>
                        </tr>

                        <tr>
                            <th>Created At</th>
                            <td>{{ $notice->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->

            <!-- Tab panes -->


            <a href="{{ route("admin.$segment.index") }}"
               class="btn btn-default border">@lang('strings.backend.general.app_back_to_list')</a>
        </div>
    </div>

@endsection

@push('after-scripts')
@endpush