<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business;

use FondOfSpryker\Zed\CollaborativeCart\Business\Model\CartClaimer;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\CartClaimerInterface;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\CompanyUserReader;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\CompanyUserReaderInterface;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteExpander;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteExpanderInterface;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteReader;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteReaderInterface;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteWriter;
use FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteWriterInterface;
use FondOfSpryker\Zed\CollaborativeCart\Business\Releaser\CartReleaser;
use FondOfSpryker\Zed\CollaborativeCart\Business\Releaser\CartReleaserInterface;
use FondOfSpryker\Zed\CollaborativeCart\CollaborativeCartDependencyProvider;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCompanyUserReferenceFacadeInterface;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCustomerFacadeInterface;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToPermissionFacadeInterface;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\CollaborativeCart\CollaborativeCartConfig getConfig()
 * @method \FondOfSpryker\Zed\CollaborativeCart\Persistence\CollaborativeCartRepositoryInterface getRepository()
 */
class CollaborativeCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteExpanderInterface
     */
    public function createQuoteExpander(): QuoteExpanderInterface
    {
        return new QuoteExpander(
            $this->getCustomerFacade(),
            $this->getCompanyUserReferenceFacade()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Business\Model\CartClaimerInterface
     */
    public function createCartClaimer(): CartClaimerInterface
    {
        return new CartClaimer(
            $this->createQuoteReader(),
            $this->createQuoteWriter(),
            $this->createCompanyUserReader(),
            $this->getPermissionFacade()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Business\Releaser\CartReleaserInterface
     */
    public function createCartReleaser(): CartReleaserInterface
    {
        return new CartReleaser(
            $this->createQuoteReader(),
            $this->createQuoteWriter()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteReaderInterface
     */
    protected function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader($this->getQuoteFacade());
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteWriterInterface
     */
    protected function createQuoteWriter(): QuoteWriterInterface
    {
        return new QuoteWriter($this->getQuoteFacade());
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Business\Model\CompanyUserReaderInterface
     */
    protected function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader(
            $this->getRepository()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCompanyUserReferenceFacadeInterface
     */
    protected function getCompanyUserReferenceFacade(): CollaborativeCartToCompanyUserReferenceFacadeInterface
    {
        return $this->getProvidedDependency(CollaborativeCartDependencyProvider::FACADE_COMPANY_USER_REFERENCE);
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCustomerFacadeInterface
     */
    protected function getCustomerFacade(): CollaborativeCartToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CollaborativeCartDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToPermissionFacadeInterface
     */
    protected function getPermissionFacade(): CollaborativeCartToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(CollaborativeCartDependencyProvider::FACADE_PERMISSION);
    }

    /**
     * @return \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToQuoteFacadeInterface
     */
    protected function getQuoteFacade(): CollaborativeCartToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(CollaborativeCartDependencyProvider::FACADE_QUOTE);
    }
}
