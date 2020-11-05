<?php

namespace FondOfSpryker\Zed\CollaborativeCart;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCompanyUserReferenceFacadeBridge;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCustomerFacadeBridge;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToPermissionFacadeBridge;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeBridge;
use FondOfSpryker\Zed\CompanyUserReference\Business\CompanyUserReferenceFacadeInterface;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;
use Spryker\Zed\Testify\Locator\Business\Container;

class CollaborativeCartDependencyProviderTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Locator
     */
    protected $locatorMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\BundleProxy
     */
    protected $bundleProxyMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\CompanyUserReference\Business\CompanyUserReferenceFacadeInterface
     */
    protected $companyUserReferenceFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected $permissionFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    protected $quoteFacadeMock;

    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\CollaborativeCartDependencyProvider
     */
    protected $collaborativeCartDependencyProvider;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->setMethodsExcept(['factory', 'set', 'offsetSet', 'get', 'offsetGet'])
            ->getMock();

        $this->locatorMock = $this->getMockBuilder(Locator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->bundleProxyMock = $this->getMockBuilder(BundleProxy::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyUserReferenceFacadeMock = $this->getMockBuilder(CompanyUserReferenceFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerFacadeMock = $this->getMockBuilder(CustomerFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->permissionFacadeMock = $this->getMockBuilder(PermissionFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteFacadeMock = $this->getMockBuilder(QuoteFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collaborativeCartDependencyProvider = new CollaborativeCartDependencyProvider();
    }

    /**
     * @return void
     */
    public function testProvideBusinessLayerDependencies(): void
    {
        $this->containerMock->expects(self::atLeastOnce())
            ->method('getLocator')
            ->willReturn($this->locatorMock);

        $this->locatorMock->expects(self::atLeastOnce())
            ->method('__call')
            ->withConsecutive(['companyUserReference'], ['customer'], ['permission'], ['quote'])
            ->willReturn($this->bundleProxyMock);

        $this->bundleProxyMock->expects(self::atLeastOnce())
            ->method('__call')
            ->with('facade')
            ->willReturnOnConsecutiveCalls(
                $this->companyUserReferenceFacadeMock,
                $this->customerFacadeMock,
                $this->permissionFacadeMock,
                $this->quoteFacadeMock
            );

        $container = $this->collaborativeCartDependencyProvider->provideBusinessLayerDependencies(
            $this->containerMock
        );

        self::assertEquals($this->containerMock, $container);

        self::assertInstanceOf(
            CollaborativeCartToCompanyUserReferenceFacadeBridge::class,
            $container[CollaborativeCartDependencyProvider::FACADE_COMPANY_USER_REFERENCE]
        );

        self::assertInstanceOf(
            CollaborativeCartToCustomerFacadeBridge::class,
            $container[CollaborativeCartDependencyProvider::FACADE_CUSTOMER]
        );

        self::assertInstanceOf(
            CollaborativeCartToPermissionFacadeBridge::class,
            $container[CollaborativeCartDependencyProvider::FACADE_PERMISSION]
        );

        self::assertInstanceOf(
            CollaborativeCartToQuoteFacadeBridge::class,
            $container[CollaborativeCartDependencyProvider::FACADE_QUOTE]
        );
    }
}
