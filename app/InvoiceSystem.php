<?php namespace App;

use DB;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class InvoiceSystem extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'invoice_system';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['invoice_number', 'invoice_date', 'client_id', 'inv_net	vat_rate', 'f_row1', 'g_row2', 'h_row3', 'i_row4', 'j_row5', 'k_row6', 'l_row7', 'm_row8', 'n_row9', 'o_row10', 'p_row11', 'q_row12', 'r_row13', 's_row14', 't_row15', 'u_row16', 'v_row17', 'w_row18', 'x_row19', 'y_row20', 'z_row1', 'aa_row2', 'ab_row3', 'ac_row4', 'ad_row5', 'ae_row6', 'af_row7', 'ag_row8', 'ah_row9', 'ai_row10', 'aj_row11', 'ak_row12', 'al_row13', 'am_row14', 'an_row15', 'ao_row16', 'ap_row17', 'aq_row18', 'ar_row19', 'as_row20', 'at_row1', 'au_row2', 'av_row3', 'aw_row4', 'ax_row5', 'ay_row6', 'az_row7', 'ba_row8', 'bb_row9', 'bc_row10', 'bd_row11', 'be_row12', 'bf_row13', 'bg_row14', 'bh_row15', 'bi_row16', 'bj_row17', 'bk_row18', 'bl_row19', 'bm_row20', 'bn_row1', 'bo_row2', 'bp_row3', 'bq_row4', 'br_row5', 'bs_row6', 'bt_row7', 'bu_row8', 'bv_row9', 'bw_row10', 'bx_row11', 'by_row12', 'bz_row13', 'ca_row14', 'cb_row15', 'cc_row16', 'cd_row17', 'ce_row18', 'cf_row19', 'cg_row20', 'ch_invoice_number', 'ci_year_end', 'cj1', 'ck2', 'cl3', 'cm4', 'cn5', 'co_abridgedinc', 'cp_sor', 'cq_adjnote', 'cr_position', 'cs_liability', 'ct_prelim', 'cu_paydate', 'cv_included', 'cw_liability', 'cx_prelim', 'cy_paydate', 'cz_invoice', 'da_blank1', 'db_blank2', 'dc_blank3', 'statement', 'update_time', 'status', 'gross'];



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
