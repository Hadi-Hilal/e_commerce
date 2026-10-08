<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Can extends Component
{
    /**
     * The permission to check.
     *
     * @var string
     */
    public string $perform;

    /**
     * The user to check permissions for (defaults to authenticated user).
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public $user;

    /**
     * Create a new component instance.
     */
    public function __construct(string $perform, $user = null)
    {
        $this->perform = $perform;
        $this->user = $user ?? auth()->user();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if ($this->user && $this->user->can($this->perform)) {
            return $this->slot ?? '';
        }

        return '';
    }
}