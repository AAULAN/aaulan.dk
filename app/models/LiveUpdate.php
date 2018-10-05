<?php

class LiveUpdate extends Eloquent {

    protected $table = 'live_updates';

    protected $fillable = ['message','poster_id'];

    public function users() {
        return $this->belongsToMany('User','live_update_user')->withPivot('seen');
    }

    public function poster() {
        return $this->belongsTo('User','poster_id');
    }
}