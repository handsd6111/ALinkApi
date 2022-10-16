<?php

namespace App\Helpers;

use App\Models\Interfaces\IStatusCode;

class ApiFeature {
    
    /**
     * @param any $data 欲傳送的資料。
     * @param int $status IStatusCode。
     * @param string $message 要傳送的訊息，有預設值。
     * @param array $headers 標頭陣列。
     */
    public static function sendResponse($data, int $statusCode, string $message = null, array $headers = [])
    {
        if ($message == null) {
            $message = IStatusCode::Message[$statusCode];
        }

        $result = [
            'data' => $data,
            'statusCode' => $statusCode,
            'message' => $message
        ];
        return Response(json_encode($result), $statusCode, $headers);
    }
}