<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Interfaces\IStatusCode;
use App\Models\MachineType;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiFeature;

class MachineTypeController extends Controller
{

    /**
     * 建立新的機器類型
     * 
     * @param Request $request post data
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|string|min:1|max:5|unique:machine_types,MT_id',
                'name' => 'required|string|min:1|max:20',
                'description' => 'string|max:50',
                'logo' => 'string|max:100',
            ]);

            if ($validator->fails()) {
                return ApiFeature::sendResponse($validator->errors(), IStatusCode::BAD_REQUEST);
            }

            $machineType = MachineType::firstOrNew([
                'MT_id' => $request['id'],
                'MT_name' => $request['name'],
                'MT_description' => $request['description'],
                'MT_logo' => $request['logo']
            ]);

            DB::transaction(function () use ($machineType) {
                $machineType->save();
            });

            return ApiFeature::sendResponse($machineType, IStatusCode::CREATED);
        } catch (Exception $ex) {
            Log::error($ex);
            return ApiFeature::sendResponse('', IStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 取得機器類型
     * 
     * @param Request $request query string
     * @param string $id 指定取得哪個機器類型
     * 
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, string $id = '')
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id' => 'string',
            'skip' => 'integer',
            'take' => 'integer'
        ]);

        if ($validator->fails()) {
            return ApiFeature::sendResponse($validator->errors(), IStatusCode::BAD_REQUEST);
        }

        $machineTypes = MachineType::all();

        if ($id !== '') {
            $machineTypes = MachineType::find($id);
        }

        return ApiFeature::sendResponse($machineTypes, IStatusCode::OK);
    }
}
