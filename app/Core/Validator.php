<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Minimal, dependency-free server-side validator.
 * Collects one error message per field.
 */
final class Validator
{
    /** @var array<string,string> */
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $message): self
    {
        if (!$this->has($field)) {
            $this->fail($field, $message);
        }
        return $this;
    }

    public function email(string $field, string $message): self
    {
        $value = $this->value($field);
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->fail($field, $message);
        }
        return $this;
    }

    public function phone(string $field, string $message): self
    {
        $value = $this->value($field);
        if ($value !== '' && !preg_match('/^[+\d][\d\s()\-]{6,}$/', $value)) {
            $this->fail($field, $message);
        }
        return $this;
    }

    public function max(string $field, int $length, string $message): self
    {
        if (mb_strlen($this->value($field)) > $length) {
            $this->fail($field, $message);
        }
        return $this;
    }

    public function min(string $field, int $length, string $message): self
    {
        $value = $this->value($field);
        if ($value !== '' && mb_strlen($value) < $length) {
            $this->fail($field, $message);
        }
        return $this;
    }

    public function passes(): bool
    {
        return $this->errors === [];
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    /** @return array<string,string> */
    public function errors(): array
    {
        return $this->errors;
    }

    private function has(string $field): bool
    {
        return $this->value($field) !== '';
    }

    private function value(string $field): string
    {
        $v = $this->data[$field] ?? '';
        return is_string($v) ? trim($v) : '';
    }

    private function fail(string $field, string $message): void
    {
        // keep first error per field
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }
}
