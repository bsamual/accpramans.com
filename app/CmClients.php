<?php namespace App;

use DB;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class CmClients extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cm_clients';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['client_id', 'firstname', 'surname', 'company', 'address1', 'address2', 'address3', 'address4', 'address5', 'email', 'tye', 'active', 'tax_reg1', 'tax_reg2', 'tax_reg3', 'email2', 'phone', 'linkcode', 'cro', 'trade_status', 'directory', 'status'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function getdetails($userid){
		$user_name = DB::table('subcontractor')->where('user_id', $userid)->first();
		return $user_name;
	}

}
