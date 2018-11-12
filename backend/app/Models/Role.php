<?php
namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {
	
	protected $hidden = array('pivot');

	public function permissions() {
        return $this->belongsToMany('App\Permission')->select('name');
    }
    
   
}