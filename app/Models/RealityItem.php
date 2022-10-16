<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealityItem extends Model
{
    use HasFactory;

    protected $table = 'reality_items';
    public $incrementing = false;
    protected $keyType = 'mediumInteger';
    protected $primaryKey = 'RI_sequence';
    public $timestamps = false;
    protected $fillable = [
        'M_id',
        'MGI_sequence',
        'RI_sequence',
        'RI_data',
    ];

    /**
     * 取得遊戲實際紀錄的資料。
     * 
     * @param int $MGI_sequence
     * @param string $machineId
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public static function getItemDatas($MGI_sequence, $machineId)
    {
        return self::select('RI_data')
            ->where('MGI_sequence', $MGI_sequence)
            ->where('M_id', $machineId)
            ->orderBy('RI_sequence', 'asc');
    }
}
