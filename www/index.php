<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once "config.php";

use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;
use PdfTools\PdfTools;
use Smalot\PdfParser\Parser;

print_r("Starting pdf compare app \n");

$file1 = $argv[1];
$file2 = $argv[2];
print_r("Comparing files : $file1 and $file2 \n");

$parser = new Parser();
$pdf1    = $parser->parseFile($filesPath . $file1);
$pdf2    = $parser->parseFile($filesPath . $file2);

$pdfTools = new PdfTools();

$rendererName = "Json";
$differOptions = [
    'context' => 3,
    'ignoreCase' => true,
    'ignoreWhitespace' => true,
];
$rendererOptions = [
    'detailLevel' => 'line',
    'language' => 'eng',
    'lineNumbers' => false,
    'separateBlock' => true,
    'showHeader' => true,
    'spacesToNbsp' => false,
    'tabSize' => 4,
    'resultForIdenticals' => null,
    'wrapperClasses' => ['diff-wrapper'],
];
$cleanText1 = $pdfTools->cleanText($pdf1->getText());
$cleanText2 = $pdfTools->cleanText($pdf2->getText());
$result = DiffHelper::calculate(
    $cleanText1,
    $cleanText2,
    $rendererName, $differOptions);

$jsonResult = json_decode($result, true);
$htmlRenderer = RendererFactory::make('Inline', $rendererOptions);
$htmlResult = "<style>" . "\n" . DiffHelper::getStyleSheet() . "\n" . "</style>";
$htmlResult = $htmlResult . $htmlRenderer->renderArray($jsonResult);

$filename = "diff_" . current(explode(".", $file1)) . "_" . current(explode(".", $file2)) . ".html";
print_r("Writing results to the file $filename \n");
file_put_contents($filename, $htmlResult);
print_r("Stopping application \n");
