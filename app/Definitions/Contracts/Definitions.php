<?php

namespace App\Definitions\Contracts;

use Illuminate\Support\Collection;

interface Definitions {
    public function fetch() : Collection;

    public function definitions() : Collection;

    public function implementation() : string;
}