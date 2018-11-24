<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activation extends Model {
	public $timestamps = false;
    protected $fillable = ['email', 'activation_key'];
}