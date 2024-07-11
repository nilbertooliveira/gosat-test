<?php

namespace App\Domains\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface DTOInterface
{
    public function jsonSerialize(): array;

    public static function createFromRequest(Request $request): DTOInterface;
}
