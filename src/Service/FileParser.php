<?php

namespace App\Service;

use DateTime;
use Exception;

readonly class FileParser
{

    public function __construct(private Calculation $calculation)
    {

    }

    public function parseFile(string $filePath, string $yearMonth, array &$data): void
    {
        if (!file_exists($filePath)) {
            exit('Dir not found: ' . $filePath . PHP_EOL);
        }

        $files = array_diff(scandir($filePath), ['.', '..']);

        foreach ($files as $fileName) {

            $file = $filePath . '/' . $fileName;

            if (!file_exists($file)) {
                echo 'File not found: ' . $filePath . PHP_EOL;
                continue;
            }

            $handle = fopen($file, 'r');

            $i = 0;
            $keys = [];

            while($row = str_replace(["\n","\r","\v","\t","\0"," "], ',', fgetcsv($handle, 1000)[0] ?? '')) {

                if (!empty($row)) {

                    $arr = array_values(explode(',', $row));

                    if ($i === 0) {
                        $keys = array_values($arr);
                        $i++;
                        continue;
                    }

                    $resArray = array_combine($keys, array_slice($arr, 0, count($keys)));

                    try {
                        $postedDate = (new DateTime($resArray['posted-date']))->format('Y-m');
                    } catch (Exception) {
                        continue;
                    }

                    if ($postedDate === $yearMonth) {
                        $data['income'] += $this->calculation->calculateIncome($resArray);
                    }
                }

                $i++;
            }

            fclose($handle);
        }

    }

}