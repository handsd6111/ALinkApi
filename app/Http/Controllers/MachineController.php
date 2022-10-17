<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Interfaces\IStatusCode;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Machine;
use App\Helpers\ApiFeature;

class MachineController extends Controller
{
    /**
     * 建立新機器
     * 
     * @param Request $request post data
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            // 驗證
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:1|max:20|unique:machines,M_name',
                'description' => 'string|max:50',
                'image' => 'string|max:100',
            ]);

            // 驗證失敗處理
            if ($validator->fails()) {
                return ApiFeature::sendResponse($validator->errors(), IStatusCode::BAD_REQUEST);
            }

            $lastMachine = Machine::orderBy('created_at', 'desc')->first(); // 取得建立時間取得最後一筆的機器
            $lastMachineId = empty($lastMachine) ? "" : $lastMachine->M_id; // 取得最後一筆機器的id

            $machineType = Machine::firstOrNew([
                'M_id' => $this->createSerializeId($lastMachineId, "M", 1), // 給新機器一個序列化id
                'M_name' => $request['name'],
                'M_description' => $request['description'],
                'M_image' => $request['image'],
                'machine_type' => 'DRB' // 目前只有DRB一種機器類型，先寫死，後面有第二種機器類型會修改。
            ]);

            DB::transaction(function () use ($machineType) {
                $machineType->save(); // lock table並寫入
            });

            return ApiFeature::sendResponse($machineType, IStatusCode::CREATED); // 回傳建立新機器
        } catch (Exception $ex) {

            Log::error($ex); // 錯誤記錄起來
            return ApiFeature::sendResponse(null, IStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 取得全部或指定機器
     * 
     * @param Request $request query string
     * @param string $id 指定取得哪個機器
     * 
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, string $id = '')
    {

        try {
            // 驗證
            $request['id'] = $id;
            $validator = Validator::make($request->all(), [
                'id' => 'string',
                'skip' => 'integer',
                'take' => 'integer'
            ]);

            //驗證失敗
            if ($validator->fails()) {
                return ApiFeature::sendResponse($validator->errors(), IStatusCode::BAD_REQUEST);
            }

            $skip = isset($request['skip']) ? $request['skip'] : 0; // 跳過幾筆
            $take = isset($request['take']) ? $request['take'] : env('MAX_TAKE_QUANTITY'); // 拿幾筆，不限制為全拿。

            $machines = Machine::skip($skip)->take($take)->get(); // 不指定id

            if ($id !== '') {
                $machines = Machine::find($id); // 指定id，只返回單筆
            }

            return ApiFeature::sendResponse($machines, IStatusCode::OK);
        } catch (Exception $ex) {

            Log::error($ex);
            return ApiFeature::sendResponse(null, IStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
