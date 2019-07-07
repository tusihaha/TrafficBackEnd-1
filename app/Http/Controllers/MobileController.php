<?php

namespace App\Http\Controllers;

use App\Jobs\ReadFileUploaded;
use App\Jobs\TestLog;
use App\Repository\Business;
use App\Repository\History;
use App\Repository\Rectangles;
use App\Repository\Markers;
// Make by Toan
use App\Repository\CellsDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;

class MobileController extends Controller
{
    //
    private $business;
    //
    function __construct()
    {
        $this->business = new Business();
    }
    //
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // Make by Manh
    // Modified by Toan
    public function getcurrent(Request $request){
        $mobile_user=User::select('id')->where('remember_token', $request->header('remember_token'))->first();
        $layer=$request->header('layer');
        if(!is_object($mobile_user)){
            $message = "Remember token error";
            $success = false;
            return response()->json(compact('success', 'message'));
        }
        if($layer === null) $layer = 1;
        $data = $this->business->fetch_current_cells($layer);
        $message = "success";
        $success = true;
        return response()->json(compact('data', 'success','message'));
    }
    public function getfuture(Request $request){
        $mobile_user=User::select('id')->where('remember_token', $request->header('remember_token'))->first();
        $layer=$request->header('layer');
        if(!is_object($mobile_user)){
            $message = "Remember token error";
            $success = false;
            return response()->json(compact('success', 'message'));
        }
        else if($layer === null) $layer = 1;
        $data = $this->business->fetch_future_cells($layer);
        $message = "success";
        $success = true;
        return response()->json(compact('data', 'success', 'message'));
    }
    public function addmarkers(Request $request){
        $mobile_user=User::select('id')->where('remember_token', $request->header('remember_token'))->first();
        if(!is_object($mobile_user)) {
            $success=false;
            $message="permission error!";
            return response()->json(compact('message', 'success'));
        }
        $markers= $request->input('markers');
        $this->business->add_new_marker($markers, $mobile_user->id);
        $success=true;
        $message="success";
        return response()->json(compact('message', 'success'));
    }
	// Make by Manh 
	public function userStatistic(Request $request){
		$users = User::select('id','email')->get();
		foreach ($users as $key => $value){
			$result[$value['email']] = Markers::select('id')->where('record_user',$value['id'])->get()->count();
		}
		return response()->json(compact('result'));
	}
	public function saveData(Request $request){
		try{
			$data = $request->input('data');
			foreach( $data as $key => $value ){
				$tosave = new CellsDetail();
				$tosave->x_axis = $value['x_axis'];
				$tosave->y_axis = $value['y_axis'];
				$tosave->start_time = $value['start_time'];
				$tosave->end_time = $value['end_time'];
				$tosave->id_cell = $value['id_cell'];
				$tosave->avg_speed = $value['avg_speed'];
				$tosave->marker_count = $value['marker_count'];
				$tosave->indicator = $value['indicator'];
				$tosave->algorithm = $value['algorithm'];
				$tosave->color = $value['color'];
				$tosave->save();
			}
			$success = true;
			$message = 'success';
			return response()->json(compact('success','message'));
		} catch( Exception $e ){
			$success = false;
			$message = $e->getMessage();
			return response()->json(compact('success','message'));
		}
	}
	//
    // Function make by Toan
    public function report(Request $request){
        $mobile_user=User::select('id')->where('remember_token', $request->header('remember_token'))->first();
        if(!is_object($mobile_user)) {
            $success=false;
            $message="permission error!";
            return response()->json(compact('message', 'success'));
        }
        $data = $request->all();
        if(!isset($data['IDMsg']) || !isset($data['lat']) || !isset($data['lng']) || !isset($data['time_stamp'])){
            $success=false;
            $message="variable error!";
            return response()->json(compact('message', 'success'));
        }
        $data['user_id'] = $mobile_user->id;
        $this->business->add_report($data);
        $success = true;
        $message = "success";
        return response()->json(compact('message', 'success'));
    }
    public function notification(Request $request){
        $mobile_user=User::select('id')->where('remember_token', $request->header('remember_token'))->first();
        if(!is_object($mobile_user)) {
            $success=false;
            $message="permission error!";
            return response()->json(compact('message', 'success'));
        }
        $data = $this->business->get_notification($request->header('time'));
        $success = true;
        $message = "success";
        return response()->json(compact('message', 'success', 'data'));
    }
}
