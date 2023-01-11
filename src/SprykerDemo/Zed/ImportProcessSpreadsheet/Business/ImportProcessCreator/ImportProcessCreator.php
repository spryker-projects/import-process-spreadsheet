<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessCreator;

use Generated\Shared\Transfer\ImportProcessTransfer;
use Orm\Zed\ImportProcess\Persistence\Map\PyzImportProcessTableMap;
use Pyz\Zed\Acl\Business\AclFacadeInterface;
use SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheet\Business\SpreadsheetManager\SpreadsheetManagerInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheet\ImportProcessSpreadsheetConfig;

class ImportProcessCreator implements ImportProcessCreatorInterface
{
    /**
     * @var \SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface
     */
    protected ImportProcessFacadeInterface $importProcessFacade;

    /**
     * @var \SprykerDemo\Zed\ImportProcessSpreadsheet\Business\SpreadsheetManager\SpreadsheetManagerInterface
     */
    protected SpreadsheetManagerInterface $spreadsheetManager;

    /**
     * @var \Pyz\Zed\Acl\Business\AclFacadeInterface
     */
    protected AclFacadeInterface $aclFacade;

    /**
     * @var \SprykerDemo\Zed\ImportProcessSpreadsheet\ImportProcessSpreadsheetConfig
     */
    protected ImportProcessSpreadsheetConfig $config;

    /**
     * @param \SprykerDemo\Zed\ImportProcess\Business\ImportProcessFacadeInterface $importProcessFacade
     * @param \SprykerDemo\Zed\ImportProcessSpreadsheet\Business\SpreadsheetManager\SpreadsheetManagerInterface $spreadsheetManager
     * @param \Pyz\Zed\Acl\Business\AclFacadeInterface $aclFacade
     * @param \SprykerDemo\Zed\ImportProcessSpreadsheet\ImportProcessSpreadsheetConfig $config
     */
    public function __construct(
        ImportProcessFacadeInterface $importProcessFacade,
        SpreadsheetManagerInterface $spreadsheetManager,
        AclFacadeInterface $aclFacade,
        ImportProcessSpreadsheetConfig $config
    ) {
        $this->importProcessFacade = $importProcessFacade;
        $this->spreadsheetManager = $spreadsheetManager;
        $this->aclFacade = $aclFacade;
        $this->config = $config;
    }

    /**
     * @param string $spreadsheetUrl
     * @param array $sheetNames
     *
     * @return \Generated\Shared\Transfer\ImportProcessTransfer
     */
    public function createImportProcess(string $spreadsheetUrl, array $sheetNames): ImportProcessTransfer
    {
        $spreadsheetId = $this->spreadsheetManager->getSheetIdFromUrl($spreadsheetUrl);
        $importMap = $this->spreadsheetManager
            ->uploadDataFromSpreadsheetBySheetNames($spreadsheetId, $sheetNames);

        $importTypesOrderWeights = array_flip($this->importProcessFacade->getAvailableOrderedImportTypes());
        uksort($importMap, static function (string $a, string $b) use ($importTypesOrderWeights): int {
            return $importTypesOrderWeights[$a] <=> $importTypesOrderWeights[$b];
        });

        $importProcessTransfer = new ImportProcessTransfer();
        $importProcessTransfer->setFkUser($this->aclFacade->getCurrentUser()->getIdUser());
        $importProcessTransfer->setImportMap($importMap);
        $importProcessTransfer->setFilesystem($this->config->getFileSystemName());
        $importProcessTransfer->setStatus(PyzImportProcessTableMap::COL_STATUS_CREATED);

        return $this->importProcessFacade->saveImportProcess($importProcessTransfer);
    }
}
