<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Interfaces\IStatusCode;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 利用Timestamp新增不重複的Id。
     * 
     * @param string $prevValue 上一個值，如果不為空表示可以繼續新增。
     * @param string $prefix 前綴，比如M16641984701的M。
     * @param int $firstMaxObjectInSecond
     * 
     * @return string
     */
    public function createSerializeId(string $prevValue, string $prefix, int $firstMaxObjectInSecond)
    {
        date_default_timezone_set("Asia/Taipei"); // 強制設定時區
        $time = time(); // 取得Timestamp
        $resultId = $id = $prefix . $time; // 將Timestamp加上前綴，並同時給$resultId跟$id。
        if (!empty($prevValue)) {
            $maxObjectInSecond = strlen($prevValue) - strlen($id); // $prevValue不為空的話，則減掉timestamp的長度，則為一秒內能建立幾個實體的值。
        } else {
            $maxObjectInSecond = $firstMaxObjectInSecond; // $prevValue 為空的話，則表示沒有上一次的值，故需要給一個預設一秒內能建立幾個實體的值。
        }

        for ($i = 1; $i < $maxObjectInSecond; $i++) {
            $resultId .= "0"; // 根據拿到的(長度-1)補0
        }
        $resultId .= "1"; // 第一個

        if (substr($prevValue, 0, strlen($prevValue) - $maxObjectInSecond) === $id) {
            $quantity = substr($prevValue, strlen($prevValue) - $maxObjectInSecond); // 取得一秒內已經幾個物件了。
            $quantity = intval($quantity); // 轉整數。
            $zeroPaddingCount = $maxObjectInSecond - strlen($quantity); // 要補多少零。

            for ($i = 1; $i < $zeroPaddingCount; $i++) {
                $id .= "0";
            }
            $resultId =  $id . $quantity + 1; // 上一個的值+1 就是新增的值。
        }

        return $resultId;
    }
}
