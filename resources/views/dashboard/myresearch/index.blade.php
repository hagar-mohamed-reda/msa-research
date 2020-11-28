@extends("dashboard.layout.app")

@section("title")
{{ __('my researchs') }}
@endsection 

@section("content")
<div>
    <div class="w3-block" >
        @foreach(Auth::user()->toStudent()->courses()->get() as $item)
        <span class="btn btn-default w3-round-xxlarge" style="margin: 2px" onclick="$('.research-list-item').hide();$('.research-course-{{ $item->id }}').show()" >{{ $item->name }}</span>
        @endforeach
        <br>
        <span class="btn btn-default w3-round-xxlarge" style="margin: 2px" onclick="$('.research-list-item').hide();$('.research-new').show()" >{{ __('new research') }}</span>
        <span class="btn btn-default w3-round-xxlarge" style="margin: 2px" onclick="$('.research-list-item').hide();$('.research-uploaded').show()" >{{ __('old research') }}</span>
        <br>
        <ul class="w3-ul" >
            
        <li style="margin-bottom: 5px;padding: 0px" >
            <div class="shadow-1 w3-block w3-padding w3-display-container" style="border-radius: 2px;" >
                <input class="w3-input w3-block" onkeyup="searchResearch(this.value)" placeholder="{{ __('search about research') }}" >
            </div>
        </li>
        </ul>
    </div>
    <ul class="w3-ul" style="height: 400px;overflow: auto;padding: 4px" >
        @foreach(Auth::user()->toStudent()->researchs()->get() as $item)
        <?php 
            $color = App\helper\Helper::randColor();
        ?>
        <li 
            class="research-list-item research-course-{{ $item->course_id }} research-{{ Auth::user()->toStudent()->isUploadFileForResearch($item->id)? 'uploaded' : 'new' }}" 
            style="margin-bottom: 5px;padding: 0px" >
            <div class="media shadow-1 w3-block w3-padding w3-display-container" style="border-radius: 2px;" >
                <div class="media-left">
                  <a href="#" style="padding: 5px" >
                      <button type="button" class="btn w3-circle fa fa-newspaper-o {{ $color }}" style="width: 40px;height: 40px" ></button>
                  </a>
                </div> 
                <div class="media-body">
                  <div class="media-heading font w3-large">{{ $item->title }}</div>
                  <ul>
                      <li>
                          {{ __('doctor') }} / {{ optional($item->doctor)->name }}
                      </li>
                      <li>
                          {{ __('course') }} / {{ optional($item->course)->name }}
                      </li>
                      <li>
                          {{ __('max_date') }} / <b class="{{ strtotime(date('Y-m-d')) > strtotime($item->max_date)? 'w3-text-red' : 'w3-text-green' }}" >{{ $item->max_date }}</b>
                      </li>
                      <li>
                          {{ __('file') }} / <a href="#" data-src="{{ $item->file_url }}" onclick="viewFile(this)" >{{ __('show research file') }}</a>
                      </li>
                      <li>
                          {{ __('description') }} / <br> {!! str_replace("\n", "<br>", $item->description) !!}
                      </li>
                      <li>
                          {{ __('requirements') }} / <br> {!! str_replace("\n", "<br>", $item->requirements) !!}
                      </li>
                      <li>
                          <!-- edit('{{ url('/dashboard/myresearch/edit') . '/' . $item->course_id }}') -->
                          <button 
                              onclick="showPage('dashboard/course')"
                              class="btn btn-flat {{ $color }}" >{{ __('upload my research') }}</button>
                      </li>
                  </ul>
                  <div class="w3-text-gray" >{{ $item->notes }}</div>
                  
                  <div class="w3-display-topleft w3-padding" >  
                  </div>
                </div>
          </div>
        </li>
        @endforeach
    </ul> 
</div> 

@endsection

@section("additional") 


<!-- edit modal -->
<div class="modal fade"  role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('upload your file') }}</center>
      </div>
      <div class="modal-body editModalPlace">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section("js")
 
<script>
    $('.app-add-button').remove();
    function searchResearch(key) {
        if (key.length <= 0)
            return $(".research-list-item").show();
        
        $(".research-list-item").hide();
        $(".research-list-item").each(function(){
            if ($(this).text().indexOf(key) >= 0) {
                $(this).show();
            }
        });
    }
$(document).ready(function() { 

     formAjax(function(){
         showPage('dashboard/myresearch');
     });

});
</script>
@endsection
