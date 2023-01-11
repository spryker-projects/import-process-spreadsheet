<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet\Business\ImportProcessCreator;

use Generated\Shared\Transfer\ImportProcessTransfer;

interface ImportProcessCreatorInterface
{
    /**
     * @param string $spreadsheetUrl
     * @param array $sheetNames
     *
     * @return \Generated\Shared\Transfer\ImportProcessTransfer
     */
    public function createImportProcess(string $spreadsheetUrl, array $sheetNames): ImportProcessTransfer;
}
