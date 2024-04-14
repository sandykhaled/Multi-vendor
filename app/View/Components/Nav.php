<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Nav extends Component
{
    public $items;
    public $active;

    public function __construct($context = 'side')
    {
        $this->items=config('nav'); //return array
        $this->active=Route::currentRouteName();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nav');
        //because $items is public , No need to add view('components.nav',['items',$this->items])
    }
}
