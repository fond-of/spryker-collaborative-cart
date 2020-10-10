<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Communication\Plugin\PermissionExtension;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\CollaborativeCart\CollaborativeCartConfig getConfig()
 * @method \FondOfSpryker\Zed\CollaborativeCart\Business\CollaborativeCartFacadeInterface getFacade()
 */
class CollaborateCartPermissionPlugin extends AbstractPlugin implements PermissionPluginInterface
{
    public const KEY = 'CanCollaborateCartPermissionPlugin';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
