<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demo extends Model {

	protected $table = 'demo';

	protected $fillable = [
		'parent_id', 'name', 'description', 'access'
	];
	
	public function children() {
		return $this->hasMany(static::class, 'parent_id')->orderBy('name', 'asc');
	}
}
