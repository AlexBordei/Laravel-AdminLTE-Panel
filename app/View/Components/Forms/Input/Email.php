<?php

namespace App\View\Components\Forms\Input;

use App\View\Components\Forms\Input;

class Email extends Input
{
    public function __construct($label, $name = null, $value = null, $placeholder = null, $required = false)
    {
        parent::__construct($label, $name, $value, $placeholder, $required, 'email');
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.input.email');
    }
}
