<?php

namespace FondOfSpryker\Client\CollaborativeCart\Zed;

use FondOfSpryker\Client\CollaborativeCart\Dependency\Client\CollaborativeCartToZedRequestClientInterface;
use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\ClaimCartResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CollaborativeCartStub implements CollaborativeCartStubInterface
{
    /**
     * @var \FondOfSpryker\Client\CollaborativeCart\Dependency\Client\CollaborativeCartToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * CollaborativeCartStub constructor.
     *
     * @param \FondOfSpryker\Client\CollaborativeCart\Dependency\Client\CollaborativeCartToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CollaborativeCartToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ClaimCartResponseTransfer
     */
    public function claimCart(ClaimCartRequestTransfer $claimCartRequestTransfer): ClaimCartResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ClaimCartRequestTransfer $criteriaFilterTransfer */
        $claimCartRequestResponseTransfer = $this->zedStub->call('/collaborative-cart/gateway/claim-cart', $claimCartRequestTransfer);

        return $claimCartRequestResponseTransfer;
    }
}
