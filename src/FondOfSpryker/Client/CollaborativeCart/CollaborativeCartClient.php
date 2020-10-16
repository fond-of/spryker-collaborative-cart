<?php

namespace FondOfSpryker\Client\CollaborativeCart;

use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\ClaimCartResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \FondOfSpryker\Client\CollaborativeCart\CollaborativeCartFactory getFactory()
 */
class CollaborativeCartClient extends AbstractClient implements CollaborativeCartClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ClaimCartResponseTransfer
     */
    public function claimCart(ClaimCartRequestTransfer $claimCartRequestTransfer): ClaimCartResponseTransfer
    {
        return $this->getFactory()->createZedStub()->claimCart($claimCartRequestTransfer);
    }
}
