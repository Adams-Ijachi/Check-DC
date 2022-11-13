<?php


namespace App\Traits;


trait ResponseTraits
{

    public function response(array $data,string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ]);
    }

    public function successResponse(array $data,string $message): \Illuminate\Http\JsonResponse
    {
        return $this->response($data, $message);
    }

    public function errorResponse(array $data,string $message): \Illuminate\Http\JsonResponse
    {
        return $this->response($data, $message);
    }

}
