 
<style>
    label {
        color: black!important;
    }
</style>
<br>
<br>
<section class="content-header font">
    <h1 class="font" >
        {{ __('publish_result') }} 
    </h1>
    <ol class="breadcrumb font">
        <li><a href="{{ url('/') }}/dashboard"><i class="fa fa-dashboard"></i>{{ __('dashboard') }}</a></li> 
        <li class="active">{{ __('publish_result') }}</li>
    </ol>
</section>


<div class="w3-white round shadow w3-animate-opacity table-responsive row w3-padding w3-margin">  
    @if(request()->password == optional(App\Setting::find(7))->value && request()->password)
    <!-- Main content -->
    <section class="content w3-margin" style="direction: ltr">
        <center>
            <button class="btn btn-danger btn-flat shadow w3-xlarge" onclick="publishResult(1, this)" >{{ __('publish result for all students') }}</button>
            <br>
            <br>
            <br>
            <button class="btn btn-danger btn-flat shadow w3-xlarge" onclick="publishResult(0, this)" >{{ __('un publish result for all students') }}</button>
        </center>
    </section>
    @else 
    <section class="content w3-margin" style="direction: ltr">
        <div> 
            <div class="form-group has-feedback">
                <label>ادخل كلمة السر الخاصه باعتماد النتيجه</label>
                <input required="" type="password" name="password" class="form-control password" placeholder="{{ __('password') }}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <center class="form-group w3-center" >
                <button class="btn btn-primary" onclick="login()" >{{ __('login') }}</button>
            </center>
        </div>
         
        <!-- /.row -->
    </section>
    @endif
</div>


{{ csrf_field() }}

<script>
    @if (request()->password) 
        @if(request()->password != optional(App\Setting::find(7))->value)
        error("{{ __('password error') }}");
        @endif
    @endif

    function login() {
        var password = $('.password').val();
        if (password.length <= 0)
            return error("{{ __('enter the password') }}");
        
        showPage('dashboard/studentresearch/admin-publish?password='+password);
    }
    
    function publishResult(publish,  button) {
        var html = $(button).html();
        $(button).attr('disabled', 'disabled');
        $(button).html('<i class="fa fa-spin fa-spinner" ></i>');
        
        var data = {
            _token: '{{ csrf_token() }}',
            publish: publish
        };
        
        $.post("{{ url('dashboard/studentresearch/publish-result') }}", $.param(data), function(r){
            
            if (r.status == 1) {
                success(r.message); 
            } else {
                error(r.message); 
            }
            $(button).removeAttr("disabled");
            $(button).html(html);
        });
    }
    
     
    
    $(document).ready(function(){ 
        formAjax(false, function(r){ 
        }); 
    });
</script> 
