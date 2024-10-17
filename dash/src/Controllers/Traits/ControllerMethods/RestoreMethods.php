<?php
namespace Dash\Controllers\Traits\ControllerMethods;

trait RestoreMethods
{

    public function multi_restore()
    {
        if (count(request('ids'))) {
            $restore = $this->resource['model']::withTrashed()->whereIn('id', request('ids'))->get();
			foreach($restore as $model){
				$model->restore();
			}
            session()->flash('success', __('dash::dash.restored'));
        }
        return back();
    }

    public function restore($id)
    {
        $model = $this->resource['model']::withTrashed()->find($id);
		$model->restore();
        
        session()->flash('success', __('dash::dash.restored'));
        return back();
    }
}
