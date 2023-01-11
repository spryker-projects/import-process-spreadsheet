<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet\Business;

use Generated\Shared\Transfer\ImportProcessTransfer;

interface ImportProcessSpreadsheetFacadeInterface
{
    /**
     * Specification
     * - Gets sheet_id from provided spreadsheet URL
     *
     * @api
     *
     * @param string $spreadsheetUrl
     *
     * @return string
     */
    public function getSheetIdFromUrl(string $spreadsheetUrl): string;

    /**
     * Specification
     * - Gets sheets titles array
     *
     * @api
     *
     * @param string $spreadsheetId
     *
     * @return array
     */
    public function getSheetsTitles(string $spreadsheetId): array;

    /**
     * Specification
     * - Create importProcess item for spreadsheet from provided sheets
     *
     * @api
     *
     * @param string $spreadsheetUrl
     * @param array $sheetNames
     *
     * @return \Generated\Shared\Transfer\ImportProcessTransfer
     */
    public function createImportProcess(string $spreadsheetUrl, array $sheetNames): ImportProcessTransfer;
}
