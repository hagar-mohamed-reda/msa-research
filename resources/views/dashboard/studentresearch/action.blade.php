@if (Auth::user()->type == 'doctor')
<div style="width: 320px" class="student-research-action-{{ $studentresearch->id }}" >
    @if ($studentresearch->canEditResult())
    <select class="form-control studentresearch-{{ $studentresearch->id }}"  >
        <option value="" >{{ __('change result') }}</option>
        <option value="" >{{ __('without result') }}</option>
        @foreach($studentresearch->results() as $item)
        <option value="{{ $item->id }}" >{{ $item->name }}</option>
        @endforeach
    </select>
    <button class="btn btn-success btn-flat" onclick="updateStatus('{{ $studentresearch->id }}', $('.studentresearch-{{ $studentresearch->id }}').val(), this)" >
        <i class="fa fa-check" ></i>
    </button>
    
    @if ($studentresearch->result_id != null)
    <button class="btn btn-warning btn-flat hidden publish-result" onclick="publishResult('{{ $studentresearch->id }}', this)" >
        {{ __('publish result') }}
    </button>
    @endif 
    @endif 
</div>
@endif

@if (Auth::user()->type == 'admin' && in_array(Auth::user()->id, [1 , 6900, 6898]))
<div style="width: 320px" class="student-research-action-{{ $studentresearch->id }}" >
    @if ($studentresearch->canEditResult())
    <select class="form-control studentresearch-{{ $studentresearch->id }}"  >
        <option value="" >{{ __('change result') }}</option>
        <option value="" >{{ __('without result') }}</option>
        @foreach($studentresearch->results() as $item)
        <option value="{{ $item->id }}" >{{ $item->name }}</option>
        @endforeach
    </select>
    <button class="btn btn-success btn-flat" onclick="updateStatus('{{ $studentresearch->id }}', $('.studentresearch-{{ $studentresearch->id }}').val(), this)" >
        <i class="fa fa-check" ></i>
    </button>
    
    @if ($studentresearch->result_id != null)
    <button class="btn btn-warning btn-flat hidden publish-result" onclick="publishResult('{{ $studentresearch->id }}', this)" >
        {{ __('publish result') }}
    </button>
    @endif 
    @endif 
</div>
@endif

