<?php

namespace PdfTools;

class PdfTools
{
    function cleanText($text)
    {
        return trim($this->cleanNewLines($this->cleanNewLines($text)));
    }

    function cleanNewLines($text)
    {

        $text = $this->replaceNbspWithSpace($text);

        $text = str_replace('&nbsp;', ' ', $text);
        $text = str_replace("\t", " ", $text);
        $text = str_replace("\r", "\n", $text);

        foreach(range(1,6) as $index) {
            $text = str_replace("\n\n", "\n", $text);
        }

        foreach(range(1,6) as $index) {
            $text = str_replace("  ", " ", $text);
        }

        foreach(range(1,6) as $index) {
            $text = str_replace(" \n", "\n", $text);
        }

        foreach(range(1,6) as $index) {
            $text = str_replace("\n ", "\n", $text);
        }

        return $text;
    }

    function replaceNbspWithSpace($content)
    {
        $string = htmlentities($content, null, 'utf-8');
        $content = str_replace("&nbsp;", " ", $string);
        $content = html_entity_decode($content);
        return $content;
    }

    function removeEquals(&$jsonResult)
    {
        foreach ($jsonResult as $i => $diff) {
            if ($diff['tag'] < 2) {
                unset($jsonResult[$i]);
            }
        }
        $jsonResult = array_values($jsonResult);
    }

    function convertToSinglePage($jsonResult)
    {
        $newResult = $jsonResult[0];

        foreach ($jsonResult as $pageNumber => $page) {
            if ($pageNumber == 0) {
                continue;
            }
            foreach ($page as $diff) {
                $newResult[] = $diff;
            }
        }

        return $newResult;
    }

    function convertInsDelToDiff(&$jsonResult)
    {
        foreach ($jsonResult as $i => $diff) {
            if ($diff['tag'] == 2 && isset($jsonResult[$i + 1]) && $jsonResult[$i + 1]["tag"] == 4) {
                $jsonResult[$i + 1]["tag"] = 8;
                $jsonResult[$i + 1]["old"] = $diff["old"];
                unset($jsonResult[$i]);
            }

            if ($diff['tag'] == 4 && isset($jsonResult[$i + 1]) && $jsonResult[$i + 1]["tag"] == 2) {
                $jsonResult[$i + 1]["tag"] = 8;
                $jsonResult[$i + 1]["new"] = $diff["new"];
                unset($jsonResult[$i]);
            }

        }
        $jsonResult = array_values($jsonResult);

    }

    function removeEqualsInserts(&$jsonResult)
    {
        foreach ($jsonResult as $i => $atom) {
            if ($atom['tag'] == 8 &&
                $this->initial($atom["old"]["lines"][0]) == $this->initial($atom["new"]["lines"][0])
            ) {
                unset($jsonResult[$i]);
            }
        }

    }

    function initial($line)
    {
        $raw = str_replace(["<del>", "</del>", "<ins>", "</ins>"], "", $line);

        return $this->cleanText($raw);
    }
}