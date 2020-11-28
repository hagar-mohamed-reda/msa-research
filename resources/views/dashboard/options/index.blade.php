 
<style>
    label {
        color: black!important;
    }
</style>
<br>
<br>
<section class="content-header font">
    <h1 class="font" >
        options 
    </h1>
    <ol class="breadcrumb font">
        <li><a href="{{ url('/') }}/dashboard"><i class="fa fa-dashboard"></i>{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('settings') }}</li>
    </ol>
</section>


<!-- Main content -->
<section class="content w3-margin" style="direction: ltr">   
    
    <!-- theme section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive row">   
        <div class="form-group w3-padding col-lg-12 col-md-12 col-sm-12">
            <label class="w3-xlarge" for="email">{{ __('theme') }}</label> 
            <div class="w3-large w3-text-gray" >
                {{ __('reload the page to see the changes') }}
            </div>
            <ul class="w3-ul row" >
                
                <li class=" col-lg-2 col-md-2 col-sm-2 w3-padding text-center" style="float: right" >
                    <img src="{{ url('/image/theme/dark-blue.jpg') }}" width="50px" height="50px" class="w3-circle" />
                    <center>
                        <span class="w3-large" >{{ __('blue sky night') }}</span>
                        <br>
                        <input type="radio" name="theme" onclick="editSetting('id=6&value=skin-blue', this)" >
                    </center>
                </li>
               
                <li class=" col-lg-2 col-md-2 col-sm-2 w3-padding text-center" style="float: right" >
                    <img src="{{ url('/image/theme/light-blue.jpg') }}" width="50px" height="50px" class="w3-circle" />
                    <center>
                        <span class="w3-large" >{{ __('light sky') }}</span>
                        <br>
                        <input type="radio" name="theme" onclick="editSetting('id=6&value=skin-blue-light', this)" >
                    </center>
                </li>
                
                <li class=" col-lg-2 col-md-2 col-sm-2 w3-padding text-center" style="float: right" >
                    <img src="{{ url('/image/theme/light-green.jpg') }}" width="50px" height="50px" class="w3-circle" />
                    <center>
                        <span class="w3-large" >{{ __('nature') }}</span>
                        <br>
                        <input type="radio" name="theme" onclick="editSetting('id=6&value=skin-green-light', this)" >
                    </center>
                </li>
                
                <li class=" col-lg-2 col-md-2 col-sm-2 w3-padding text-center" style="float: right" >
                    <img src="{{ url('/image/theme/colors.jpg') }}" width="50px" height="50px" class="w3-circle" />
                    <center>
                        <span class="w3-large" >{{ __('colorfully') }}</span>
                        <br>
                        <input type="radio" name="theme" onclick="editSetting('id=6&value=skin-dark-light', this)" >
                    </center>
                </li>
            </ul>
            <input type="hidden" id="theme" >
        </div> 
    </div> 
    <br>
      
    <!-- manager section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive row"> 
        <div class="form-group w3-padding col-lg-10 col-md-10 col-sm-10">
            <label class="w3-xlarge" for="email">{{ __('manager of faculty') }}</label> 
            <input  
                type="text" 
                class="form-control" 
                id="facultyManager" 
                required=""
                value="{{ optional(App\Setting::find(1))->value }}"
                placeholder="{{ __('manager of faculty') }}">
        </div>  
        <div class="form-group w3-padding col-lg-2 col-md-2 col-sm-2">
            <button class="btn w3-indigo shadow btn-sm" onclick="editSetting('id=1&value='+$('#facultyManager').val(), this)" >
                <i class="fa fa-check" ></i> {{ __('save') }}
            </button>
        </div>
    </div> 
    <br>
    
    <!-- manager section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive row"> 
        <div class="form-group w3-padding col-lg-10 col-md-10 col-sm-10">
            <label class="w3-xlarge" for="email">{{ __('name of faculty') }}</label> 
            <input  
                type="text" 
                class="form-control" 
                required=""
                id="facultyName" 
                value="{{ optional(App\Setting::find(5))->value }}"
                placeholder="{{ __('name of faculty') }}">
        </div>  
        <div class="form-group w3-padding col-lg-2 col-md-2 col-sm-2">
            <button class="btn w3-indigo shadow btn-sm" onclick="editSetting('id=5&value='+$('#facultyName').val(), this)" >
                <i class="fa fa-check" ></i> {{ __('save') }}
            </button>
        </div>
    </div> 
    <br>
    
    <!-- manager section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive "> 
        <div class="form-group w3-padding">
            <label class="w3-xlarge" for="inputStudentGrade">{{ __('publish result of student grade') }}</label>
            <br>
            <input type="hidden" name="studentGrade" id="inputStudentGrade" value="{{ optional(App\Setting::find(10))->value }}">
            <div class="material-switch pull-left" style='direction: rtl' >
                <input id="active" type="checkbox" onchange="this.checked? $('#inputStudentGrade').val(1) : $('#inputStudentGrade').val(0)"
                {{ optional(App\Setting::find(10))->value==1? 'checked' : '' }}
                >
                <label for="active" class="label-primary "></label>
            </div> 
            
        </div>  
        <div class="form-group w3-padding col-lg-2 col-md-2 col-sm-2">
            <button class="btn w3-indigo shadow btn-sm" onclick="editSetting('id=10&value='+$('#inputStudentGrade').val(), this)" >
                <i class="fa fa-check" ></i> {{ __('save') }}
            </button>
        </div>
    </div> 
    <br>
    
    <!-- login background section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive row">
        <form class="form" action="{{ url('dashboard/option/update') }}" method="post" enctype="multipart/form-data"   >
            @csrf 
            <div class="form-group w3-padding col-lg-6 col-md-6 col-sm-12 ">
        <input type="hidden" name="id" value="3" >
        <label for="">{{ __('login background')  }} *</label>
        <div class="form-control cursor" onclick="$('.login-background').click()">
            <span class="fa fa-image w3-large"></span>
            <span> {{ __('max file size 3M') }} </span>
        </div>
        <br>
        <img class="imageView" onclick="viewImage(this)" style="cursor: pointer" width="50px" src="{{ url('images/login') }}/{{ optional(App\Setting::find(3))->value }}" >
        <input type="file" onchange="loadImage(this, event)" class="hidden login-background " required="" name="value"  accept="image/x-png,image/gif,image/jpeg" >
    </div>
        <div class="form-group w3-padding col-lg-2 col-md-2 col-sm-2">
            <button class="btn w3-indigo shadow btn-sm submit-button" type="submit"   >
                <i class="fa fa-check" ></i> {{ __('save') }}
            </button>
        </div>
    </form> 
    
    </div> 
    <br>
    
    <!-- logo background section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive row">
        <form class="form" action="{{ url('dashboard/option/update') }}" method="post" enctype="multipart/form-data"   >
            @csrf 
            <div class="form-group w3-padding col-lg-6 col-md-6 col-sm-12 ">
        <input type="hidden" name="id" value="4" >
        <label for="">{{ __('logo')  }} *</label>
        <div class="form-control cursor" onclick="$('.logo-background').click()">
            <span class="fa fa-image w3-large"></span>
            <span> {{ __('max file size 3M') }} </span>
        </div>
        <br>
        <img class="imageView" onclick="viewImage(this)" style="cursor: pointer" width="50px" src="{{ url('images/login') }}/{{ optional(App\Setting::find(4))->value }}" >
        <input type="file" onchange="loadImage(this, event)" class="hidden logo-background " required="" name="value"  accept="image/x-png,image/gif,image/jpeg" >
    </div>
        <div class="form-group w3-padding col-lg-2 col-md-2 col-sm-2">
            <button class="btn w3-indigo shadow btn-sm submit-button" type="submit"   >
                <i class="fa fa-check" ></i> {{ __('save') }}
            </button>
        </div>
    </form> 
    
    </div> 
    <br>
    
    <!-- research max date section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive row">
        <form class="form" action="{{ url('dashboard/research/max-date/update') }}" method="post" enctype="multipart/form-data"   >
            @csrf 
            <div class="form-group w3-padding col-lg-6 col-md-6 col-sm-12 "> 
                <label for="">{{ __('change researches max date')  }}  </label>
                <input type="date" name="max_date" class="form-control" value="{{ optional(App\Research::first())->max_date }}" >
            </div>
        <div class="form-group w3-padding col-lg-2 col-md-2 col-sm-2">
            <button class="btn w3-indigo shadow btn-sm submit-button" type="submit"   >
                <i class="fa fa-check" ></i> {{ __('save') }}
            </button>
        </div>
    </form> 
    
    </div> 
    <br>
    
    <!-- manager section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive row"> 
        <div class="form-group w3-padding col-lg-10 col-md-10 col-sm-10">
            <label class="w3-xlarge" for="email">{{ __('password of publish result') }}</label> 
            <input  
                type="password" 
                class="form-control" 
                id="publishPassword" 
                required=""
                value="{{ optional(App\Setting::find(7))->value }}"
                placeholder="{{ __('password of publish result') }}">
        </div>  
        <div class="form-group w3-padding col-lg-2 col-md-2 col-sm-2">
            <button class="btn w3-indigo shadow btn-sm" onclick="editSetting('id=7&value='+$('#publishPassword').val(), this)" >
                <i class="fa fa-check" ></i> {{ __('save') }}
            </button>
        </div>
    </div> 
    <br>
    
    <!-- manager section -->
    <div class="w3-white round shadow w3-animate-opacity table-responsive row"> 
        <div class="form-group w3-padding col-lg-10 col-md-10 col-sm-10">
            <label class="w3-xlarge" for="email">{{ __('password of student result') }}</label> 
            <input  
                type="password" 
                class="form-control" 
                id="studentResutlPassword" 
                required=""
                value="{{ optional(App\Setting::find(8))->value }}"
                placeholder="{{ __('password of publish result') }}">
        </div>  
        <div class="form-group w3-padding col-lg-2 col-md-2 col-sm-2">
            <button class="btn w3-indigo shadow btn-sm" onclick="editSetting('id=8&value='+$('#studentResutlPassword').val(), this)" >
                <i class="fa fa-check" ></i> {{ __('save') }}
            </button>
        </div>
    </div> 
    <br>
    
    <!-- email section -->
    <div class="w3-white round shadow w3-animate-opacity table-  row"> 
        <div class="form-group w3-padding ">
            <label class="w3-xlarge" for="email">{{ __('translation') }}</label>
            <div class="w3-large w3-text-gray" >
               {{ __('you can translate each word in English or Arabic') }}
            </div>
            <table class="table table-bordered" >
                <tr>
                    <th>{{ __('key') }}</th>
                    <th>{{ __('word in English') }}</th>
                    <th>{{ __('word in Arabic') }}</th>
                </tr>
                @foreach(App\Translation::all() as $item)
                <tr class="dictionary-item" data-id="{{ $item->id }}" >
                    <td>
                        {{ $item->key }}
                    </td>
                    <td> 
                        <input  
                            type="text" 
                            class="w3-input w3-block  word_en"   
                            value="{{ $item->word_en }}"
                            style="width: 200px"
                            placeholder="">
                    </td>
                    <td>
                        <input  
                            type="text" 
                            class="w3-input w3-block  word_ar"   
                            value="{{ $item->word_ar }}"
                            style="width: 200px"
                            placeholder="">
                    </td>
                </tr>
                @endforeach
            </table> 
            <br>
            <div class="form-group w3-padding ">
                <button class="btn w3-indigo shadow btn-sm" onclick="editTranslation(this)" >
                    <i class="fa fa-check" ></i> {{ __('save') }}
                </button>
            </div>
        </div>  
    </div> 
    <br>
    
    <!-- /.row -->
