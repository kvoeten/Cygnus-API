<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model {
	
    protected $fillable = ['id', 'author', 'title', 'type', 'content', 'views', 'created_at', 'updated_at'];
}