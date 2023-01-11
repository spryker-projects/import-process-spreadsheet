<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ImportProcessSpreadsheetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const FACADE_ACL = 'FACADE_ACL';
    public const FACADE_PRODUCT_IMPORT_PROCESS = 'FACADE_PRODUCT_IMPORT_PROCESS';
    public const FACADE_UPLOADS = 'FACADE_UPLOADS';
    public const SERVICE_GOOGLE_SPREADSHEETS = 'SERVICE_GOOGLE_SPREADSHEETS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSessionClient($container);
        $container = $this->addAclFacade($container);
        $container = $this->addImportProcessFacade($container);
        $container = $this->addUploadFacade($container);
        $container = $this->addGoogleSpreadsheetsService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUploadFacade(Container $container): Container
    {
        $container[static::FACADE_UPLOADS] = static function (Container $container) {
            return $container->getLocator()->uploads()->facade();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclFacade(Container $container)
    {
        $container->set(static::FACADE_ACL, function (Container $container) {
            return $container->getLocator()->acl()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addImportProcessFacade(Container $container)
    {
        $container->set(static::FACADE_PRODUCT_IMPORT_PROCESS, function (Container $container) {
            return $container->getLocator()->importProcess()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGoogleSpreadsheetsService(Container $container)
    {
        $container->set(static::SERVICE_GOOGLE_SPREADSHEETS, function (Container $container) {
            return $container->getLocator()->googleSpreadsheets()->service();
        });

        return $container;
    }
}
