<div class="w3-padding" >
    <center>
        <h2 class='upload-research-title hidden' >{{ __('my research of course') }} {{ $course->name }}</h2>
    </center>
    <br>
    
    @if ($studentResearch->admin_publish == 1)
    <div class="alert alert-danger" style="direction: rtl" >
        {{ __('researches notes result') }}
    </div>
    <br>
    @endif
    
    <ul class="w3-large" >
        <li>
            <b>{{ __('research_title') }}</b> : {{ optional($studentResearch->research)->title }}
        </li>
        <li>
            <b>{{ __('file uploaded') }}</b> : <a href="#" class='w3-text-blue' data-src="{{ $studentResearch->file_url }}" onclick="viewFile(this)" >{{ __('show file') }}</a>
        </li>
        <li>
            <b>{{ __('upload date') }}</b> : {{ $studentResearch->upload_date }}
        </li>
        @if ($studentResearch->admin_publish == 1)
        @if (optional($studentResearch->student)->can_see_result == 1)
        <li>
            <b>{{ __('result') }}</b> : {{ optional($studentResearch->result)->name }}
        </li>
        @else
        <li>
            <b>{{ __('you_cant_see_result') }}</b>
        </li>
        @endif
        @endif
    </ul>
</div>
<script>
    $('.modal-title').html($('.upload-research-title').html());
</script>