<?php

namespace EscolaLms\Core\Models\Concerns;

trait Donatable
{
    public function addPoints(int $points): self
    {
        $this->donated_points += $points;
        $this->save();
        return $this;
    }
}
