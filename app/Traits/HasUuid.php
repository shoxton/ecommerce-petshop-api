<?php

namespace App\Traits;

trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            $model->uuid = \Illuminate\Support\Str::uuid();
        });
    }
}
