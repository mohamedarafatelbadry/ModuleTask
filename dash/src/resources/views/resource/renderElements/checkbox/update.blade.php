@if($field['show_rules']['showInUpdate'])
@php
$default = isset($field['default'])?$field['default']:$model->{$field['attribute']};
$value = isset($field['trueVal'])?$field['trueVal']:$model->{$field['attribute']};
$col = isset($field['columnWhenUpdate'])?$field['columnWhenUpdate']:$field['column'];
@endphp
<div class="col-lg-{{ $col }} col-md-{{ $col }} col-sm-12 col-xs-12 box_{{ $field['attribute'] }}">


	<label class="custom-switch" for="{{ $field['attribute'] }}">
        <input
        {{ isset($field['readonly']) && $field['readonly']?'readonly':'' }}
		{{ isset($field['checked']) && $field['checked']?'checked':'' }}
        type="checkbox" name="{{ $field['attribute'] }}" value="{{ $value }}" {{ $default == $value?'checked':'' }}
        id="{{ $field['attribute'] }}" class="custom-switch-input">
        <span class="custom-switch-indicator"></span>
        <span class="custom-switch-description">{{ $field['name'] }}
			@if(isset($field['rule']) && in_array('required',$field['rule']) || isset($field['ruleWhenUpdate']) && in_array('required',$field['ruleWhenUpdate']))
			<span class="text-danger text-sm">*</span>
			@endif
        </span>
    </label>
 


</div>
@endif

