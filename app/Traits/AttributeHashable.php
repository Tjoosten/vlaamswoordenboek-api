<?php

namespace App\Traits;

trait AttributeHashable
{
    public static function bootAttributeHashable(): void
    {
        static::saving(function ($model): void {
            foreach ($model->hashable as $attribute) {
                if (! $model->isDirty($attribute)) {
                    continue;
                }

                $model->attributes[$attribute] = app('hash')->make($model->attributes[$attribute]);
            }
        });
    }
}
