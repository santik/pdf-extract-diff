<?php

namespace PdfTools;

class PdfTools
{
    function cleanText($text) {
        return trim($this->cleanNewLines($this->cleanNewLines($text)));
    }

    function cleanNewLines($text) {

        $text =  preg_replace('/(?!\n|\r)[\x00-\x1F\x7F-\xFF]/u', ' ', $text);

        $text = str_replace("\r", "\n", $text);

        $text = str_replace("\n\n", "\n", $text);
        $text = str_replace("\n\n", "\n", $text);
        $text = str_replace("\n\n", "\n", $text);
        $text = str_replace("\n\n", "\n", $text);
        $text = str_replace("\n\n", "\n", $text);
        $text = str_replace("\n\n", "\n", $text);

        $text = str_replace("\t", " ", $text);
        $text = str_replace('&nbsp', " ", $text);

        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);
        $text = str_replace("  ", " ", $text);

        $text = str_replace(" \n", "\n", $text);
        $text = str_replace(" \n", "\n", $text);
        $text = str_replace(" \n", "\n", $text);
        $text = str_replace(" \n", "\n", $text);
        $text = str_replace(" \n", "\n", $text);

        $text = str_replace("\n ", "\n", $text);
        $text = str_replace("\n ", "\n", $text);
        $text = str_replace("\n ", "\n", $text);
        $text = str_replace("\n ", "\n", $text);
        $text = str_replace("\n ", "\n", $text);
        $text = str_replace("\n ", "\n", $text);

        return $text;
    }

    function removeEquals($jsonResult)
    {
        foreach ($jsonResult as $diffIndex => $diff) {
            foreach ($diff as $i => $atom) {
                if ($atom['tag'] < 2) {
                    unset($jsonResult[$diffIndex][$i]);
                }
            }
        }
        return $jsonResult;
    }

    function removeEqualsInserts($jsonResult)
    {
        foreach ($jsonResult as $diffIndex => $diff) {
            foreach ($diff as $i => $atom) {
                if ($atom['tag'] == 8 &&
                    $this->initial($atom["old"]["lines"][0]) == $this->initial($atom["new"]["lines"][0])
                ) {
                    unset($jsonResult[$diffIndex][$i]);
                }
            }
        }
        return $jsonResult;
    }

    function initial($line) {
        $raw = str_replace(["<del>", "</del>", "<ins>", "</ins>"], "", $line);

        return $this->cleanText($raw);
    }
}