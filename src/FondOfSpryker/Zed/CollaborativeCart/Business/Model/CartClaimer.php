<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business\Model;

use Exception;
use FondOfSpryker\Zed\CollaborativeCart\Business\Exception\QuoteCouldNotBeClaimedException;
use FondOfSpryker\Zed\CollaborativeCart\Communication\Plugin\PermissionExtension\CollaborateCartPermissionPlugin;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCustomerFacadeInterface;
use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToPermissionFacadeInterface;
use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\ClaimCartResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CartClaimer implements CartClaimerInterface
{
    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Business\Model\CompanyUserReaderInterface
     */
    protected $companyUserReader;

    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToPermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteWriterInterface
     */
    protected $quoteWriter;

    /**
     * CartClaimer constructor.
     *
     * @param \FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteReaderInterface $quoteReader
     * @param \FondOfSpryker\Zed\CollaborativeCart\Business\Model\QuoteWriterInterface $quoteWriter
     * @param \FondOfSpryker\Zed\CollaborativeCart\Business\Model\CompanyUserReaderInterface $companyUserReader
     * @param \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToPermissionFacadeInterface $permissionFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        QuoteWriterInterface $quoteWriter,
        CompanyUserReaderInterface $companyUserReader,
        CollaborativeCartToPermissionFacadeInterface $permissionFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->quoteWriter = $quoteWriter;
        $this->companyUserReader = $companyUserReader;
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ClaimCartResponseTransfer
     */
    public function claim(ClaimCartRequestTransfer $claimCartRequestTransfer): ClaimCartResponseTransfer
    {
        try {
            $claimCartResponseTransfer = $this->doClaim($claimCartRequestTransfer);
        } catch (Exception $exception) {
            $claimCartResponseTransfer = (new ClaimCartResponseTransfer())
                ->setIsSuccess(false)
                ->setError($exception->getMessage());
        }

        return $claimCartResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ClaimCartResponseTransfer
     */
    protected function doClaim(ClaimCartRequestTransfer $claimCartRequestTransfer): ClaimCartResponseTransfer
    {
        $quoteTransfer = $this->getQuoteByClaimCartRequest($claimCartRequestTransfer);
        $companyUserTransfer = $this->getCompanyUserByClaimCartRequestAndQuote(
            $claimCartRequestTransfer,
            $quoteTransfer
        );

        $quoteTransfer->setOriginalCompanyUserReference($quoteTransfer->getCompanyUserReference())
            ->setOriginalCustomerReference($quoteTransfer->getCustomerReference())
            ->setCompanyUserReference($companyUserTransfer->getCompanyUserReference())
            ->setCustomerReference($claimCartRequestTransfer->getNewCustomerReference())
            ->setCustomer((new CustomerTransfer())->setCustomerReference($claimCartRequestTransfer->getNewCustomerReference()));

        return (new ClaimCartResponseTransfer())
            ->setIsSuccess(true)
            ->setQuote($this->updateQuote($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     *
     * @throws \FondOfSpryker\Zed\CollaborativeCart\Business\Exception\QuoteCouldNotBeClaimedException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteByClaimCartRequest(ClaimCartRequestTransfer $claimCartRequestTransfer): QuoteTransfer
    {
        $quoteTransfer = $this->quoteReader->getByClaimCartRequest($claimCartRequestTransfer);

        if ($quoteTransfer === null) {
            throw new QuoteCouldNotBeClaimedException('Could not find quote to claim.');
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \FondOfSpryker\Zed\CollaborativeCart\Business\Exception\QuoteCouldNotBeClaimedException
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function getCompanyUserByClaimCartRequestAndQuote(
        ClaimCartRequestTransfer $claimCartRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): CompanyUserTransfer {
        $companyUserTransfer = $this->companyUserReader->getActiveByClaimCartRequestAndQuote(
            $claimCartRequestTransfer,
            $quoteTransfer
        );

        if ($companyUserTransfer === null) {
            throw new QuoteCouldNotBeClaimedException('Could not find company user.');
        }

        $canCollaborateCartPermission = $this->permissionFacade->can(
            CollaborateCartPermissionPlugin::KEY,
            $companyUserTransfer->getIdCompanyUser()
        );

        if (!$canCollaborateCartPermission) {
            throw new QuoteCouldNotBeClaimedException('Could not find company user.');
        }

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \FondOfSpryker\Zed\CollaborativeCart\Business\Exception\QuoteCouldNotBeClaimedException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer = $this->quoteWriter->update($quoteTransfer);

        if ($quoteTransfer === null) {
            throw new QuoteCouldNotBeClaimedException('Could not update quote.');
        }

        return $quoteTransfer;
    }
}
