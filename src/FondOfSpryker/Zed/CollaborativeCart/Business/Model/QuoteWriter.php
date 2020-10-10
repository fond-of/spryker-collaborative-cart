<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business\Model;

use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeInterface;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteWriter implements QuoteWriterInterface
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function update(QuoteTransfer $quoteTransfer): ?QuoteTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->updateQuote($quoteTransfer);
        $quoteTransfer = $quoteResponseTransfer->getQuote();

        if ($quoteTransfer === null || !$quoteResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $quoteTransfer;
    }
}
