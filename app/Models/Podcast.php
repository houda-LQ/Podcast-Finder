<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Foundation\Auth\User;
use App\Models\User;

class Podcast extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','image','user_id'];

    public function host(){
        return $this->belongsTo(User::class);
    }

    public function episodes(){
        return $this->hasMany(Episode::class);
    }
}
