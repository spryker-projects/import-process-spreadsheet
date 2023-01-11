<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet\Business;

use Pyz\Zed\Acl\Business\AclFacadeInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerDemo\Service\GoogleSpreadsheets\GoogleSpreadsheetsServiceInterface;
use SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessCreator\ImportProcessCreator;
use SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessCreator\ImportProcessCreatorInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheet\Business\SpreadsheetManager\SpreadsheetManager;
use SprykerDemo\Zed\ImportProcessSpreadsheet\Business\SpreadsheetManager\SpreadsheetManagerInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheet\ImportProcessSpreadsheetDependencyProvider;
use SprykerDemo\Zed\Uploads\Business\UploadsFacadeInterface;

/**
 * @method \SprykerDemo\Zed\ImportProcessSpreadsheet\ImportProcessSpreadsheetConfig getConfig()
 */
class ImportProcessSpreadsheetBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\Acl\Business\AclFacadeInterface
     */
    public function getAclFacade(): AclFacadeInterface
    {
        return $this->getProvidedDependency(ImportProcessSpreadsheetDependencyProvider::FACADE_ACL);
    }

    /**
     * @return \SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface
     */
    public function getImportProcessFacade(): ImportProcessFacadeInterface
    {
        return $this->getProvidedDependency(ImportProcessSpreadsheetDependencyProvider::FACADE_PRODUCT_IMPORT_PROCESS);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(ImportProcessSpreadsheetDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \SprykerDemo\Zed\Uploads\Business\UploadsFacadeInterface
     */
    public function getUploadsFacade(): UploadsFacadeInterface
    {
        return $this->getProvidedDependency(ImportProcessSpreadsheetDependencyProvider::FACADE_UPLOADS);
    }

    /**
     * @return \SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessCreator\ImportProcessCreatorInterface
     */
    public function createImportProcessCreator(): ImportProcessCreatorInterface
    {
        return new ImportProcessCreator(
            $this->getImportProcessFacade(),
            $this->createSpreadsheetManager(),
            $this->getAclFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerDemo\Zed\ImportProcessSpreadsheet\Business\SpreadsheetManager\SpreadsheetManagerInterface
     */
    public function createSpreadsheetManager(): SpreadsheetManagerInterface
    {
        return new SpreadsheetManager(
            $this->getGoogleSpreadsheetsService(),
            $this->getUploadsFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerDemo\Service\GoogleSpreadsheets\GoogleSpreadsheetsServiceInterface
     */
    public function getGoogleSpreadsheetsService(): GoogleSpreadsheetsServiceInterface
    {
        return $this->getProvidedDependency(ImportProcessSpreadsheetDependencyProvider::SERVICE_GOOGLE_SPREADSHEETS);
    }
}
