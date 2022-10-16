<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'machines';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'M_id';
    public $timestamps = true;
    protected $fillable = [
        'M_id',
        'M_name',
        'M_description',
        'M_image',
        'M_online_status',
        'owner',
        'machine_type'
    ];
}
