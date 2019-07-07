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
