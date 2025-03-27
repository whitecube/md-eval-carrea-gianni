<?php

namespace App\Transactions\Clients;

enum ClientType: string
{
    case Normal = 'normal';
    case Vip = 'vip';
    case Wholesaler = 'wholesaler';

    public function label(): string
    {
        return match ($this) {
            self::Normal => 'Normal',
            self::Vip => 'VIP',
            self::Wholesaler => 'Grossiste',
        };
    }
}
