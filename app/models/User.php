<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Entrust\HasRole;
use Carbon\Carbon;

class User extends Eloquent implements UserInterface, RemindableInterface {
	use HasRole;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	
	public static function boot() {
		parent::boot();
		self::saving(function($model) {
			if (empty($model->ida)) {
				$model->ida = null;
			}
			if (empty($model->display_name)) {
				$model->display_name = null;
			}
			return true;
		});
	}
	
	public static $sluggable = array(
		'build_from' => 'display_name',
		'save_to' => 'slug',
	);

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');
	
	protected $guarded = ['id','password'];

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	public function slugOrId() {
		if ($this->slug != "") {
			return $this->slug;
		} else {
			return $this->id;
		}
	}
	
	public function lans() {
		return $this->belongsToMany('Lan')->withPivot('ticket_id','seat_group_id','seat_num')->withTimestamps();
	}
	
	public function hasAdmission() {
		
		$lan = Lan::curLan();
		return $this->lans->contains($lan);

		foreach ($this->lans as $k => $v) {
			if ($v->id == $lan->id) {
				return true;
			}
		}
		
		return false;
		
	}

	public function hasSeat() {
		$lan = Lan::curLan();
		$lanuser = LanUser::whereUserId($this->id)->whereLanId($lan->id)->first();
		if (!$lanuser) {
			return false;
		}
		if ($lanuser->seat_group_id && $lanuser->seat_num) {
			return true;
		} else {
			return false;
		}
		
	}

	public function seatNumber() {
		$lan = Lan::curLan();
		$lanuser = LanUser::whereUserId($this->id)->whereLanId($lan->id)->first();
		if (!$lanuser) {
			return false;
		}
		if ($lanuser->seat_group_id && $lanuser->seat_num) {
			return $lanuser->gseatnum;
		} else {
			return false;
		}
		
	}

	public function refreshRiotStats($delay = 0) {
		if ($delay <= 0) {
			Queue::push('Aaulan\Riot\GetRiotStats',$this->id);	
		} else {
			$date = Carbon::now()->addSeconds($delay);
			Queue::later($date,'Aaulan\Riot\GetRiotStats',$this->id);
		}
		
	}
	
	public function getHasTicketAttribute() {
		return $this->hasAdmission();
	}
	
	public function entries() {
		return $this->hasMany('Entry');
	}
	
	public function validation() {
		return $this->hasMany('ValidationToken');
	}
	
	public function ticketvalidation() {
		return $this->hasMany('TicketValidationToken');
	}
	
	public function setPasswordAttribute($value) {
		$this->attributes['password'] =  Hash::make($value);
	}
	
	public function teams() {
		return $this->belongsToMany('Team')->withPivot('accepted');
	}

	public function tournaments() {
		return $this->belongsToMany('Tournament')->withTimestamps();
	}
	
	public function lanuser() {
		return $this->hasOne('LanUser')->where('lan_id',Lan::curLan()->id);
	}

	public function liveUpdates() {
		return $this->belongsToMany('LiveUpdate','live_update_user')->withPivot('seen');
	}

	public function getUrlAttribute() {
		if (!empty($this->slug)) {
			return $this->slug;
		} else {
			return $this->id;
		}
	}
	public function getDisplaySanitizedAttribute() {
		if (!empty($this->display_name)) {
			$str = $this->display_name;
		} else {
			$str = $this->name;
		}
		return $str;
	}
	public function getDisplayAttribute() {
		$str = '<a href="'.URL::action('UserController@getShowProfile',$this->url).'">';
		if (!empty($this->display_name)) {
			$str .= $this->display_name;
		} else {
			$str .= $this->name;
		}
		$str .= "</a>";
		if ($this->can('crew_title')) {
			$str .= ' <span class="crew_title">(Crew)</span>';
		}
		return $str;
	}
	public function getXmppAttribute() {
		if (!empty($this->slug)) {
			return $this->slug;
		}
		return strstr($this->email,'@',true);
	}	


	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

}
