<?php


namespace App\Helpers;


class Date
{

    public static function dmYKZ($date){
        $date = strtotime($date);
        $day = date('d',$date);
        $year = date('Y',$date);
        $month = date('m',$date);
        switch ($month){
            case 1:
                $month = 'қаңтар';
                break;
            case 2:
                $month = 'ақпан';
                break;
            case 3:
                $month = 'наурыз';
                break;
            case 4:
                $month = 'сәуір';
                break;
            case 5:
                $month = 'мамыр';
                break;
            case 6:
                $month = 'маусым';
                break;
            case 7:
                $month = 'шілде';
                break;
            case 8:
                $month = 'тамыз';
                break;
            case 9:
                $month = 'қырқүйек';
                break;
            case 10:
                $month = 'қазан';
                break;
            case 11:
                $month = 'қараша';
                break;
            case 12:
                $month = 'желтоқсан';
                break;
        }
        return "{$day} {$month} {$year}";
    }

    public static function mYKZ($date){
        $date = strtotime($date);
        $day = date('d',$date);
        $year = date('Y',$date);
        $month = date('m',$date);
        switch ($month){
            case 1:
                $month = 'қаңтар';
                break;
            case 2:
                $month = 'ақпан';
                break;
            case 3:
                $month = 'наурыз';
                break;
            case 4:
                $month = 'сәуір';
                break;
            case 5:
                $month = 'мамыр';
                break;
            case 6:
                $month = 'маусым';
                break;
            case 7:
                $month = 'шілде';
                break;
            case 8:
                $month = 'тамыз';
                break;
            case 9:
                $month = 'қырқүйек';
                break;
            case 10:
                $month = 'қазан';
                break;
            case 11:
                $month = 'қараша';
                break;
            case 12:
                $month = 'желтоқсан';
                break;
        }
        return "{$month}, {$year}";
    }

    public static function monthKz($month): string
    {
        switch ($month){
            case 1:
                return 'Қаңтар';
            case 2:
                return 'Ақпан';
            case 3:
                return 'Наурыз';
            case 4:
                return 'Сәуір';
            case 5:
                return 'Мамыр';
            case 6:
                return 'Маусым';
            case 7:
                return 'Шілде';
            case 8:
                return 'Тамыз';
            case 9:
                return 'Қырқүйек';
            case 10:
                return 'Қазан';
            case 11:
                return 'Қараша';
            case 12:
                return 'Желтоқсан';
            default:
                return 'Қаңтар';
        }
    }
}
