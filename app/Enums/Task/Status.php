<?php

namespace App\Enums\Task;

enum Status: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case NON_COMPLIANT = 'non_compliant';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::NON_COMPLIANT => 'Non compliant'
        };
    }

    public function cssClass(): string
    {
        return match ($this) {
            self::PENDING => 'info',
            self::COMPLETED => 'success',
            self::NON_COMPLIANT => 'warning'
        };
    }
}
