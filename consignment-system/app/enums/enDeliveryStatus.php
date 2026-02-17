<?php


namespace App\Enums;

enum enDeliveryStatus: int
{
    case Arrived = 1;
    case Cancelled = 2;
    case Refunded = 3;
}
