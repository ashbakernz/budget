<?php

namespace App\Models;

use App\Exceptions\WidgetNotFoundException;
use App\Exceptions\WidgetUnknownTypeException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use SoftDeletes;

    protected $casts = [
        'settings' => 'object'
    ];

    protected $fillable = [
        'user_id',
        'type',
        'sorting_index',
        'settings'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Custom
    public function render(): string
    {
        $widgetClass = '\App\Widgets\\' . ucfirst($this->type);

        if (!class_exists($widgetClass)) {
            throw new WidgetUnknownTypeException();
        }

        $widgetInstance = new $widgetClass($this->settings);

        return $widgetInstance->render();
    }
}
