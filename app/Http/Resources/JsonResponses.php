<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\Support\Responsable;

class JsonResponses extends JsonResource implements Responsable
{
    public $status;
    public $message;
    public $data;
    public $additionalParams = [];

    public function __construct($status, $message, $data, ...$additionalParams)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->additionalParams = $additionalParams;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $response = [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];

        foreach ($this->additionalParams as $param) {
            if (is_array($param)) {
                $response = array_merge($response, $param);
            }
        }

        $response['response_at'] = Carbon::now()->format('d/m/Y H:i:s');

        return $response;
    }

    /**
     * Mengubah resource menjadi response JSON dengan status HTTP yang benar.
     */
    public function toResponse($request)
    {
        return response()->json($this->toArray($request), $this->status);
    }
}
