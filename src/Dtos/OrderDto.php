<?php

namespace EscolaLms\Core\Dtos;

use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class OrderDto implements DtoContract, InstantiateFromRequest
{
    private ?string $orderBy;
    private ?string $order;

    /**
     * OrderDto constructor.
     * @param string|null $orderBy
     * @param string|null $order
     */
    public function __construct(?string $orderBy, ?string $order)
    {
        $this->orderBy = $orderBy;
        $this->order = $order;
    }

    public function toArray(): array
    {
        return [
            'order_by' => $this->getOrderBy(),
            'order' => $this->getOrder()
        ];
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->get('order_by'),
            $request->get('order')
        );
    }

    /**
     * @return string|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * @return string|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }
}
