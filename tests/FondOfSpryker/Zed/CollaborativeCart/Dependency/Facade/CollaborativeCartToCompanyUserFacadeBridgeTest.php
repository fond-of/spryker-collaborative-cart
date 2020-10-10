<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;

class CollaborativeCartToCompanyUserFacadeBridgeTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected $companyUserFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer
     */
    protected $companyUserCriteriaFilterTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    protected $companyUserCollectionTransferMock;

    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCompanyUserFacadeBridge
     */
    protected $collaborativeCartToCompanyUserFacadeBridge;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->companyUserFacadeMock = $this->getMockBuilder(CompanyUserFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserCriteriaFilterTransferMock = $this->getMockBuilder(CompanyUserCriteriaFilterTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserCollectionTransferMock = $this->getMockBuilder(CompanyUserCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collaborativeCartToCompanyUserFacadeBridge = new CollaborativeCartToCompanyUserFacadeBridge(
            $this->companyUserFacadeMock
        );
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollection(): void
    {
        $this->companyUserFacadeMock->expects(self::atLeastOnce())
            ->method('getCompanyUserCollection')
            ->with($this->companyUserCriteriaFilterTransferMock)
            ->willReturn($this->companyUserCollectionTransferMock);

        self::assertEquals(
            $this->companyUserCollectionTransferMock,
            $this->collaborativeCartToCompanyUserFacadeBridge
                ->getCompanyUserCollection($this->companyUserCriteriaFilterTransferMock)
        );
    }
}
