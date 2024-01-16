<?php

namespace App\Enums;

enum ReservationStatusEnum:string {
    case pending = 'PENDIENTE';
    case approved = 'APROBADO';
    case reject = 'RECHAZADO';
}
