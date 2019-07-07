<?php

namespace App\Repository;

class CellsDetail extends BaseModel
{
    protected $table = 'cells_detail';
    protected $fillable = [
      'x_axis',
      'y_axis',
      'start_time',
      'end_time',
      'id_cell',
      'avg_speed',
      'marker_count',
      'indicator',
      'color',
      'algorithm'
    ];
    
    // public function markers()
    // {
    //     return $this->hasMany(Markers::class, 'position', 'id');
    // }
    
    // public function rule()
    // {
    //     return [
    //       'height' => 'required|min:0|max:22',
    //       'width' => 'required|min:0|max:55',
    //     ];
    // }
}
