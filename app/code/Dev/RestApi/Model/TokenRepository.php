<?php
namespace Dev\RestApi\Model;

use Dev\RestApi\Api\TokenRepositoryInterface;
use Dev\RestApi\Model\TokenFactory;
use Dev\RestApi\Model\ResourceModel\Token\CollectionFactory;

class TokenRepository implements TokenRepositoryInterface
{
    private $tokenFactory;
    private $collectionFactory;

    public function __construct(
        TokenFactory $tokenFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function save($tokenValue)
    {
        $token = $this->tokenFactory->create();
        $token->setTokenValue($tokenValue);
        $token->save();
        return $token;
    }
}
