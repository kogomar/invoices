<?php

namespace Modules\Invoices\Domain\Entities;

use Ramsey\Uuid\UuidInterface;
use InvalidArgumentException;

final class InvoiceProductLine
{
    public function __construct(
        private readonly UuidInterface $id,
        private readonly UuidInterface $invoiceId,
        private readonly string $name,
        private readonly int $price,
        private readonly int $quantity,
    ) {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('The quantity must be greater than zero.');
        }
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getInvoiceId(): UuidInterface
    {
        return $this->invoiceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalPrice(): int
    {
        return $this->quantity * $this->price;
    }

    public static function create(UuidInterface $id, UuidInterface $invoiceId, string $name, int $price, int $quantity): self
    {
        return new self($id, $invoiceId, $name, $price, $quantity);
    }
}