</section>

{{ csrf_field() }}

<script>
    function editSetting(values, button) {
        $(button).html('<i class="fa fa-spin fa-spinner" ></i>');
        $.get('{{ url("/dashboard/option/update?") }}' + values, function (r) {
            if (r.status == 1) {
                success(r.message);
                $(button).html(' <i class="fa fa-check" ></i> {{ __('save') }}');
            } else {
                error(r.message);
                $(button).html(' <i class="fa fa-check" ></i> {{ __('save') }}');
            }
            
            if (values.indexOf("id=6") >= 0 || values.indexOf("id=7") >= 0)
                window.location.reload();
        });
    }
    
    function editTranslation(button) {
        $(button).attr('disabled', 'disabled');
        $(button).html('<i class="fa fa-spin fa-spinner" ></i>');
        
        var translations = [];
        
        $(".dictionary-item").each(function(){
            var item = {};
            item.id = $(this).attr('data-id');
            item.word_en = $(this).find(".word_en").val();
            item.word_ar = $(this).find(".word_ar").val();
            
            translations.push(item);
        }); 
        
        var data = {
            translations: JSON.stringify(translations),
            _token: '{{ csrf_token() }}'
        };
        
        $.post('{{ url("/dashboard/translation/update?") }}', $.param(data), function(r){
            if (r.status == 1) {
                success(r.message); 
            } else {
                error(r.message); 
            }
            $(button).removeAttr("disabled");
            $(button).html(' <i class="fa fa-check" ></i> {{ __('save') }}');
        });
    }
     
    
    $(document).ready(function(){ 
        formAjax(false, function(r){ 
        }); 
    });
</script> 
