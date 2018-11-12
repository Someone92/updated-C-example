<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class MenuItems extends Model {

	protected $table = 'menu_items';


	public function children() {
		return $this->hasMany('App\Models\MenuItems', 'parent_id');
	}

}
