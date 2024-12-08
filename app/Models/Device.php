<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table = 'devices';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'description', 'service_id'];
    public function service()
    {
        return $this->belongsTo(service::class);
    }
    public function scans()
    {
        return $this->hasMany(Scan::class);
    }
}
