<?php
namespace Dev\RestApi\Api;

interface TokenRepositoryInterface
{
    public function save($tokenValue);
}
