@if ($problem->status == 'default')
<div style="width: 220px" style="form-group form-inline" > 
    <select class="form-control problem-{{ $problem->id }}"  >
        <option value="" >{{ __('change status') }}</option>  
        <option value="success" >{{ __('success') }}</option> 
        <option value="warning" >{{ __('warning') }}</option> 
        <option value="error" >{{ __('error') }}</option> 
    </select>
    <br>
    <textarea class="form-control problem-comment-{{ $problem->id }}" placeholder="{{ __('write a comment') }}" ></textarea>
    <button class="btn btn-success" onclick="updateStatus('{{ $problem->id }}', $('.problem-{{ $problem->id }}').val(), $('.problem-comment-{{ $problem->id }}').val(), this)" >
        <i class="fa fa-check" ></i>
    </button> 
</div>
@endif