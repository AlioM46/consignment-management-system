<?php


namespace App\Enums;

enum enInvoiceStatus: int
{
    case Pending = 1;
    case Paid = 2;
    case Cancelled = 3;
}
