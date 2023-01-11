<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ImportProcessSpreadsheetConfig extends AbstractBundleConfig
{
    protected const FILESYSTEM_NAME = 's3-import';

    /**
     * @return string
     */
    public function getFileSystemName(): string
    {
        return self::FILESYSTEM_NAME;
    }
}
