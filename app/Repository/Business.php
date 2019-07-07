<?php

namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

/**
 * contain all Business logic
 * Class Business
 * @package App\Repository
 */
class Business // extends Model
{
    private $rectangles;
    private $markers;
    private $cells;
    private $cellsdetail;
    private $future;
    
    public function findRectangleInclude($lat, $lng)
    {
        $grid = $this->getGrid();
        $north = $grid->north;
        $east = $grid->east;
        $south = $grid->south;
        $west = $grid->west;
        $height = $grid->height;
        $width = $grid->width;

        $whereX = floor(($lat - $south) / ($north - $south) * $height);
        $whereY = floor(($lng - $west) / ($east - $west) * $width);
        if ($whereX == $height) {
            $whereX--;
        } elseif ($whereX > $height || $whereX < 0) {
            return false;
        }
        if ($whereY == $width) {
            $whereY--;
        } elseif ($whereY > $width || $whereY < 0) {
            return false;
        }
        return ['whereX' => $whereX, 'whereY' => $whereY];
    }
    
    public function __construct()
    {
        $this->rectangles = new Rectangles();
        $this->markers = new Markers();
        $this->cells = new Cells();
        $this->cellsdetail = new CellsDetail();
        $this->future = new Future();
    }
    
    /**
     * @param $avg_speed
     * @return string
     *
     * calculate the color from avg_speed
     */
    public function getColor($avg_speed)
    {
        if ($avg_speed > 30) {
            return '#0000FF';
        } elseif ($avg_speed > 20) {
            return '#00FF00';
        } elseif ($avg_speed > 10) {
            return '#FFFF00';
        } else {
            return '#FF0000';
        }
    }
 
