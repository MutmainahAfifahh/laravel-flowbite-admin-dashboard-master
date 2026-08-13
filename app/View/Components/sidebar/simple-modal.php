<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class simpleModal extends Component
{
    public $id, $title, $buttonText;

    /**
     * Create a new component instance.
     */
    public function __construct($id, $title, $buttonText = 'Toggle Modal')
    {
        $this->id = $id;
        $this->title = $title;
        $this->buttonText = $buttonText;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.simple-modal');
    }
}
