<?php
namespace Aaulan\Composers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider {
	
	public function register() {
		$this->app->view->composer('layouts.master','Aaulan\Composers\Validation');
        $this->app->view->composer('layouts.master','Aaulan\Composers\ActiveUsers');
		$this->app->view->composer('layouts.master','Aaulan\Composers\PizzaCountdown');
		$this->app->view->composer('partials.menu','Aaulan\Composers\Menu');
		$this->app->view->composer('layouts.master','Aaulan\Composers\InfoPages');
		
	}
	
}
