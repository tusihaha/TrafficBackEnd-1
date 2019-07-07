<?php

namespace App\Repository;

class Cells extends BaseModel
{
    protected $table = 'rectangles';
    protected $fillable = [
      'height',
      'width',
      'east',
      'west',
      'north',
      'south'
    ];
}
