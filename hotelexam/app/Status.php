<?php

namespace App;

enum Status: string
{
    //
    case PENDING ='pending';
    case COMPLETED ='completed';
    case FAILED ='failed';
    case REFUNDED ='refunded';
}
