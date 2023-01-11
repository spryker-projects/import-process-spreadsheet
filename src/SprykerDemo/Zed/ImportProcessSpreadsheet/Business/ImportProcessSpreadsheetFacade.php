<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet\Business;

use Generated\Shared\Transfer\ImportProcessTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessSpreadsheetBusinessFactory getFactory()
 */
class ImportProcessSpreadsheetFacade extends AbstractFacade implements ImportProcessSpreadsheetFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $spreadsheetUrl
     *
     * @return string
     */
    public function getSheetIdFromUrl(string $spreadsheetUrl): string
    {
        return $this->getFactory()->createSpreadsheetManager()->getSheetIdFromUrl($spreadsheetUrl);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $spreadsheetId
     *
     * @return array
     */
    public function getSheetsTitles(string $spreadsheetId): array
    {
        return $this->getFactory()->createSpreadsheetManager()->getSheetsTitles($spreadsheetId);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $spreadsheetUrl
     * @param array $sheetNames
     *
     * @return \Generated\Shared\Transfer\ImportProcessTransfer
     */
    public function createImportProcess(string $spreadsheetUrl, array $sheetNames): ImportProcessTransfer
    {
        return $this->getFactory()
            ->createImportProcessCreator()
            ->createImportProcess($spreadsheetUrl, $sheetNames);
    }
}
