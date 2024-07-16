{{-- @extends('layouts.admin')
@section('content')
@can('challenge_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.categories.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.category.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.challenge.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Challenge">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.challenge.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.challenge.fields.challenge_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.challenge.fields.start_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.challenge.fields.end_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.challenge.fields.duration') }}
                        </th>
                        <th>
                            {{ trans('cruds.challenge.fields.num_questions') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($challenges as $key => $challenge)
                        <tr data-entry-id="{{ $challenge->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $challenge->id ?? '' }}
                            </td>
                            <td>
                                {{ $challenge->challenge_name ?? '' }}
                            </td>
                            <td>
                                {{ $challenge->start_date?? '' }}
                            </td>
                            <td>
                                {{ $challenge->end_date ?? '' }}
                            </td>
                            <td>
                                {{ $challenge->duration ?? '' }}
                            </td>
                            <td>
                                {{ $challenge->num_questions?? '' }}
                            </td>
                            <td>
                                @can('challenge_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.categories.show', $challenge->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('category_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.categories.edit', $challenge->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('category_delete')
                                    <form action="{{ route('admin.categories.destroy', $challenge->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('category_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.categories.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-Category:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection --}}




@can('challenge_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.challenges.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.challenge.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.challenge.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Challenge">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.challenge.fields.id') }}</th>
                        <th>{{ trans('cruds.challenge.fields.challenge_name') }}</th>
                        <th>{{ trans('cruds.challenge.fields.start_date') }}</th>
                        <th>{{ trans('cruds.challenge.fields.end_date') }}</th>
                        <th>{{ trans('cruds.challenge.fields.duration') }}</th>
                        <th>{{ trans('cruds.challenge.fields.num_questions') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($challenges as $key => $challenge)
                        <tr data-entry-id="{{ $challenge->id }}">
                            <td></td>
                            <td>{{ $challenge->id ?? '' }}</td>
                            <td>{{ $challenge->challenge_name ?? '' }}</td>
                            <td>{{ $challenge->start_date->format('d-m-y') ?? '' }}</td>
                            <td>{{ $challenge->end_date->format('d-m-y') ?? '' }}</td>
                            <td>{{ $challenge->duration ?? '' }}</td>
                            <td>{{ $challenge->num_questions ?? '' }}</td>
                            <td>
                                @can('challenge_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.challenges.show', $challenge->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('challenge_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.challenges.edit', $challenge->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('challenge_delete')
                                    <form action="{{ route('admin.challenges.destroy', $challenge->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('challenge_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.challenges.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')
                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: { ids: ids, _method: 'DELETE' }
                        }).done(function () { location.reload() })
                    }
                }
            }
            dtButtons.push(deleteButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        });
        $('.datatable-Challenge:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>

