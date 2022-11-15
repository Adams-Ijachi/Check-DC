<?php


namespace App\Traits;


use App\Models\Status;

trait StatusTrait
{

    // boot
    public static function bootStatusTrait()
    {
        static::creating(function ($model) {

            $model->status_id = $model->status_id ?? Status::where('name', Status::STATUS_ACTIVE)->first()->id;
        });
    }


    public function isActive(): bool
    {
        return $this->status_id === Status::where('name', Status::STATUS_ACTIVE)->first()->id;
    }

}
