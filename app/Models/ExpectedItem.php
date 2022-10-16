<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpectedItem extends Model
{
    use HasFactory;

    protected $table = 'expected_items';
    public $incrementing = false;
    protected $keyType = 'mediumInteger';
    protected $primaryKey = 'EI_sequence';
    public $timestamps = false;
    protected $fillable = [
        'M_id',
        'MGI_sequence',
        'EI_sequence',
        'EI_data',
    ];


    /**
     * 取得遊戲預計紀錄的資料。
     * 
     * @param int $MGI_sequence
     * @param string $machineId
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function getItemDatas(int $MGI_sequence, string $machineId)
    {
        return self::select('EI_data')
            ->where('MGI_sequence', $MGI_sequence)
            ->where('M_id', $machineId)
            ->orderBy('EI_sequence', 'asc');
    }
}
