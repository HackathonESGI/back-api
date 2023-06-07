<?php

declare(strict_types=1);

namespace App\Tests\Behat\Service;

class SharedStorage
{
    /** @var array<mixed> */
    protected array $storage = [];

    protected ?string $lastKey = null;

    public function set(string $key, mixed $element): static
    {
        $this->storage[$key] = $element;
        $this->lastKey = $key;

        return $this;
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->storage, true);
    }

    public function get(string $key): mixed
    {
        return $this->storage[$key];
    }

    public function getLastElement(): mixed
    {
        return $this->storage[$this->lastKey];
    }

    public function getLastKey(): ?string
    {
        return $this->lastKey;
    }
}
