<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Checkbox extends Component
{

    public $label;
    public $data;
    public $name;
    public $selected_data;

    /**
     * Create a new component instance.
     *
     * @param $label
     * @param $name
     * @param array $data
     * @param array $selected_data
     */
    public function __construct($label, $name, $data = [], $selected_data = [])
    {

        $this->label = $label;
        $this->name = $name;

        if(!empty($data)) {
            $this->data = $data;
        }

        if(!empty($selected_data)) {
            $this->selected_data = $selected_data;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.checkbox');
    }
}
