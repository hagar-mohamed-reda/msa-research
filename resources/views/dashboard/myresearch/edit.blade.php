<div class="w3-padding" >
    <center>
        <h2 class='upload-research-title hidden' >{{ __('researches of course ') }} {{ $course->name }}</h2>
    </center>
    <br>
    <div>
    
    <!--
    if (Auth::user()->toStudent()->isUploadFileForCourse($course->id))
    <center>
        <b class="w3-text-red w3-xxlarge" >{{ __('your can not upload your research again') }}</b>
        <br>
        <br>
        <a href="#" onclick="viewFile(this)" class="btn btn-primary" data-src="{{ optional(Auth::user()->toStudent()->getUploadedFileForCourse($course->id))->file_url }}" >
            {{ optional(Auth::user()->toStudent()->getUploadedFileForResearch($course->id))->file }}
        </a>
    </center>
    else 
    -->
     
    @if (!App\StudentResearch::where('course_id', $course->id)->where('student_id', Auth::user()->id)->first() || in_array(optional(App\StudentResearch::where('course_id', $course->id)->where('student_id', Auth::user()->id)->first())->result_id, [2, 3]))
    @if (optional(App\Setting::find(9))->value) 
    <center>
        <h3 class="alert alert-danger" >{{ __('please select a research from researches ') }}</h3>
    </center>
    <form method="post" action="{{ url('dashboard/myresearch/store') }}/{{ $course->id }}" class="form" onsubmit="$(this).find('.upload-btn-form').hide(200)" >
        {{ csrf_field() }}
        
        <input type="hidden" name="research_id" class="research_id_value" value="{{ optional(Auth::user()->toStudent()->getUploadedFileForCourse($course->id))->research_id }}" >
        
        
        <ul class="w3-ul" > 
            @foreach(App\Research::where('course_id', $course->id)->get() as $item)
            <?php 
                $color = App\helper\Helper::randColor();
            ?>
            <li class="w3-white" >
                <div class="media">
                  <div class="media-left">
                    <a href="#">
                      <button 
                      onclick="$(this).find('input')[0].checked=true;$('.research_id_value').val('{{ $item->id }}')"
                      style="width: 60px;height: 60px;" 
                      class="btn w3-circle {{ App\helper\Helper::randColor() }}" >
                          <input 
                          type="radio" 
                          class="w3-check"
                          {{ optional(Auth::user()->toStudent()->getUploadedFileForCourse($course->id))->research_id == $item->id? 'checked' : '' }}
                          name="research-radio" 
                          onclick="$('.research_id_value').val('{{ $item->id }}')" required="" >
                      </button>
                    </a>
                  </div>
                  <div class="media-body w3-padding">
                    <h4 class="media-heading font">{{ $item->title }}</h4>
                    {!! str_replace("\n", "<br>", $item->requirements) !!} <br>
                    <a href="#" class="w3-text-blue" onclick="$('.research-details-{{ $item->id }}').slideToggle(500)" >{{ __('show more') }}</a>
                    
                      <ul class="research-details-{{ $item->id }}" style="display: none" >
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
                      </ul>
                  </div>
                </div>
            </li>
            @endforeach
        </ul>
        <br>
        <br>
        <center>
            <span class="btn fa fa-cloud-upload w3-text-indigo w3-jumbo" onclick="$('.upload-research').click()" ></span>
            
            <br>
            <br>
            <span class="fileView w3-large btn btn-primary" ></span>
        </center>
        
        <input type="file" accept="application/pdf" name="file" required="" onchange="loadFile(this, event)" class="hidden upload-research" >
    
        <br>
        <button class="btn btn-primary w3-block upload-btn-form" type="submit" >{{ __('upload') }}</button>
    </form>  
    @endif
    @endif
    
</div>
</div>

<script>
    $('.modal-title').html($('.upload-research-title').html());
</script>