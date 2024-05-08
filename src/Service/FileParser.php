<?php

namespace App\Service;

use DateTime;
use Exception;
use Throwable;

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

            while($row = fgetcsv($handle, 1000, "\t")) {

                if (!empty($row)) {

                    if ($i === 0) {
                        $keys = $row;
                        $i++;
                        continue;
                    }

                    try {
                        $resArray = array_combine($keys, $row);
                        $postedDate = (new DateTime($resArray['posted-date']))->format('Y-m');
                    } catch (Exception) {
                        var_dump($keys, $row);
                        continue;
                    } catch (\Throwable) {
                        try {
                            $postedDate = (new DateTime($resArray['posted-date']))->format('Y-m');
                        } catch (Exception) {
                            var_dump($keys, $row);
                            continue;
                        }
                        $keysTemp = array_slice($keys, 0, count($keys) - 1);
                        $resArray = array_combine($keysTemp, $row);
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