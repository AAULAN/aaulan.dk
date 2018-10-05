<?php

class Winner extends Eloquent
{

	public function lan() {
		return $this->belongsTo('Lan');
	}

	public function doOverlay($force = false) {
		$cropped = public_path().'/uploads/1140x760_crop/'.$this->filename;
		$overlayed = public_path().'/uploads/winners/'.$this->filename;

		if (file_exists($cropped) && (!file_exists($overlayed) || $force == true)) {
			Log::debug('Doing the overlay!');
			$overlay_file = $this->lan->overlay_file;
			$overlay = public_path().'/img/'.$overlay_file;

			$img = Image::make($cropped);
//			$img->text($this->game,570,600,function($font) {
//				$font->file(base_path().'/Nunito-Bold.ttf');
//				$font->size(60);
//				$font->color('#ffffff');
//				$font->align('center');
//			});
			$place = $this->place;
			if ($this->place == 1) {
				$place .= 'st';
			} else if ($this->place == 2) {
				$place .= 'nd';
			} else if ($this->place == 3) {
				$place .= 'rd';
			} else {
				$place .= 'th';
			}
//			$img->text(sprintf("%s place to %s",$place,$this->team_name),570,680,function($font) {
//				$font->file(base_path().'/Nunito-Regular.ttf');
//				$font->size(40);
//				$font->color('#ffffff');
//				$font->align('center');
//			});
			if (!empty($overlay_file) && file_exists($overlay)) {
				$img->insert($overlay)->save($overlayed);
			} else {
				$img->save($overlayed);
			}

		} else {
			Log::debug('Not doing the overlay');
		}
	}

	public static function boot() {
		parent::boot();
		self::saving(function($model) {
			$model->doOverlay();
		});
	}

}
