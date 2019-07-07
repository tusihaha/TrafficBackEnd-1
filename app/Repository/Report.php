<?php

namespace App\Repository;

class Report extends BaseModel
{
    protected $table = 'report';
    protected $fillable = [
      'lat',
      'lng',
      'idmgs',
      'time_stamp',
      'user_id'
    ];
}
