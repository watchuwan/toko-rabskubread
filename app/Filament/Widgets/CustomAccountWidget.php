<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class CustomAccountWidget extends Widget
{

    protected static ?int $sort = -3;

    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = 'full';
    /**
     * @var view-string
     */

    protected string $view = 'filament-panels::widgets.account-widget';
    public static function canView(): bool
    {
        return Filament::auth()->check();
    }


}
