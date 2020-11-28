@if (Auth::user()->type == 'admin')
<div style="width: 350px" >
    @if (Auth::user()->_can('student_assign_course'))
    <i class="fa fa-th-list w3-text-purple w3-button" onclick="edit('{{ url('/dashboard/studentcourse/assign') . '/' . $course->id }}', 'assignModal', 'assign-modal-place')" > {{ __('assign course to students') }} </i>
    @endif
    
    @if (Auth::user()->_can('edit course'))
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/dashboard/course/edit') . '/' . $course->id }}')" ></i>
    @endif
    
    @if (Auth::user()->_can('doctor_assign_course'))
    <i class="fa fa-address-book-o w3-text-pink w3-button" onclick="edit('{{ url('/dashboard/course/assign') . '/' . $course->id }}', 'assignModal', 'assign-modal-place')" > {{ __('assign course to doctors') }} </i>
    @endif
    
    @if (Auth::user()->_can('remove course'))
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/dashboard/course/remove/') .'/' . $course->id }}')" ></i>
    @endif
    
    @if (Auth::user()->_can('show_register_student'))
    <i class="fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $course->id }}')" > {{ __('register student') }} </i>
    @endif
</div>
@endif

@if (Auth::user()->type == 'doctor') 
    <i class="fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $course->id }}')" > {{ __('register student') }} </i>
@endif


@if (Auth::user()->type == 'student')
    <?php
        $item = Auth::user()->toStudent()->researchs()->where("course_id", $course->id)->first();
        
    ?>  
    
    
    @if (optional(App\Setting::find(9))->value && in_array(optional(App\StudentResearch::where('course_id', $course->id)->where('student_id', Auth::user()->id)->first())->result_id, [2, 3]))
    <a 
        style="margin: 3px"
        role="button"
        onclick="edit('{{ url('/dashboard/myresearch/edit') . '/' . $course->id }}')" 
        class="btn btn-flat btn-warning" >{{ __('remove old and upload new') }}</a>
    @endif
    
    @if (optional(App\Setting::find(9))->value && !App\StudentResearch::where('course_id', $course->id)->where('student_id', Auth::user()->id)->first())
    <button 
        style="margin: 3px"
        onclick="edit('{{ url('/dashboard/myresearch/edit') . '/' . $course->id }}')"
        class="btn btn-flat btn-primary" >{{ __('upload my research') }}</button> 
    @endif
     
    
    
    
    
    
    @if (App\StudentResearch::where('course_id', $course->id)->where('student_id', Auth::user()->id)->first())
    <!--
    <a 
        style="margin: 3px"
        role="button"
        onclick="edit('{{ url('/dashboard/myresearch/edit') . '/' . $course->id }}')" 
        class="btn btn-flat btn-warning" >{{ __('remove old and upload new') }}</a>
        
        -->
    <a 
        style="margin: 3px"
        role="button"
        onclick="edit('{{ url('/dashboard/myresearch/show') . '/' . $course->id }}')" 
        class="btn btn-flat btn-success" >{{ __('show course research') }}</a> 
    @endif
@endif