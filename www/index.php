<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once "config.php";

use Jfcherng\Diff\DiffHelper;
use League\Csv\Writer;
use PdfTools\PdfTools;
use Smalot\PdfParser\Parser;

print_r("Starting pdf compare app \n");

$file1 = $argv[1];
$file2 = $argv[2];
$file1 = "file1.pdf";
$file2 = "file2.pdf";
print_r("Compairing files : $file1 and $file2 \n");

$parser = new Parser();
$pdf1    = $parser->parseFile($filesPath . $file1);
$pdf2    = $parser->parseFile($filesPath . $file2);

$pdfTools = new PdfTools();

$rendererName = "Json";
$differOptions = [
    // show how many neighbor lines
    // Differ::CONTEXT_ALL can be used to show the whole file
    'context' => 3,
    'ignoreCase' => true,
    'ignoreWhitespace' => true,
];
$cleanText1 = $pdfTools->cleanText($pdf1->getText());
//print_r($cleanText1); exit;
$cleanText2 = $pdfTools->cleanText($pdf2->getText());
$result = DiffHelper::calculate(
    $cleanText1,
    $cleanText2,
    $rendererName, $differOptions);

$jsonResult = json_decode($result, true);
$jsonResult = $pdfTools->removeEquals($jsonResult);
$jsonResult = $pdfTools->removeEqualsInserts($jsonResult);

print_r($jsonResult);exit;

$filename = "search_" . $file1 . $file2 . ".csv";
print_r("Writing results to the file $filename \n");
file_put_contents($filename, $csv);

print_r("Stopping application \n");
