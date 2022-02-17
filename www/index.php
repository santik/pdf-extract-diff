<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once "config.php";

use Jfcherng\Diff\DiffHelper;
use League\Csv\Writer;
use PdfTools\PdfTools;
use Smalot\PdfParser\Parser;
use PdfTools\DBTools;


print_r("Starting pdf compare app \n");

$file1 = $argv[1];
$file2 = $argv[2];
//$file1 = "file1.pdf";
//$file2 = "file2.pdf";
print_r("Comparing files : $file1 and $file2 \n");

$dbTools = new DBTools($db);

$parser = new Parser();
$pdf1 = $parser->parseFile($filesPath . $file1);
$pdf2 = $parser->parseFile($filesPath . $file2);

$pdfTools = new PdfTools();

$differOptions = [
    'context' => 3,
    'ignoreCase' => true,
    'ignoreWhitespace' => true,
];

$result = DiffHelper::calculate(
    $pdfTools->cleanText($pdf1->getText()),
    $pdfTools->cleanText($pdf2->getText()),
    "Json", $differOptions);

$jsonResult = json_decode($result, true);

$jsonResult = $pdfTools->convertToSinglePage($jsonResult);
$pdfTools->removeEquals($jsonResult);
$pdfTools->convertInsDelToDiff($jsonResult);
$pdfTools->removeEqualsInserts($jsonResult);

$filename = "diff_" . current(explode(".", $file1)) . "_" . current(explode(".", $file2));

print_r("Writing results to the file $filename.json \n");
file_put_contents($filename . ".json", json_encode($jsonResult));

print_r("Creating CSV \n");
$header = [
    'old',
    'new'
];
$csv = Writer::createFromString();
$csv->insertOne($header);
$csvData = [];
foreach ($jsonResult as $item) {
    $oldNew = [
        "old" => implode(" ", $item['old']['lines']),
        "new" => implode(" ", $item['new']['lines'])
    ];
    $csvData[] = $oldNew;

    $dbData = $oldNew;
    $dbData['file1'] = $file1;
    $dbData['file2'] = $file2;

    if ($saveResultsToDatabase) {
        $dbTools->insert('differences', $dbData);
    }
}
$csv->insertAll($csvData);

print_r("Writing results to the file $filename.csv \n");
file_put_contents($filename . ".csv", $csv);

print_r("Stopping application \n");
