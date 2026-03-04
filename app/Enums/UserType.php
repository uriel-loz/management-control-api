<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            self::CUSTOMER => 'Customer',
        };
    }

    public function isCustomer(): bool
    {
        return $this === self::CUSTOMER;
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
