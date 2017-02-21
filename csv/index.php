<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/PHPExcel/Classes/PHPExcel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PHPExcel/Classes/PHPExcel/Writer/IWriter.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PHPExcel/Classes/PHPExcel/Writer/Abstract.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PHPExcel/Classes/PHPExcel/Writer/CSV.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PHPExcel/Classes/PHPExcel/Reader/CSV.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Faker/src/autoload.php';

function exception_error_handler($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity)) {
        // Этот код ошибки не входит в error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}

set_error_handler("exception_error_handler");



$locale = 'ru';
$validLocale = PHPExcel_Settings::setLocale($locale);
if (!$validLocale) {
    echo 'Unable to set locale to ' . $locale . ' - reverting to ru <br>';
}

//$timestamp1 = time();
//$commonWriter = new PHPExcel_Reader_CSV();
//$commonPHPExcel = $commonWriter->load('common.csv');
//$commonPHPExcel->setActiveSheetIndex(0);
//$commonSheet = $commonPHPExcel->getActiveSheet();
//$commonArray = $commonSheet->toArray();
////$newCommonArray = array_combine(array_column($commonArray, 0) , $commonArray);
//$timestamp2 = time();
//$personalWriter = new PHPExcel_Reader_CSV();
//$personalPHPExcel = $personalWriter->load('personal.csv');
//$personalPHPExcel->setActiveSheetIndex(0);
//$personalSheet = $personalPHPExcel->getActiveSheet();
//$personalArray = $personalSheet->toArray();
//$newPersonalArray = array_combine(array_column($personalArray, 0) , $personalArray);
$timestamp3 = time();
$commonFile= file('common.csv');
$commonKeys =  array_column($commonFile, 1);
echo count($commonFile).' '.count($commonKeys);
//$commonArray = array_combine(array_column($commonFile, 0) , $commonFile);
//$personalFile= file('personal.csv');
//$personalArray = array_combine(array_column($personalFile, 0) , $personalFile);
//$timestamp4 = time();
file_put_contents('times.log', $timestamp1."\t".$timestamp2."\t".$timestamp3."\t".$timestamp4."\t".($timestamp2-$timestamp1)."\t".($timestamp3-$timestamp2)."\t".($timestamp4-$timestamp3));
file_put_contents('common.log', print_r($commonFile, true));
file_put_contents('personal.log', print_r($personalArray, true));




//$info = array();
//
//$faker = Faker\Factory::create('ru_RU');
//
//for ($i = 1; $i <= 500; $i++) {
//    $info[$i]['article'] = $faker->numberBetween($min = 1, $max = 8000);
////    $info[$i]['commonPrice'] = $faker->numberBetween($min = 1000, $max = 9000);
//    $info[$i]['personPrice'] = $faker->numberBetween($min = 1000, $max = 9000);
////    $info[$i]['artHolding'] = $faker->userName;
////    $info[$i]['partnumber'] = $faker->swiftBicNumber;
////    $info[$i]['model'] = $faker->company;
////    $info[$i]['name'] = $faker->sentences[0];
//}
//
//$sheet->fromArray($info);
//
//$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
//
//$filename = 'info.csv';
//
//$objWriter->save($filename);
//
//header('Content-Type: text/csv');
//header('Content-disposition: attachment; filename=' . 'info.csv');
//readfile('info.csv');
//unlink($filename);
