<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JsonResponses extends JsonResource
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
        // Mengambil parameter tambahan dan menyimpannya dalam properti
        $this->additionalParams = $additionalParams;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Menggabungkan additionalParams dengan data yang ada
        $response = [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];

        // Menambahkan parameter tambahan ke dalam response, jika ada
        foreach ($this->additionalParams as $param) {
            // Asumsikan $param adalah array, jika Anda ingin menambahkan ke dalam data
            if (is_array($param)) {
                $response = array_merge($response, $param);
            }
        }

        // Menambahkan 'response_at' di bagian bawah
        $response['response_at'] = Carbon::now()->format('d/m/Y H:i:s');

        return $response;
    }
}
