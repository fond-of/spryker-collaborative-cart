<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\CartClaimerInterface;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteExpanderInterface;
use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\ClaimCartResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CollaborativeCartFacadeTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|CollaborativeCartBusinessFactory
     */
    protected $collaborativeCartBusinessFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\CollaborativeCart\Business\Model\CartClaimerInterface
     */
    protected $cartClaimerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ClaimCartRequestTransfer
     */
    protected $claimCartRequestTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ClaimCartResponseTransfer
     */
    protected $claimCartResponseTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteExpanderInterface
     */
    protected $quoteExpanderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransferMock;

    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Business\CollaborativeCartFacade
     */
    protected $collaborativeCartFacade;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->collaborativeCartBusinessFactoryMock = $this->getMockBuilder(CollaborativeCartBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartClaimerMock = $this->getMockBuilder(CartClaimerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->claimCartRequestTransferMock = $this->getMockBuilder(ClaimCartRequestTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->claimCartResponseTransferMock = $this->getMockBuilder(ClaimCartResponseTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteExpanderMock = $this->getMockBuilder(QuoteExpanderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collaborativeCartFacade = new CollaborativeCartFacade();
        $this->collaborativeCartFacade->setFactory($this->collaborativeCartBusinessFactoryMock);
    }

    /**
     * @return void
     */
    public function testClaimCart(): void
    {
        $this->collaborativeCartBusinessFactoryMock->expects(self::atLeastOnce())
            ->method('createCartClaimer')
            ->willReturn($this->cartClaimerMock);

        $this->cartClaimerMock->expects(self::atLeastOnce())
            ->method('claim')
            ->with($this->claimCartRequestTransferMock)
            ->willReturn($this->claimCartResponseTransferMock);

        self::assertEquals(
            $this->claimCartResponseTransferMock,
            $this->collaborativeCartFacade->claimCart($this->claimCartRequestTransferMock)
        );
    }

    /**
     * @return void
     */
    public function testExpandQuote(): void
    {
        $this->collaborativeCartBusinessFactoryMock->expects(self::atLeastOnce())
            ->method('createQuoteExpander')
            ->willReturn($this->quoteExpanderMock);

        $this->quoteExpanderMock->expects(self::atLeastOnce())
            ->method('expand')
            ->with($this->quoteTransferMock)
            ->willReturn($this->quoteTransferMock);

        self::assertEquals(
            $this->quoteTransferMock,
            $this->collaborativeCartFacade->expandQuote($this->quoteTransferMock)
        );
    }
}
