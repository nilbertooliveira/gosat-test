<?php

namespace App\Application\Services;

class ResponseService
{
    private mixed $data;
    private bool $isSuccess;

    /**
     * Create a new class instance.
     */
    public function __construct(mixed $data, bool $isSuccess = true)
    {
        $this->data = $data;
        $this->isSuccess = $isSuccess;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    public function toArray(): array
    {
        $result = [
            'success' => $this->isSuccess(),
        ];

        if ($this->isSuccess()) {
            $result['data'] = $this->getData();
        } else {
            $result['errors'] = $this->getData();
        }
        return $result;
    }
}
