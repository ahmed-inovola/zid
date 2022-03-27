<?php
namespace App\Traits;

trait RespondsWithHttpStatus
{
    protected function success($data = [], $message = null, $status = 200)
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function failure($message, $status = 422)
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}

?>
