 @can('vehicle-make-edit')  
 @if($status == '1')
   @php ($status_class ='fa fa-check')
   @php ($title ='Click to inactivate')
 
 @else
   @php ($status_class ='fa fa-remove')
   @php ($title ='Click to activate')
 
 @endif
 <a href="{{ route('status-vehiclemake',encrypt($id)) }}"><i class="{{ $status_class }}" title="{{$title}}"></i></a>
 @endcan
 