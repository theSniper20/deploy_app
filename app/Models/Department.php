<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';
    protected $primaryKey = 'id';
    
    protected $fillable = ['name', 'description', 'hospital_id'];
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // Define the inverse relationship with Hospital
    public function hospital()
    {
         return $this->belongsTo(Hospital::class);
    }

}
