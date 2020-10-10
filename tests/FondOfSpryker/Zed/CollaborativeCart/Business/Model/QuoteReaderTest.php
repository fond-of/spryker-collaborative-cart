<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeInterface;
use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteReaderTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ClaimCartRequestTransfer
     */
    protected $claimCartRequestTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeInterface
     */
    protected $quoteFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected $quoteResponseTransferMock;

    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteReader
     */
    protected $quoteReader;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->claimCartRequestTransferMock = $this->getMockBuilder(ClaimCartRequestTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteFacadeMock = $this->getMockBuilder(CollaborativeCartToQuoteFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteResponseTransferMock = $this->getMockBuilder(QuoteResponseTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteReader = new QuoteReader(
            $this->quoteFacadeMock
        );
    }

    /**
     * @return void
     */
    public function testGetByClaimCartRequest(): void
    {
        $idQuote = 1;
        $newCustomerReference = 'DE-1';

        $this->claimCartRequestTransferMock->expects(self::atLeastOnce())
            ->method('getIdQuote')
            ->willReturn($idQuote);

        $this->quoteFacadeMock->expects(self::atLeastOnce())
            ->method('findQuoteById')
            ->with($idQuote)
            ->willReturn($this->quoteResponseTransferMock);

        $this->quoteResponseTransferMock->expects(self::atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteResponseTransferMock->expects(self::atLeastOnce())
            ->method('getIsSuccessful')
            ->willReturn(true);

        $this->quoteTransferMock->expects(self::atLeastOnce())
            ->method('getOriginalCustomerReference')
            ->willReturn(null);

        $this->claimCartRequestTransferMock->expects(self::atLeastOnce())
            ->method('getNewCustomerReference')
            ->willReturn($newCustomerReference);

        self::assertEquals(
            $this->quoteTransferMock,
            $this->quoteReader->getByClaimCartRequest($this->claimCartRequestTransferMock)
        );
    }

    /**
     * @return void
     */
    public function testGetByClaimCartRequestWithoutIdQuote(): void
    {
        $idQuote = null;

        $this->claimCartRequestTransferMock->expects(self::atLeastOnce())
            ->method('getIdQuote')
            ->willReturn($idQuote);

        $this->quoteFacadeMock->expects(self::never())
            ->method('findQuoteById');

        $this->quoteResponseTransferMock->expects(self::never())
            ->method('getQuote');

        $this->quoteResponseTransferMock->expects(self::never())
            ->method('getIsSuccessful');

        $this->quoteTransferMock->expects(self::never())
            ->method('getOriginalCustomerReference');

        $this->claimCartRequestTransferMock->expects(self::never())
            ->method('getNewCustomerReference');

        self::assertEquals(
            null,
            $this->quoteReader->getByClaimCartRequest($this->claimCartRequestTransferMock)
        );
    }

    /**
     * @return void
     */
    public function testGetAlreadyClaimedByClaimCartRequest(): void
    {
        $idQuote = 1;
        $originalCustomerReference = 'DE-1';

        $this->claimCartRequestTransferMock->expects(self::atLeastOnce())
            ->method('getIdQuote')
            ->willReturn($idQuote);

        $this->quoteFacadeMock->expects(self::atLeastOnce())
            ->method('findQuoteById')
            ->with($idQuote)
            ->willReturn($this->quoteResponseTransferMock);

        $this->quoteResponseTransferMock->expects(self::atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteResponseTransferMock->expects(self::atLeastOnce())
            ->method('getIsSuccessful')
            ->willReturn(true);

        $this->quoteTransferMock->expects(self::atLeastOnce())
            ->method('getOriginalCustomerReference')
            ->willReturn($originalCustomerReference);

        $this->claimCartRequestTransferMock->expects(self::never())
            ->method('getNewCustomerReference');

        self::assertEquals(
            null,
            $this->quoteReader->getByClaimCartRequest($this->claimCartRequestTransferMock)
        );
    }
}
