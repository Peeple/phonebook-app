<?php

namespace App\Casts;

use App\Http\Services\PhoneUtil\PhoneUtilInterface;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PhoneNumber implements CastsAttributes
{
    protected PhoneUtilInterface $phoneUtil;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->phoneUtil = app()->make(PhoneUtilInterface::class);
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $this->phoneUtil->formatPhone($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $this->phoneUtil->parsePhone($value);
    }
}
