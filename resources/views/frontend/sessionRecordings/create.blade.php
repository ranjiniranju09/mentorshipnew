@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.sessionRecording.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.session-recordings.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.sessionRecording.fields.session_type') }}</label>
                            <select class="form-control" name="session_type" id="session_type" required>
                                <option value disabled {{ old('session_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\SessionRecording::SESSION_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('session_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('session_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('session_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sessionRecording.fields.session_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="session_title_id">{{ trans('cruds.sessionRecording.fields.session_title') }}</label>
                            <select class="form-control select2" name="session_title_id" id="session_title_id" required>
                                @foreach($session_titles as $id => $entry)
                                    <option value="{{ $id }}" {{ old('session_title_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('session_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('session_title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sessionRecording.fields.session_title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="session_video_file">{{ trans('cruds.sessionRecording.fields.session_video_file') }}</label>
                            <div class="needsclick dropzone" id="session_video_file-dropzone">
                            </div>
                            @if($errors->has('session_video_file'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('session_video_file') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sessionRecording.fields.session_video_file_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var uploadedSessionVideoFileMap = {}
Dropzone.options.sessionVideoFileDropzone = {
    url: '{{ route('frontend.session-recordings.storeMedia') }}',
    maxFilesize: 2048, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2048
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="session_video_file[]" value="' + response.name + '">')
      uploadedSessionVideoFileMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedSessionVideoFileMap[file.name]
      }
      $('form').find('input[name="session_video_file[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($sessionRecording) && $sessionRecording->session_video_file)
          var files =
            {!! json_encode($sessionRecording->session_video_file) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="session_video_file[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection