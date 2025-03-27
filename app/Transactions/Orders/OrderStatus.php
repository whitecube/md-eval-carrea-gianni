<?php

namespace App\Transactions\Orders;

enum OrderStatus: string
{
    case Cart = 'cart';
    case Submitted = 'submitted';
    case Registered = 'registered';
    case Processing = 'processing';
    case Treated = 'treated';
}
