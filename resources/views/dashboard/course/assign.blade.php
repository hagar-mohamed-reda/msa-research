<center class="modal-title w3-xlarge">{{ __('assign course to doctors') }}</center>
<form  class="form" method="post" action="{{ url('dashboard/course/assign/update') }}/{{ $course->id }}" >
    @csrf
    
    <ul class="w3-ul" style="height: 400px;overflow: auto;padding: 4px" >
        <li style="margin-bottom: 5px;padding: 0px" >
            <div class="shadow-1 w3-block w3-padding w3-display-container" style="border-radius: 2px;" >
                <input class="w3-input w3-block" onkeyup="searchDoctor(this.value)" placeholder="{{ __('search about doctor') }}" >
            </div>
        </li>
        @foreach(App\User::doctors()->get() as $item)
        <li class="doctor-list-item" style="margin-bottom: 5px;padding: 0px" >
            <div class="media shadow-1 w3-block w3-padding w3-display-container" style="border-radius: 2px;" >
                <div class="media-left">
                  <a href="#" style="padding: 5px" >
                      <button type="button" class="btn w3-circle fa fa-user {{ App\helper\Helper::randColor() }}" style="width: 40px;height: 40px" ></button>
                  </a>
                </div>
                <input type="hidden" name="doctor_id[]" value="{{ $item->id }}" >
                <div class="media-body">
                  <div class="media-heading font w3-large">{{ $item->name }}</div>
                  <div class="w3-text-gray" >{{ $item->notes }}</div>
                  
                  <div class="w3-display-topleft w3-padding" > 
                      <div class="material-switch pull-right w3-margin-top">
                            <input 
                                id="doctorSwitch{{ $item->id }}" 
                                {{ $course->hasDoctor($item->id)? 'checked' : '' }}
                                value="{{ $course->hasDoctor($item->id)? '1' : '0' }}"
                                name="assign[]"  
                                onchange="this.checked? this.value = 1 : this.value = 0"
                                type="checkbox"/>
                            <label for="doctorSwitch{{ $item->id }}" class="label-primary"></label>
                      </div>
                  </div>
                </div>
          </div>
        </li>
        @endforeach
    </ul> 
    <center>
        <button class="btn btn-primary w3-block" >{{ __('save') }}</button>
    </center>
    
</form>