<?php

namespace FondOfSpryker\Zed\CollaborativeCart\Communication\Plugin\PermissionExtension;

use Codeception\Test\Unit;

class CollaborateCartPermissionPluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\CollaborativeCart\Communication\Plugin\PermissionExtension\CollaborateCartPermissionPlugin
     */
    protected $collaborateCartPermissionPlugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->collaborateCartPermissionPlugin = new CollaborateCartPermissionPlugin();
    }

    /**
     * @return void
     */
    public function testGetKey(): void
    {
        self::assertEquals(
            CollaborateCartPermissionPlugin::KEY,
            $this->collaborateCartPermissionPlugin->getKey()
        );
    }
}
