<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet\Business\SpreadsheetManager;

interface SpreadsheetManagerInterface
{
    /**
     * @param string $spreadsheetUrl
     *
     * @return string
     */
    public function getSheetIdFromUrl(string $spreadsheetUrl): string;

    /**
     * @param string $spreadsheetId
     *
     * @return array
     */
    public function getSheetsTitles(string $spreadsheetId): array;

    /**
     * @param string $spreadsheetId
     * @param array $sheetNames
     *
     * @return array
     */
    public function uploadDataFromSpreadsheetBySheetNames(string $spreadsheetId, array $sheetNames): array;
}
