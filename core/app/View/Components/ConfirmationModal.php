<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ConfirmationModal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $btnSize;
    public $btnBase;
    public function __construct($btnSize=null, $btnBase=null)
    {
        $this->btnSize = $btnSize;
        $this->btnBase = $btnBase;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.confirmation-modal');
    }
}
