<?php

namespace MemoryCache;

use Carbon\Carbon;

class Item
{
    public $value;
    public $deadline;

    public function inExpire()
    {
        return $this->deadline === null || $this->deadline >= Carbon::now()->timestamp;
    }
}