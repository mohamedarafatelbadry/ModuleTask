"data": null,
orderable: false,
searchable:false,
render: function(data,type,full,meta){
  {{-- Render Start --}}
@php
$method = $field['attribute'];
$types = $field['types'];
$listColumns = [];
foreach($types as $restype){
    $type = is_array($restype)?$restype[0]:$restype;
  if(class_exists($type)){
  $listColumns[] = [
    'title'=>$type::$title,
    'model'=>$type::$model,
    'resourceName'=>resourceShortName($type),
    'resource'=>$type
  ];
  }
}
@endphp
 var listColumns = {!! json_encode($listColumns,true,JSON_PRETTY_PRINT) !!}
 for(i in listColumns){
  if(data.{{ $method.'_type' }} == listColumns[i].model){
    var id           = data.{{ $method.'_id' }};
    var title        = data.{{ $method }}[listColumns[i].title];
    var resourceName = listColumns[i].resourceName;
    var url          = '{{ url(app('dash')['DASHBOARD_PATH'].'/resource') }}/'+resourceName+'/'+id;
    return  '<a href="'+url+'">'+title+'</a>';
  }
 }
  {{-- Render End --}}
}
