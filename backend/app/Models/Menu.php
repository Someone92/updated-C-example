<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {

	protected $table = 'menu';

	protected $fillable = [
		'name', 'hidden'
	];

	public function child() {
		return $this->hasMany('App\Models\MenuItems')->whereNUll('parent_id');
	}

	public function children() {
		return $this->child()->with('children');
	}
}
