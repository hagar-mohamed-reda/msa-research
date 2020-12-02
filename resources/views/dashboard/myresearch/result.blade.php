@extends("dashboard.layout.app")

@section("title")
{{ __('student result') }}
@endsection

@section("content")
<div>
    @if (Auth::user()->type == 'student')
    <div class="alert alert-danger" style="direction: rtl" >
        {{ __('researches notes result') }}
    </div>
    <br>
    @endif
    <div class="w3-block" >

    </div>
    <table class="table table-bordered" id="table" >
        <thead>
        <tr class="w3-dark-gray" >
            <th>#</th>
            <th>{{ __('course') }}</th>
            <th>{{ __('research_title') }}</th>
            <th>{{ __('file uploaded') }}</th>
            <th>{{ __('upload date') }}</th>
            <th>{{ __('result') }}</th>
        </tr>
        </thead>
        <tbody>
            <?php
                $canSeeResultMsgShow = false;
            ?>
            @foreach(Auth::user()->toStudent()->courses()->get() as $item)
            @php
                $research = App\StudentResearch::where('student_id', Auth::user()->fid)->where('course_id', $item->id)->first();
            @endphp
            @if ($research)


                @if ($research->admin_publish == 1)
                @if (optional($research->student)->can_see_result == 1)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $item->name }}
                    </td>
                    <td>
                        {{ optional($research->research)->title }}
                    </td>
                    <td>
                        <a href="#" class='w3-text-blue' data-src="{{ $research->file_url }}" onclick="viewFile(this)" >{{ __('show file') }}</a>
                    </td>
                    <td>
                        {{ $research->upload_date }}
                    </td>
                    <td>
                        @if ($research)
                        {{ optional($research->result)->name }}
                        @else
                        failed
                        @endif
                    </td>
                </tr>
                @else

                @if (!$canSeeResultMsgShow)
                <?php $canSeeResultMsgShow = true; ?>
                <tr>
                    <td class="alert alert-danger" colspan="6" >
                        <b>{{ __('you_cant_see_result') }}</b>
                    </td>
                </tr>
                @endif

                @endif
                @endif

            @else
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ $item->name }}
                </td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td  >
                    failed
                    <br>
                    <b class="w3-text-red" >({{ __('you have no research uploaded') }})</b>
                </td>
            </tr>
            @endif

            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section("js")

<script>
$('.app-add-button').remove();
var table = null;

$(document).ready(function() {
        table = $('#table').DataTable({
            "paging": false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'print'
                //'csvHtml5',
                //'pdfHtml5'
            ],
         });

});
</script>
@endsection
