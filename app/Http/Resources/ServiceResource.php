<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,

            'view_type' => $this->view_type,

            'options' => $this->options,
            'show_website' => $this->show_website,
            
            'properties' => ServicePropertiesResource::collection($this->properties),
            'addons' => ServiceAddonsResource::collection($this->addons),
            'fees' => ServiceFeeDetailsResource::collection($this->fees),

            'icon'=>$this->icon,
            'icon_name'=>$this->icon_name,
            'font_type'=>$this->font_type,
            'translations' => ($this->translations) ? BasicTranslationResource::collection($this->translations) : [],
        ];
    }
}
