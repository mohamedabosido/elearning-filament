<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static bigener()
 * @method static static intermediate()
 * @method static static advanced()
 */
final class CourseLevel extends Enum
{
    const Beginner = 'beginner';
    const Intermediate = 'intermediate';
    const Advanced = 'advanced';
    public $color;

    public function __construct($enumValue)
    {
        parent::__construct($enumValue);
        $this->color = static::getColor($enumValue);
    }

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::Beginner:
                return __('main.beginner');
                break;
            case self::Intermediate:
                return __('main.intermediate');
                break;
            case self::Advanced:
                return __('main.advanced');
                break;
            default:
                return parent::getDescription($value);
                break;
        }
    }

    public static function getColor($value): string
    {
        switch ($value) {
            case self::Beginner:
                return 'success';
                break;
            case self::Intermediate:
                return 'warning';
                break;
            case self::Advanced:
                return 'danger';
                break;
            default:
                return 'secondary';
                break;
        }
    }
}
