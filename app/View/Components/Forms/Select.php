<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Select extends Component
{

    public $name;
    public $id;
    public $label;
    public $value;
    public $placeholder;
    public $type;
    public $required;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name = null, $id = null, $value = null, $placeholder = null, $required = false, $type = 'text')
    {
        $this->id = $id;
        $this->label = $label;
        $this->name =  is_null($name) ? strtolower(str_replace(' ', '_', $label)) : $name;
        $this->value = is_null($value) ? old($this->name, null) : $value;
        $this->placeholder = is_null($placeholder) ? $label : $placeholder;
        $this->required = $required;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.select');
    }
}
