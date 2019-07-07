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
}
