@can('scenario-five-all-delete')
@php
    $latestGenerated = \App\Models\LogXMLGenActivity::where('scenario_no', 5)
        ->orderBy('created_at', 'desc')
        ->first();
@endphp
@if($latestGenerated && $latestGenerated->id == $id)
    @php ($status_class ='fa fa-trash')
    <button class="btn-delete" value="{{ $id }}"><i class="{{ $status_class }}"></i></button>
@else
    @php ($status_class ='fa fa-ban')
    <i class="{{ $status_class }}"></i>
@endif

@endcan
