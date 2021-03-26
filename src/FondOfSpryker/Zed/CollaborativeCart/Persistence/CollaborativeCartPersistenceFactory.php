<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Persistence;

use FondOfSpryker\Zed\CollaborativeCart\CollaborativeCartDependencyProvider;
use FondOfSpryker\Zed\CompanyUsersRestApi\Persistence\Mapper\CompanyUserMapper;
use FondOfSpryker\Zed\CompanyUsersRestApi\Persistence\Mapper\CompanyUserMapperInterface;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \FondOfSpryker\Zed\CollaborativeCart\Persistence\CollaborativeCartRepositoryInterface getRepository()
 * @method \FondOfSpryker\Zed\CollaborativeCart\CollaborativeCartConfig getConfig()
 */
class CollaborativeCartPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(CollaborativeCartDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    /**
     * @return \FondOfSpryker\Zed\CompanyUsersRestApi\Persistence\Mapper\CompanyUserMapperInterface
     */
    public function createCompanyUserMapper(): CompanyUserMapperInterface
    {
        return new CompanyUserMapper();
    }
}
