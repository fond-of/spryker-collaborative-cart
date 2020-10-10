<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business\Model;

use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeInterface;
use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(CollaborativeCartToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function getByClaimCartRequest(
        ClaimCartRequestTransfer $claimCartRequestTransfer
    ): ?QuoteTransfer {
        $idQuote = $claimCartRequestTransfer->getIdQuote();

        if ($idQuote === null) {
            return null;
        }

        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($idQuote);
        $quoteTransfer = $quoteResponseTransfer->getQuote();

        if (
            $quoteTransfer === null
            || !$quoteResponseTransfer->getIsSuccessful()
            || $this->isAlreadyClaimed($quoteTransfer)
            || $this->isOwnedByCollaborator($claimCartRequestTransfer, $quoteTransfer)
        ) {
            return null;
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAlreadyClaimed(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getOriginalCustomerReference() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isOwnedByCollaborator(
        ClaimCartRequestTransfer $claimCartRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): bool {
        return $claimCartRequestTransfer->getNewCustomerReference() === $quoteTransfer->getOriginalCustomerReference();
    }
}