    public function fetchRectangle($zoom = 12, $indicator = 0)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh')->subMinutes(30);
        return $this->cellsdetail->select('id', 'x_axis', 'y_axis', 'color', 'avg_speed', 'marker_count')
            ->where('id_cell', $zoom - 10)
            ->where('algorithm', $indicator)
            ->where('end_time', '>=', $now)->get();
    }
    
    /**
     * @param UploadedFile $img
     * @param string $path
     * @return null|string Ten file img upload
     */
    public function saveImg($img, $path)
    {
        if ($img == null) {
            return null;
        }
        $err = null;
        $name = $img->getClientOriginalName();
        $ext = $img->getClientOriginalExtension();
        while (file_exists($path . '/' . $name)) {
            $name = str_random(5) . "_" . $name;
        }
        $arr_ext = ['png', 'jpg', 'gif', 'jpeg'];
        if (!in_array($ext, $arr_ext) || $img->getClientSize() > 500000) {
            $name = null;
            return redirect()->back()->with('not_img', 'Chọn file ảnh png jpg gif jpeg có kích thước < 5Mb');
        } else {
            $img->move($path, $name);
        }
        return $name;
    }
    
    public function getColorFromLevel($level)
    {
        if ($level == '1') {
            return '#FF0000';
        }
        if ($level == '2') {
            return '#FFFF00';
        }
        if ($level == '3') {
            return '#00FF00';
        }
        if ($level == '4') {
            return '#0000FF';
        }
        return '#808080';
    }
    
    public function getOriginColor($speed)
    {
        if ($speed <= 10) {
            return '#FF0000';
        }
        if ($speed <= 20) {
            return '#FFFF00';
        }
        if ($speed <= 30) {
            return '#00FF00';
        }
        return '#0000FF';
    }

    /**
     * Get level of traffic from color
     * @param $color
     * @return int
     */
    public function getLevelFromColor($color)
    {
        if ($color == "#FF0000") {
            return 1;
        }
        if ($color == "#FFFF00") {
            return 2;
        }
        if ($color == "#00FF00") {
            return 3;
        }
        if ($color == "#0000FF") {
            return 4;
        }
        return 0;
    }
    
    public function resetColors()
    {
        $rectangles = $this->fetchRectangle();
        foreach ($rectangles as $rectangle) {
            if ($rectangle->marker_count == 0) {
                $rectangle->color = '#808080';
            } else {
                $rectangle->color = $this->getOriginColor($rectangle->avg_speed);
            }
            $rectangle->save();
        }
    }
    
    public function readHistory($start_time, $indicator = 0, $zoom = 12)
    {
        $cellsdetail = $this->cellsdetail->select('x_axis', 'y_axis', 'color')
                    ->where('id_cell', $zoom - 10)
                    ->where('algorithm', $indicator)
                    ->where('start_time', '<=', $start_time)
                    ->where('end_time', '>=', $start_time)->get();
        return $cellsdetail;
    }
    
    private function isHoliday($date)
    {
        if ($date->isWeekend() ||
          ($date->day == '1' && $date->month == '1') ||
          ($date->day == '8' && $date->month == '3') ||
          ($date->day == '30' && $date->month == '4') ||
          ($date->day == '1' && $date->month == '5') ||
          ($date->day == '1' && $date->month == '6') ||
          ($date->day == '2' && $date->month == '9') ||
          ($date->day == '20' && $date->month == '10') ||
          ($date->day == '20' && $date->month == '11') ||
          ($date->day == '23' && $date->month == '12') ||
          ($date->day == '31' && $date->month == '12')
        ) {
            return true;
        } else {
            return false;
        }
    }
    
    public function calculateFuture($start_time = 'future_now', $indicator = 0, $zoom = 12)
    {
        if($start_time == "future_now") $future = $this->fetchRectangle($zoom, $indicator);
        else if($start_time == "future_thirty_minute"){
            $future = $this->future->select('x_axis', 'y_axis', 'color')
                ->where('destination_time', '<=', Carbon::now('Asia/Ho_Chi_Minh')->addMinutes(30))
                ->where('id_cell', $zoom - 10)
                ->where('algorithm', $indicator)->get();
        }
        return $future;
    }

    // Function make by Toan
    public function getGrid($zoom = 12){
        return $this->cells->select('height', 'width', 'west', 'east', 'south', 'north')->where('id', $zoom - 10)->first();
    }

    // Make by Manh
    // Modified by Toan
    //----------------------------------------BỔ SUNG---------------------------------------------------------------------------------------------------
    public function fetch_current_cells($layer){
        $now = Carbon::now('Asia/Ho_Chi_Minh')->subMinutes(30);
        $cell = $this->cells->select('height', 'width','east','west','south','north')->where('id',$layer)->first();
        $recgs = $this->cellsdetail->select('x_axis', 'y_axis','color')->where('end_time', '>=', $now)->where('id_cell',$layer)->where('algorithm', 0)->get();
        $data['grid'] = $cell;
        $data['cells']=$recgs;
        return $data;
    }
    public function fetch_future_cells($layer){
        $cell = $this->cells->select('height', 'width','east','west','south','north')->where('id',$layer)->first();
        $recgs = $this->future->select('x_axis', 'y_axis','color')->where('id_cell',$layer)->where('algorithm', 0)->get();
        $data['grid'] = $cell;
        $data['cells']=$recgs;
        return $data;
    }
    public function fetch_cells_in_the_past($layer, $time){
        $cell = $this->cells->select('height', 'width','east','west','south','north')->where('id',$layer)->first();
        $recgs = $this->cellsdetail->select('x_axis', 'y_axis', 'color')->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('id_cell',$layer)->get();
        $data['grid'] = $cell;
        $data['cells']=$recgs;
        return $data;
    }
    public function add_new_marker($markers, $record_user){
        foreach ($markers as $data) {
            $marker = new Markers();
            $marker->lat=$data['lat'];
            $marker->lng=$data['lng'];
            $marker->speed=$data['avg_speed'];
            $marker->vehicle=$data['vehicle'];
            $marker->direct=$data['direction'];
            $marker->record_user=$record_user;
            $marker->record_time=$data['record_time'];
            $marker->distance=$data['avg_speed']*5000;
            $marker->save();
        }
    }
    //--------------------------------------------------------------------------------------------------------------------------------------------------
    // Function make by Toan
    public function add_report($data){
        $report = new Report();
        $report->lat = $data['lat'];
        $report->lng = $data['lng'];
        $report->time_stamp = $data['time_stamp'];
        $report->idmsg = $data['IDMsg'];
        $report->user_id = $data['user_id'];
        $report->save();
    }
    public function get_notification($time){
        $report = new Report();
	$time = Carbon::now('Asia/Ho_Chi_Minh')->subMinutes($time)->toDateTimeString();
        $data = $report->select('lat','lng','time_stamp','idmsg')->where('time_stamp','>=',$time)->get();
        return $data;
    }
}
