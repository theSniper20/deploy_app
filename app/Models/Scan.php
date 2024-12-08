<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;
    protected $table = 'scans';
    protected $primaryKey = 'id';
    protected $fillable = ['image_name', 'path_name', 'produced_at','service_id','display_method','category_id', 'scan_reference'];
    protected $casts = [
        'produced_at' => 'datetime', // Cast 'produced_at' to a Carbon instance
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
