<?php

namespace App\Domains\ValueObjects;

use Illuminate\Http\Request;

class Cpf
{
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public static function createFromRequest(Request $request): Cpf
    {
        return new Cpf($request->input('cpf'));
    }
}
