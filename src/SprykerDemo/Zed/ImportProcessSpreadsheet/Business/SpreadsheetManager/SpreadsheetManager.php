<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerDemo\Zed\ImportProcessSpreadsheet\Business\SpreadsheetManager;

use SprykerDemo\Service\GoogleSpreadsheets\GoogleSpreadsheetsServiceInterface;
use SprykerDemo\Zed\ImportProcessSpreadsheet\ImportProcessSpreadsheetConfig;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use SprykerDemo\Zed\Uploads\Business\UploadsFacadeInterface;

class SpreadsheetManager implements SpreadsheetManagerInterface
{
    protected const FILE_PREFIX = 'sheetContent';

    /**
     * @var \SprykerDemo\Service\GoogleSpreadsheets\GoogleSpreadsheetsServiceInterface
     */
    protected GoogleSpreadsheetsServiceInterface $googleSpreadsheetsService;

    /**
     * @var \SprykerDemo\Zed\Uploads\Business\UploadsFacadeInterface
     */
    protected UploadsFacadeInterface $uploadsFacade;

    /**
     * @var \SprykerDemo\Zed\ImportProcessSpreadsheet\ImportProcessSpreadsheetConfig
     */
    protected ImportProcessSpreadsheetConfig $config;

    /**
     * @param \SprykerDemo\Service\GoogleSpreadsheets\GoogleSpreadsheetsServiceInterface $googleSpreadsheetsService
     * @param \SprykerDemo\Zed\Uploads\Business\UploadsFacadeInterface $uploadsFacade
     * @param \SprykerDemo\Zed\ImportProcessSpreadsheet\ImportProcessSpreadsheetConfig $config
     */
    public function __construct(
        GoogleSpreadsheetsServiceInterface $googleSpreadsheetsService,
        UploadsFacadeInterface $uploadsFacade,
        ImportProcessSpreadsheetConfig $config
    ) {
        $this->googleSpreadsheetsService = $googleSpreadsheetsService;
        $this->uploadsFacade = $uploadsFacade;
        $this->config = $config;
    }

    /**
     * @param string $spreadsheetUrl
     *
     * @return string
     */
    public function getSheetIdFromUrl(string $spreadsheetUrl): string
    {
        $matches = [];

        preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $spreadsheetUrl, $matches);

        return $matches[1];
    }

    /**
     * @param string $spreadsheetId
     *
     * @return array
     */
    public function getSheetsTitles(string $spreadsheetId): array
    {
        return $this->googleSpreadsheetsService->getSheetNames($spreadsheetId);
    }

    /**
     * @param string $spreadsheetId
     * @param array $sheetNames
     *
     * @return array
     */
    public function uploadDataFromSpreadsheetBySheetNames(string $spreadsheetId, array $sheetNames): array
    {
        $fileSystemName = $this->config->getFileSystemName();
        $fileMap = [];
        foreach ($sheetNames as $sheetName) {
            $lines = $this->googleSpreadsheetsService->getSheetContent($spreadsheetId, $sheetName);

            $filePath = tempnam('/tmp', 'csv');
            $file = fopen($filePath, 'w');
            $columnsCount = count($lines[0]);
            foreach ($lines as $line) {
                $lineLength = count($line);
                if ($lineLength < $columnsCount) {
                    $line = array_merge($line, array_fill_keys(range($lineLength, $columnsCount - 1), ''));
                }
                fputcsv($file, $line);
            }
            fclose($file);

            $fileNamePrefix = $this->getFilePrefix($sheetName);
            $fileName = $this->getFileName($sheetName);
            $tempFile = new UploadedFile($filePath, $fileName);

            $fileUploadResponseArray = $this->uploadsFacade->upload($tempFile, $fileSystemName, $fileNamePrefix);

            unlink($filePath);

            $fileMap[$sheetName] = $fileUploadResponseArray['fileName'];
        }

        return $fileMap;
    }

    /**
     * @param string $sheetName
     *
     * @return string
     */
    protected function getFilePrefix(string $sheetName): string
    {
        return static::FILE_PREFIX;
    }

    /**
     * @param string $sheetName
     *
     * @return string
     */
    protected function getFileName(string $sheetName): string
    {
        return $sheetName . '.csv';
    }
}
