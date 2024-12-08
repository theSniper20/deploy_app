<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash; // Import Hash facade
class Hospital extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'hospitals';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'description', 'address', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value); // Ensure password is hashed
    }
    // Define one-to-many relationship with Department
    public function departments()
    {
          return $this->hasMany(Department::class);
    }
}
