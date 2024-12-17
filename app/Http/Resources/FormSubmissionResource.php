<?php

namespace App\Http\Resources;

use App\Models\FormSubmission;
use App\Models\FormSubmissionOption;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormSubmissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'field_name' => $this->field_name,
            'type' => $this->type,
            'question' => $this->question,
            'required' => $this->required == 1,
            'options' => FormSubmissionOptionResource::collection($this->whenLoaded('options')),
        ];
    }
}
