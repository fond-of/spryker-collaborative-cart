<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Business\Model;

use FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCompanyUserFacadeInterface;
use Generated\Shared\Transfer\ClaimCartRequestTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CompanyUserReader implements CompanyUserReaderInterface
{
    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \FondOfSpryker\Zed\CollaborativeCart\Dependency\Facade\CollaborativeCartToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(CollaborativeCartToCompanyUserFacadeInterface $companyUserFacade)
    {
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ClaimCartRequestTransfer $claimCartRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function getActiveByClaimCartRequestAndQuote(
        ClaimCartRequestTransfer $claimCartRequestTransfer,
        QuoteTransfer $quoteTransfer
    ): ?CompanyUserTransfer {
        $idCustomer = $claimCartRequestTransfer->getNewIdCustomer();

        if ($idCustomer === null) {
            return null;
        }

        $companyUser = $quoteTransfer->getCompanyUser();

        if (
            $companyUser === null
            || $companyUser->getFkCompany() === null
            || $companyUser->getFkCompanyBusinessUnit() === null
        ) {
            return null;
        }

        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCustomer($idCustomer)
            ->setIdCompany($companyUser->getFkCompany())
            ->setIdCompanyBusinessUnit($companyUser->getFkCompanyBusinessUnit())
            ->setIsActive(true);

        $companyUserCollectionTransfer = $this->companyUserFacade
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        if ($companyUserCollectionTransfer->getCompanyUsers()->count() === 0) {
            return null;
        }

        return $this->findCompanyUserInCompanyUserCollection($companyUserCollectionTransfer, $idCustomer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     * @param int $idCustomer
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findCompanyUserInCompanyUserCollection(
        CompanyUserCollectionTransfer $companyUserCollectionTransfer,
        int $idCustomer
    ): ?CompanyUserTransfer {
        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            if ($companyUserTransfer->getFkCustomer() !== $idCustomer) {
                continue;
            }

            return $companyUserTransfer;
        }

        return null;
    }
}
