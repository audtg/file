<?php

$host = 'localhost:C:\Program Files\Firebird\Firebird_2_5\data\test.fdb';
$username = 'sysdba';
$password = 'masterkey';

function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // Этот код ошибки не входит в error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

$dbh = ibase_connect($host, $username, $password, 'utf-8');


function getNames() {
    $result = array();
    $preparedSQL = ibase_prepare('SELECT CATEGORY, NAME FROM MYPICTURES ORDER BY CATEGORY, NAME');
    try {
        $sth = ibase_execute($preparedSQL);
    } catch (ErrorException $e) {
        @file_put_contents('logfile.log', $e->getMessage()."\r\n", FILE_APPEND | LOCK_EX);
    }
    if ($sth && $res =  get_resource_type($sth)) {
        try{
            while($row = ibase_fetch_object($sth)) {
                $result[$row->CATEGORY][] = $row->NAME;
            }
            ibase_free_result($sth);
        } catch (ErrorException $e) {
            @file_put_contents('logfile.log', $e->getMessage().' $sth= '.$sth."\r\n", FILE_APPEND | LOCK_EX);
        }
    }
    return $result;
}

function getNamesByCategory($category) {
    $result = array();
    $preparedSQL = ibase_prepare('SELECT NAME FROM MYPICTURES WHERE CATEGORY= ? ORDER BY NAME');
    try {
        $sth = ibase_execute($preparedSQL, $category);
    } catch (ErrorException $e) {
        @file_put_contents('logfile.log', $e->getMessage()."\r\n", FILE_APPEND | LOCK_EX);
    }
    if ($sth && $res =  get_resource_type($sth)) {
        try{
            while($row = ibase_fetch_object($sth)) {
                $result[] = $row->NAME;
            }
            ibase_free_result($sth);
        } catch (ErrorException $e) {
            @file_put_contents('logfile.log', $e->getMessage().' $sth= '.$sth."\r\n", FILE_APPEND | LOCK_EX);
        }
    }
    return $result;
}

function saveFilesToDb($dbh, $data) {
     $result = array();
   $preparedSQL = ibase_prepare('INSERT INTO MYPICTURES (CATEGORY, NAME, PICTURE) VALUES (?, ?, ?) RETURNING NAME ');
    foreach ($data as $category => $item) {
        foreach ($item as $name => $picture) {
            try {
                $blobHandle = ibase_blob_create($dbh);
                ibase_blob_add($blobHandle, base64_decode($picture));
                $blobId = ibase_blob_close($blobHandle);
                $sth = ibase_execute($preparedSQL, $category, $name, $blobId);
            } catch (ErrorException $e) {
                @file_put_contents('logfile.log', $e->getMessage()."\r\n", FILE_APPEND | LOCK_EX);
            }
            if ($sth && $res = get_resource_type($sth)) {
                try{
                    $result[] = ibase_fetch_object($sth)->NAME;
                    ibase_free_result($sth);
                } catch (ErrorException $e) {
                    @file_put_contents('logfile.log', $e->getMessage().' $sth= '.$sth."\r\n", FILE_APPEND | LOCK_EX);
                }

            }
        }

    }
    ibase_query($dbh, 'commit');
    return $result;
}

function getFileFromDb($category, $name) {
    $preparedSQL = ibase_prepare('SELECT PICTURE FROM MYPICTURES WHERE CATEGORY = ? AND NAME = ?');
    try {
        $sth = ibase_execute($preparedSQL, $category, $name);
    } catch (ErrorException $e) {
        @file_put_contents('logfile.log', $e->getMessage()."\r\n", FILE_APPEND | LOCK_EX);
    }
    if ($sth && $res =  get_resource_type($sth)) {
        try{
            $blobId = ibase_fetch_object($sth)->PICTURE;
            $blobHandle = ibase_blob_open($blobId);
            $blobInfo = ibase_blob_info($blobId);
            $blobContent = ibase_blob_get($blobHandle, $blobInfo['length']);
            ibase_free_result($sth);
        } catch (ErrorException $e) {
            @file_put_contents('logfile.log', $e->getMessage().' $sth= '.$sth."\r\n", FILE_APPEND | LOCK_EX);
        }
    }
    return  $blobContent;
}

function getCategoryFromDb($category) {
    $result = array();
    $preparedSQL = ibase_prepare('SELECT PICTURE FROM MYPICTURES WHERE CATEGORY = ?');
    try {
        $sth = ibase_execute($preparedSQL, $category);
    } catch (ErrorException $e) {
        @file_put_contents('logfile.log', $e->getMessage()."\r\n", FILE_APPEND | LOCK_EX);
    }
    if ($sth && $res =  get_resource_type($sth)) {
        try{
            while($row = ibase_fetch_object($sth)) {
                $blobId = $row->PICTURE;
                $blobHandle = ibase_blob_open($blobId);
                $blobInfo = ibase_blob_info($blobId);
                $blobContent = ibase_blob_get($blobHandle, $blobInfo['length']);
                $result[] = $blobContent;
            }
            ibase_free_result($sth);
        } catch (ErrorException $e) {
            @file_put_contents('logfile.log', $e->getMessage().' $sth= '.$sth."\r\n", FILE_APPEND | LOCK_EX);
        }
    }
    return  $result;
}