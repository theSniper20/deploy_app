<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'description', 'department_id'];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
    public function scans()
    {
        return $this->hasMany(Scan::class);
    }
}
