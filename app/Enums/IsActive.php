<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static InActive()
 * @method static static Active()
 */
final class IsActive extends Enum
{
    const InActive = 0;
    const Active = 1;
    public $color;

    public function __construct($enumValue)
    {
        parent::__construct($enumValue);
        $this->color = static::getColor($enumValue);
    }

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::InActive:
                return __('main.inactive');
                break;
            case self::Active:
                return __('main.active');
                break;
            default:
                return parent::getDescription($value);
                break;
        }
    }

    public static function getColor($value): string
    {
        switch ($value) {
            case self::InActive:
                return 'danger';
                break;
            case self::Active:
                return 'success';
                break;
            default:
                return 'danger';
                break;
        }
    }
}
