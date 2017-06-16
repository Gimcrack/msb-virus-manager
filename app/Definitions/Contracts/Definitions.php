<?php

namespace App\Definitions\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface Definitions {
    public function fetch() : Collection;

    public function definitions() : Collection;

    public function active() : Collection;

    public function implementation() : string;
    
    public function implementation_short() : string;

    public function lastUpdated() : Carbon;
}