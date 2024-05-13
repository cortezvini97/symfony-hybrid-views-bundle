<?php

namespace Cortez\SymfonyHybridViews\Utils;

use Closure;
use Illuminate\Container\Container;

class BladeContainer extends Container
{
    protected array $terminatingCallbacks = [];

    public function terminating(Closure $callback)
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    public function terminate()
    {
        foreach ($this->terminatingCallbacks as $terminatingCallback) {
            $terminatingCallback();
        }
    }
}