<?php

namespace PdfTools;

class DBTools
{
    /** @var ADOConnection */
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function cleanInput($data) {
        foreach ($data as $key => $value) {
            $data[$key] =  preg_replace('/[\x00-\x1F\x80-\xFF]/', '',
                str_replace("\n", " ", $value)
            );
        }

        return $data;
    }

    public function insert($table, $data) {
        $this->db->autoExecute($table,$data,'INSERT');
    }

}