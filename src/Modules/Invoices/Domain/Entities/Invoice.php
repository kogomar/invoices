<?php

namespace Modules\Invoices\Domain\Entities;

use Modules\Invoices\Domain\Enums\StatusEnum;
use Ramsey\Uuid\UuidInterface;

final class Invoice
{
    private StatusEnum $status;

    private function __construct(
        private readonly UuidInterface $id,
        private readonly string $customerName,
        private readonly string $customerEmail,
        private array $productLines = [],
    )
    {
        $this->status = StatusEnum::Draft;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function getProductLines(): array
    {
        return $this->productLines;
    }

    public function getTotalPrice(): int
    {
        return array_reduce($this->productLines, static function ($total, InvoiceProductLine $line) {
            return $total + ($line->getQuantity() * $line->getPrice());
        }, 0);
    }

    public function addProductLine(InvoiceProductLine $productLine): void
    {
        $this->productLines[] = $productLine;
    }

    public function setStatus(StatusEnum $status): void
    {
        $this->status = $status;
    }

    public static function create(UuidInterface $id, string $customerName, string $customerEmail): self
    {
        return new self($id, $customerName, $customerEmail);
    }
}
