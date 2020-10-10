<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteWriterInterface
{
    public function update(QuoteTransfer $quoteTransfer): ?QuoteTransfer;
}
