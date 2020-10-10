<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business\Model;

use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function getByClaimCartRequest(ClaimCartRequestTransfer $claimCartRequestTransfer): ?QuoteTransfer;
}
