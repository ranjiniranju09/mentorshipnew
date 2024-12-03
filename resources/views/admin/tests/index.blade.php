@extends('layouts.admin')
@section('content')
@can('test_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.tests.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.test.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Test', 'route' => 'admin.tests.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.test.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Test">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.test.fields.id') }}
                    </th>
                    {{--<th>
                        {{ trans('cruds.test.fields.course') }}
                    </th>--}}
                    <th>
                        Chapter
                    </th>
                    <th>
                        {{ trans('cruds.test.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.test.fields.description') }}
                    </th>
                    <th>
                        {{ trans('cruds.test.fields.is_published') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    {{--<td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($courses as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>--}}
                    <td>
                        {{--<select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($lessons as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>--}}
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->chaptername }}">{{ $chapter->chaptername }}</option>
                            @endforeach
                        </select>
                    </td>
                    

                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('test_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tests.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.tests.index') }}",
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'id', name: 'id' },
        { data: 'chaptername', name: 'chapters.chaptername' }, // Add chapter name
        { data: 'title', name: 'title' },
        { data: 'description', name: 'description' },
        { data: 'is_published', name: 'is_published' },
        { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[1, 'desc']],
    pageLength: 100,
};

  let table = $('.datatable-Test').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
});

</script>
@endsection