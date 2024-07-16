{{-- @extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.challenge.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.categories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.challenge.fields.challenge_name') }}</label>
                <input class="form-control {{ $errors->has('challenge_name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('challenge_name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection --}}


<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.challenge.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Challenge Name Field -->
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.challenge.fields.challenge_name') }}</label>
                <input class="form-control {{ $errors->has('challenge_name') ? 'is-invalid' : '' }}" type="text" name="challenge_name" id="name" value="{{ old('challenge_name', '') }}" required>
                @if($errors->has('challenge_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('challenge_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.challenge.fields.challenge_name_helper') }}</span>
            </div>

            <!-- Start Date Field -->
            <div class="form-group">
                <label class="required" for="start_date">{{ trans('cruds.challenge.fields.start_date') }}</label>
                <input class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="date" name="start_date" id="start_date" value="{{ old('start_date', '') }}" required>
                @if($errors->has('start_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('start_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.challenge.fields.start_date_helper') }}</span>
            </div>

            <!-- End Date Field -->
            <div class="form-group">
                <label class="required" for="end_date">{{ trans('cruds.challenge.fields.end_date') }}</label>
                <input class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="date" name="end_date" id="end_date" value="{{ old('end_date', '') }}" required>
                @if($errors->has('end_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('end_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.challenge.fields.end_date_helper') }}</span>
            </div>

            <!-- Duration Field -->
            <div class="form-group">
                <label class="required" for="duration">{{ trans('cruds.challenge.fields.duration') }}</label>
                <input class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}" type="text" name="duration" id="duration" value="{{ old('duration', '') }}" required>
                @if($errors->has('duration'))
                    <div class="invalid-feedback">
                        {{ $errors->first('duration') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.challenge.fields.duration_helper') }}</span>
            </div>

            <!-- Number of Questions Field -->
            <div class="form-group">
                <label class="required" for="num_questions">{{ trans('cruds.challenge.fields.num_questions') }}</label>
                <input class="form-control {{ $errors->has('num_questions') ? 'is-invalid' : '' }}" type="number" name="num_questions" id="num_questions" value="{{ old('num_questions', '') }}" required>
                @if($errors->has('num_questions'))
                    <div class="invalid-feedback">
                        {{ $errors->first('num_questions') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.challenge.fields.num_questions_helper') }}</span>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
