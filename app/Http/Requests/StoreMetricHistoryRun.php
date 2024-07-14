<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMetricHistoryRun extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'url'                   => ['required', 'string'],
            'strategy_id'           => ['sometimes', 'nullable', 'int'],
            'accessibility_metric'  => ['sometimes', 'nullable', 'numeric'],
            'pwa_metric'            => ['sometimes', 'nullable', 'numeric'],
            'performance_metric'    => ['sometimes', 'nullable', 'numeric'],
            'seo_metric'            => ['sometimes', 'nullable', 'numeric'],
            'best_practices_metric' => ['sometimes', 'nullable', 'numeric'],
        ];
    }
}
