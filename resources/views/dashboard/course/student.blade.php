<?php 
    $course = App\Course::find(request()->course_id);
?>
<div class="w3-display-container" >
    <input type="hidden" name="student_id[]" value="{{ $student->id }}" >
                <div class="media-body">
                      <div class="media-heading font w3-large">{{ $student->name }}</div>
                  <div class="w3-text-gray" >{{ $student->notes }}</div>
                  
                    <div class="form-group has-feedback">
                        <label>{{ __('times') }}</label>
                        <input required="" type="number" min="1" name="times[]" class="form-control" value="1" placeholder="{{ __('times') }}"> 
                    </div>
                  
                  <div class="w3-display-topleft w3-padding" > 
                      <div class="material-switch pull-right w3-margin-top">
                            <input 
                                id="studentSwitch{{ $student->id }}" 
                                {{ $course->hasStudent($student->id)? 'checked' : '' }}
                                value="{{ $course->hasStudent($student->id)? '1' : '0' }}"
                                name="assign[]"  
                                onclick="setTimeout(function(){$('.student-assign-course-form').submit()}, 1000)"
                                onchange="this.checked? this.value = 1 : this.value = 0"
                                type="checkbox"/>
                            <label for="studentSwitch{{ $student->id }}" class="label-primary"></label>
                      </div>
                  </div>
                </div>
</div>