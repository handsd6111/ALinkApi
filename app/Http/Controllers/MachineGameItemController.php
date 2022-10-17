<?php

namespace App\Http\Controllers;

use App\Models\ExpectedItem;
use Illuminate\Http\Request;
use App\Models\Interfaces\IStatusCode;
use App\Models\MachineGameItem;
use App\Models\RealityItem;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFeature;

class MachineGameItemController extends Controller
{
    /**
     * 取得指定機器的遊玩記錄數量。
     * 
     * @param string $machineId 指定機器id
     * 
     * @return \Illuminate\Http\Response
     */
    public function getCount(string $machineId)
    {
        try {
            // 驗證
            $validator = Validator::make(['machineId' => $machineId], [
                'machineId' => 'string|required|required:machines,M_id',
            ]);

            // 驗證失敗處理
            if ($validator->fails()) {
                return ApiFeature::sendResponse($validator->errors(), IStatusCode::BAD_REQUEST); //回傳400並且指出錯誤在哪。
            }

            // 回傳指定機器的遊玩紀錄數量。
            return ApiFeature::sendResponse(MachineGameItem::where('M_id', $machineId)->count(), IStatusCode::OK);
        } catch (Exception $ex) {
            Log::error($ex);
            return ApiFeature::sendResponse(null, IStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 取得指定機器的全部遊玩紀錄，或者指定機器的單筆詳細遊玩紀錄。
     * 
     * @param Request $request query string
     * @param string $machineId 指定機器id
     * @param int $MGI_sequence 指定要取得單筆的詳細遊玩紀錄，預設為-1不指定，為拿全部的紀錄。
     * 
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, string $machineId, int $MGI_sequence = -1)
    {
        $request['MGI_sequence'] = $MGI_sequence;
        $request['machineId'] = $machineId;

        // 驗證
        $validator = Validator::make($request->all(), [
            'MGI_sequence' => 'integer',
            'machineId' => 'string|required|required:machines,M_id',
            'skip' => 'integer',
            'take' => 'integer'
        ]);

        // 驗證失敗處理
        if ($validator->fails()) {
            return ApiFeature::sendResponse($validator->errors(), IStatusCode::BAD_REQUEST); //回傳400並且指出錯誤在哪。
        }

        $skip = isset($request['skip']) ? $request['skip'] : 0; // 跳過幾筆
        $take = isset($request['take']) ? $request['take'] : env('MAX_TAKE_QUANTITY'); // 拿幾筆，不限制為全拿。

        // 若沒指定取哪個gameItem，就給全部的gameItem。
        $machineGameItems = MachineGameItem::where('M_id', $machineId)
            ->orderby('MGI_sequence', 'desc')
            ->skip($skip)
            ->take($take)
            ->get();

        // 指定取得gameItem
        if ($MGI_sequence >= 0) {

            // 預期的itemDatas
            $expectedItem = ExpectedItem::getItemDatas($MGI_sequence, $machineId)->get();
            // 實際的itemDatas
            $realityItem = RealityItem::getItemDatas($MGI_sequence, $machineId)->get();

            // 沒有資料就回傳空陣列，並且message表示沒東西。
            if (count($expectedItem) <= 0) {
                return ApiFeature::sendResponse([], IStatusCode::OK, "nothing there.");
            }

            $machineGameItems = ['expectedItem' => [], 'realityItem' => []];
            $fillRate = 0;

            // 整合預期跟實際的itemDatas資料
            for ($i = 0; $i < count($expectedItem); $i++) {
                $machineGameItems['expectedItem'][] = $expectedItem[$i]->EI_data;
                $machineGameItems['realityItem'][] = $realityItem[$i]->RI_data;
                // 若實際與預期相同，則達成總數加一
                if ($expectedItem[$i]->EI_data === $realityItem[$i]->RI_data) {
                    $fillRate++;
                }
            }

            // 達成率，總合除以總數
            $fillRate /= count($expectedItem);
            // 轉換成百分比，保留小數
            $machineGameItems['fillRate'] = $fillRate * 100;
        }

        return ApiFeature::sendResponse($machineGameItems, IStatusCode::OK);
    }
}
