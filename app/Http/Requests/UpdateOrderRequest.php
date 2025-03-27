<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $client = $this->user()?->client;

        return ! is_null($client) && $this->order->client_id === $client->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:products'],
            'line' => ['nullable', Rule::in($this->order->products->pluck('id')->all())],
            'quantity' => ['required', 'numeric', 'min:0'],
        ];
    }
}
