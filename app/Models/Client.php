<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }
}
