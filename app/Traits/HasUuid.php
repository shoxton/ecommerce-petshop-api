<?php

namespace App\Traits;

trait HasUuid
{
    /**
     * Bootstrap trait to set uuid on creating event.
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            $model->uuid = \Illuminate\Support\Str::uuid()->toString();
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
