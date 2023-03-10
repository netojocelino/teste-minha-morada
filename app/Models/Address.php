<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'cep',
        'number',
        'street',
        'neighborhood',
        'city',
        'state',
        'user_id',
    ];

    public function user () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
