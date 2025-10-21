<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static WaitingForApproval()
 * @method static static Approved()
 * @method static static Rejected()
 */
final class ApprovalStatus extends Enum
{
    const WaitingForApproval = 'waiting_for_approval';
    const Approved = 'approved';
    const Rejected = 'rejected';
    public $color;

    public function __construct($enumValue)
    {
        parent::__construct($enumValue);
        $this->color = static::getColor($enumValue);
    }

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::WaitingForApproval:
                return __('main.waiting_for_approval');
                break;
            case self::Approved:
                return __('main.approved');
                break;
            case self::Rejected:
                return __('main.rejected');
                break;
            default:
                return parent::getDescription($value);
                break;
        }
    }

    public static function getColor($value): string
    {
        switch ($value) {
            case self::WaitingForApproval:
                return 'warning';
                break;
            case self::Approved:
                return 'success';
                break;
            case self::Rejected:
                return 'danger';
                break;
            default:
                return 'secondary';
                break;
        }
    }
}
