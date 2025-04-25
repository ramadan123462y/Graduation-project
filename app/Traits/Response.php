<?php

namespace App\Traits;

trait Response
{
    protected function sendResponse($result, $message, $code = 200, )
    {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $result,
        ];
        return response()->json($response, $code);
    }


    protected function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'status' => 'error',
            'message' => $error,
            'data' => null
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function sendResponseWithPagination($result, $message = '', $code = 200)
    {

        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $result,
            'pagination' => [
                'currentPage' => $result->currentPage(),
                'lastPage' => $result->lastPage(),
                'perPage' => $result->perPage(),
                'total' => $result->total(),
            ]
            ];

        return response()->json($response, $code);
    }

}
