@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')

@php
    $segment = Request::segment(2)
@endphp

@section('title', ucwords($segment) . ' | ' . app_name())

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">{{ ucwords($segment) }}</h3>
            <div class="float-right">
                <a href="{{ route("admin.$segment.create") }}"
                    class="btn btn-success">@lang('strings.backend.general.app_add_new')</a>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">

                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>@lang('labels.general.sr_no')</th>
                            <th>@lang('labels.general.id')</th>
                            <th>Title</th>
                            <th>Created</th>
                            @if (request('show_deleted') == 1)
                                <th>@lang('strings.backend.general.actions') &nbsp;</th>
                            @else
                                <th>@lang('strings.backend.general.actions') &nbsp;</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($notices as $item)
                        <tr>

                            <td>{{ $i }}</td>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->created_at->format('d M Y, h:i A') }}</td>

                            <td>
                                <a href="{{ route("admin.$segment.show", $item->id) }}" class="btn btn-xs btn-primary mb-1"><i
                                        class="icon-eye"></i></a>

                                <a href="{{ route("admin.$segment.edit", $item->id) }}" class="btn btn-xs btn-info mb-1"><i
                                        class="icon-pencil"></i></a>

                                <a data-method="delete" data-trans-button-cancel="Cancel" data-trans-button-confirm="Delete"
                                    data-trans-title="Are you sure you want to do this?"
                                    class="btn btn-xs btn-danger text-white mb-1" style="cursor:pointer;"
                                    onclick="$(this).find('form').submit();">
                                    <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="Delete"></i>
                                    <form action="{{ route("admin.$segment.destroy", $item->id) }}" method="POST" name="delete_item"
                                        style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </a>
                            </td>
                            @php
                                $i++;
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

@endsection

@push('after-scripts')
    <script>


    </script>
@endpush
