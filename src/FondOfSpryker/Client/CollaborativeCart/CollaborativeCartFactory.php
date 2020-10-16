<?php

namespace FondOfSpryker\Client\CollaborativeCart;

use FondOfSpryker\Client\CollaborativeCart\Dependency\Client\CollaborativeCartToZedRequestClientInterface;
use FondOfSpryker\Client\CollaborativeCart\Zed\CollaborativeCartStub;
use FondOfSpryker\Client\CollaborativeCart\Zed\CollaborativeCartStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CollaborativeCartFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Client\CollaborativeCart\Zed\CollaborativeCartStubInterface
     */
    public function createZedStub(): CollaborativeCartStubInterface
    {
        return new CollaborativeCartStub($this->getZedRequestClient());
    }

    /**
     * @return \FondOfSpryker\Client\CollaborativeCart\Dependency\Client\CollaborativeCartToZedRequestClientInterface
     *
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getZedRequestClient(): CollaborativeCartToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CollaborativeCartDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
