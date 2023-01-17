<?php

namespace App\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Cart;
use Symfony\Component\Security\Core\Security;

final class CartProcessor implements ProcessorInterface
{
    public function __construct(private ProcessorInterface $persistProcessor, private ProcessorInterface $removeProcessor, private Security $security)
    {   
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof DeleteOperationInterface) {
            return $this->removeProcessor->process($data, $operation, $uriVariables, $context);
        }
        
        if($data instanceof Cart){
            $data->setUser($this->security->getUser());
        }

        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        return $result;
    }

}