<?php

namespace App\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\LineItem;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class LineItemProcessor implements ProcessorInterface
{
    protected const MAX_LINE_ITEMS_PER_CART = 3;

    public function __construct(private ProcessorInterface $persistProcessor, private ProcessorInterface $removeProcessor)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof DeleteOperationInterface) {
            return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
        }

        if($data instanceof LineItem && $data->getCart()->getLineItems()->count() >= self::MAX_LINE_ITEMS_PER_CART){
            throw new UnprocessableEntityHttpException();
        }

        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        return $result;
    }

}