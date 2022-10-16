<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineGameItem extends Model
{
    use HasFactory;

    protected $table = 'machine_game_items';
    public $incrementing = false;
    protected $keyType = 'mediumInteger';
    protected $primaryKey = 'MGI_sequence';
    public $timestamps = false;
    protected $fillable = [
        'M_id',
        'MGI_sequence',
        'MGI_start_game_time',
        'MGI_end_game_time',
    ];
}
