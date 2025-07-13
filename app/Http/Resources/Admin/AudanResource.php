<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AudanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
   public function toArray($request)
    {
        $successful = 0;
        $unsuccessful = 0;
        $warningful = 0;

        // Подсчет статусов по всем стадиям каждого проекта
        foreach ($this->projects as $project) {
            foreach ($project->stages as $stage) {
                $status = $stage->status;

                if ($status == 1) {
                    $successful++;
                } elseif ($status == 2) {
                    $warningful++;
                } else {
                    $unsuccessful++;
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'projects_count' => $this->projects_count,
            'successful' => $successful,
            'warningful' => $warningful,
            'unsuccessful' => $unsuccessful,
        ];
    }
}
