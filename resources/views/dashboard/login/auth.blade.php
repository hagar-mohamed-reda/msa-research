<div class="auth-container {{ $type }}-container"  >
    <div>
        <p class="login-box-msg text-center">{{ __($type . ' register and login') }}</p>
    </div>
    <form action="{{ url('/') }}/dashboard/login" class="auth-card {{ $type }}-login-card" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="type" value="{{ $type }}" >
        <div class="form-group has-feedback">
            <input required="" type="text" name="phone" class="form-control" placeholder="{{ __('phone') }}">
            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input required="" type="password" name="password" class="form-control" placeholder="{{ __('password') }}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <br>
        <div class="">
            <!-- /.col -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('login') }}</button>

                @if ($type != 'admin')
                <button
                type="button"
                onclick="$('.auth-card').slideUp(500);$('.{{ $type }}-register-card').slideDown(500)"
                class="btn btn-success btn-block btn-flat">{{ __('create account') }}</button>
                @endif
            </div>
            <!-- /.col -->
        </div>
    </form>

    @if ($type != 'admin')
    <form action="{{ url('/') }}/dashboard/register" autocomplete="off" class="auth-card {{ $type }}-register-card" method="post" style="display: none">
        {{ csrf_field() }}
        <input   type="hidden" name="type" value="{{ $type }}" >
        <!--
        <div class="form-group has-feedback">
            <label>{{ __('name') }}</label>
            <input required="" type="text" name="name" class="form-control" placeholder="{{ __('name') }}">
            <span class="fa fa-user form-control-feedback"></span>
        </div>
        -->
        @if ($type == 'student')
        <div class="form-group has-feedback">
            <label>{{ __('code') }}</label>
            <input required="" type="text" name="code" class="form-control" placeholder="{{ __('code') }}">
            <span class="fa fa-code form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <label>{{ __('national_id') }}</label>
            <input required="" type="text" name="national_id" class="form-control" placeholder="{{ __('national_id') }}">
            <span class="fa fa-code form-control-feedback"></span>
        </div>
        @endif
        <div class="form-group has-feedback">
            <label>{{ __('phone') }}</label>
            <input required="" type="text" name="phone" autocomplete="off" class="form-control" placeholder="{{ __('phone') }}">
            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <label>{{ __('confirm phone') }}</label>
            <input required="" type="text" name="confirm_phone" autocomplete="off" class="form-control" placeholder="{{ __('confirm phone') }}">
            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <label>{{ __('password') }}</label>
            <input required="" type="password" name="password" autocomplete="off" class="form-control" placeholder="{{ __('password') }}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <label>{{ __('confirm password') }}</label>
            <input required="" type="password" name="confirm_password" autocomplete="off" class="form-control" placeholder="{{ __('confirm password') }}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <br>
        <div class="">
            <!-- /.col -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('submit') }}</button>
                <button
                type="button"
                onclick="$('.auth-card').slideUp(500);$('.{{ $type }}-login-card').slideDown(500)"
                class="btn btn-success btn-block btn-flat">{{ __('i have an account') }}</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
    @endif

    <form action="{{ url('/') }}/dashboard/forget-password" autocomplete="off" class="auth-card {{ $type }}-forget-card" method="post" style="display: none">
        {{ csrf_field() }}
        <p class="login-box-msg text-center">{{ __('your new password will send to you in sms code') }}</p>
        <input   type="hidden" name="type" value="{{ $type }}" >
        <div class="form-group has-feedback">
            <label>{{ __('phone') }}</label>
            <input required="" type="text" name="phone" autocomplete="off" class="form-control" placeholder="{{ __('phone') }}">
            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
        </div>
        <br>
        <div class="">
            <!-- /.col -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('submit') }}</button>
                <button
                type="button"
                onclick="$('.auth-card').slideUp(500);$('.{{ $type }}-login-card').slideDown(500)"
                class="btn btn-default btn-block btn-flat">{{ __('back') }}</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <form action="{{ url('/') }}/dashboard/confirm-account" autocomplete="off" class="auth-card {{ $type }}-confirm-card" method="post" style="display: none">
        {{ csrf_field() }}
        <input  type="hidden" name="type" value="{{ $type }}" >
        <div class="form-group has-feedback">
            <label>{{ __('phone') }}</label>
            <input required="" type="text" name="phone" autocomplete="off" class="form-control" placeholder="{{ __('phone') }}">
            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <label>{{ __('sms code') }}</label>
            <input required="" type="text" name="sms_code" autocomplete="off" class="form-control" placeholder="{{ __('phone') }}">
            <span class="fa fa-commenting-o form-control-feedback"></span>
        </div>
        <br>
        <div class="">
            <!-- /.col -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('submit') }}</button>
                <button
                type="button"
                onclick="$('.auth-card').slideUp(500);$('.{{ $type }}-login-card').slideDown(500)"
                class="btn btn-default btn-block btn-flat">{{ __('back') }}</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <div class="w3-row" >
        <div class="w3-col l6 m6 s12" >
            <a href="#"
            onclick="$('.auth-card').slideUp(500);$('.{{ $type }}-forget-card').slideDown(500)"
             >{{ __('forget password') }}</a>
        </div>
         
        <!--
        <div class="w3-col l6 m6 s12 text-right" >
            <a href="#"
            onclick="$('.auth-card').slideUp(500);$('.{{ $type }}-confirm-card').slideDown(500)"
             >{{ __('confirm my account') }}</a>
        </div>
        --> 
    </div>
    <br>
</div>
