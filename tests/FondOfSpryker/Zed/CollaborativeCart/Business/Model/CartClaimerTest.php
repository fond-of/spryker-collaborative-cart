<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\CollaborativeCart\Communication\Plugin\PermissionExtension\CollaborateCartPermissionPlugin;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToPermissionFacadeInterface;
use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CartClaimerTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteReaderInterface
     */
    protected $quoteReaderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteWriterInterface
     */
    protected $quoteWriterMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|CompanyUserReaderInterface
     */
    protected $companyUserReaderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToPermissionFacadeInterface
     */
    protected $permissionFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ClaimCartRequestTransfer
     */
    protected $claimCartRequestTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransferMock;

    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Business\Model\CartClaimer
     */
    protected $cartClaimer;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->quoteReaderMock = $this->getMockBuilder(QuoteReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteWriterMock = $this->getMockBuilder(QuoteWriterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserReaderMock = $this->getMockBuilder(CompanyUserReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->permissionFacadeMock = $this->getMockBuilder(CollaborativeCartToPermissionFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->claimCartRequestTransferMock = $this->getMockBuilder(ClaimCartRequestTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserTransferMock = $this->getMockBuilder(CompanyUserTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartClaimer = new CartClaimer(
            $this->quoteReaderMock,
            $this->quoteWriterMock,
            $this->companyUserReaderMock,
            $this->permissionFacadeMock
        );
    }

    /**
     * @return void
     */
    public function testClaim(): void
    {
        $idCompanyUser = 1;
        $companyUserReference = 'DE-CU-1';
        $currentCompanyUserReference = 'DE-CU-2';
        $customerReference = 'DE-CU-1';
        $currentCustomerReference = 'DE-CU-2';

        $this->quoteReaderMock->expects(self::atLeastOnce())
            ->method('getByClaimCartRequest')
            ->with($this->claimCartRequestTransferMock)
            ->willReturn($this->quoteTransferMock);

        $this->companyUserReaderMock->expects(self::atLeastOnce())
            ->method('getActiveByClaimCartRequestAndQuote')
            ->with($this->claimCartRequestTransferMock, $this->quoteTransferMock)
            ->willReturn($this->companyUserTransferMock);

        $this->companyUserTransferMock->expects(self::atLeastOnce())
            ->method('getIdCompanyUser')
            ->willReturn($idCompanyUser);

        $this->permissionFacadeMock->expects(self::atLeastOnce())
            ->method('can')
            ->with(CollaborateCartPermissionPlugin::KEY, $idCompanyUser)
            ->willReturn($idCompanyUser);

        $claimCartResponseTransfer = $this->cartClaimer->claim($this->claimCartRequestTransferMock);
    }
}
