{{-- @extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.category.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.id') }}
                        </th>
                        <td>
                            {{ $category->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.category.fields.name') }}
                        </th>
                        <td>
                            {{ $category->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection --}}



<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.challenge.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <!-- ID Field -->
                    <tr>
                        <th>
                            {{ trans('cruds.challenge.fields.id') }}
                        </th>
                        <td>
                            {{ $challenge->id }}
                        </td>
                    </tr>
                    <!-- Name Field -->
                    <tr>
                        <th>
                            {{ trans('cruds.challenge.fields.challenge_name') }}
                        </th>
                        <td>
                            {{ $challenge->name }}
                        </td>
                    </tr>
                    <!-- Start Date Field -->
                    <tr>
                        <th>
                            {{ trans('cruds.challenge.fields.start_date') }}
                        </th>
                        <td>
                            {{ $challenge->start_date }}
                        </td>
                    </tr>
                    <!-- End Date Field -->
                    <tr>
                        <th>
                            {{ trans('cruds.challenge.fields.end_date') }}
                        </th>
                        <td>
                            {{ $challenge->end_date }}
                        </td>
                    </tr>
                    <!-- Duration Field -->
                    <tr>
                        <th>
                            {{ trans('cruds.challenge.fields.duration') }}
                        </th>
                        <td>
                            {{ $challenge->duration }}
                        </td>
                    </tr>
                    <!-- Number of Questions Field -->
                    <tr>
                        <th>
                            {{ trans('cruds.challenge.fields.num_questions') }}
                        </th>
                        <td>
                            {{ $challenge->num_questions }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>
