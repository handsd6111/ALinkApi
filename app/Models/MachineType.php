<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineType extends Model
{
    use HasFactory;

    protected $table = 'machine_types';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'MT_id';
    public $timestamps = false;
    protected $fillable = [
        'MT_id',
        'MT_name',
        'MT_description',
        'MT_logo'
    ];
}
