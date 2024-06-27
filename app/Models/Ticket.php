<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable=[
        'ticket_name',
        'support_type',
        'user_id',
        'priority',
        'ticket_id'

    ];
}
