<?php

namespace App\Repository;

class Future extends BaseModel
{
    protected $table = 'future';
    protected $fillable = [
      'x_axis',
      'y_axis',
      'destination_time',
      'id_cell',
      'avg_speed',
      'color',
      'indicator',
      'algorithm'
    ];
}
