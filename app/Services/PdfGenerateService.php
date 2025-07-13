<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;
//use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DateTime;
use App\Models\Project;

class PdfGenerateService
{
    private static $center = 1750;


    public function getPdfGen($id)
    {
        $project = Project::with(['curator', 'projectType', 'audan', 'stages'])->findOrFail($id);
        $title = $project->name;
        $description = $project->description;
        $about = [
            'Инициатор проекта: ' . $project->curator,
            'Стоимость: ' . $project->invest_sum,
            'Вид проекта: ' . $project->projectType['name'],
            'Район: ' . $project->audan['name'],
            'Рабочие места: ' . $project->people_count,
            'Контактное лицо: ' . $project->phone,
        ];
        $stages = [];

        foreach ($project->stages as $index => $stage) {
            $stages[] = ($index + 1) . ' - этап: ' . $stage->name . '. Дата: ' . $stage->date;
        }
        
        
        /* ------------------------- OPTIONS -------------------------------  */
        $dir_cert_path = public_path(Project::PDF_PATH);
        $font_dir = public_path('/fonts');
        
        $img_name = time()."-invest.jpg";
        $img = $dir_cert_path."cart.jpg";

        $pic = ImageCreateFromjpeg($img);
        Header("Content-type: image/jpeg");
        
        $c_black = ImageColorAllocate($pic, 0, 0, 0);
        $c_white = ImageColorAllocate($pic, 251, 252, 252);
        $font_atyp = $font_dir.'/AtypText-Regular.ttf';
        $font_atypDisplay = $font_dir.'/AtypDisplay-Bold.ttf';
        $font_m = $font_dir.'/marta/Marta_Regular.otf';

        
        $h_info = 0;
        $width = 1980;
        $center = 990;
        $margin = 0;
        $px = 3/4;
        
        /* --------------------------------------------------------  */
        
        
        /* ------- TITLE -------- */
        $h_info = $this->setText($pic, $title, 30*$px,$font_atyp,$width, $center, 20, 50, $c_black );
        /* ---------------------------- */
        
        /* ------- About -------- */
        for($i=0; $i<6; $i++) ImageTTFtext($pic, 20, 0, 80, 250+36*$i, $c_black, $font_atyp, $about[$i]);
        /* ---------------------------- */
        
        /* ------- Stages -------- */
        for ($i = 0; $i < count($stages); $i++) imagettftext($pic, 16, 0, 80, 570 + 28 * $i, $c_black, $font_atyp, $stages[$i]);
        /* ---------------------------- */
        
        /* ------- Description -------- */
        $h_info = $this->setText($pic, $description, 24*$px, $font_atyp, 1100, 600, 30, 790, $c_black );
        /* ---------------------------- */
        
        
        
        // ✅ ВСТАВКА ИЗОБРАЖЕНИЯ ПРОЕКТА
        if (!empty($project->images) && isset($project->images[0])) {
            $imagePath = public_path('storage/images/' . $project->images[0]); // Убедитесь, что это путь к файлу
            if (file_exists($imagePath)) {
                $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
                switch (strtolower($ext)) {
                    case 'jpg':
                    case 'jpeg':
                        $insertImg = imagecreatefromjpeg($imagePath);
                        break;
                    case 'png':
                        $insertImg = imagecreatefrompng($imagePath);
                        break;
                    default:
                        $insertImg = null;
                }

                if ($insertImg) {
                    // Получение размеров
                    $dst_w = 750;
                    $dst_h = 418;
                    $src_w = imagesx($insertImg);
                    $src_h = imagesy($insertImg);

                    // Вставка (справа вверху)
                    imagecopyresampled($pic, $insertImg, 1190, 180, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
                    imagedestroy($insertImg);
                }
            }
        }
        
        
        if (!Storage::disk('public')->exists(Project::PDF_PATH)) {
            Storage::disk('public')->makeDirectory(Project::PDF_PATH);
        }
        Imagejpeg($pic,Storage::disk('public')->path(Project::PDF_PATH)."/".$img_name);
        ImageDestroy($pic);
        return $img_name;
    }

    public static function dmYKZ($date)
    {
            $date = strtotime($date);
            $day = date('d', $date);
            $year = date('Y', $date);
            $month = date('m', $date);
            switch ($month) {
                    case 1:
                            $month = 'Қаңтар';
                            break;
                    case 2:
                            $month = 'Ақпан';
                            break;
                    case 3:
                            $month = 'Наурыз';
                            break;
                    case 4:
                            $month = 'Сәуір';
                            break;
                    case 5:
                            $month = 'Мамыр';
                            break;
                    case 6:
                            $month = 'Маусым';
                            break;
                    case 7:
                            $month = 'Шілде';
                            break;
                    case 8:
                            $month = 'Тамыз';
                            break;
                    case 9:
                            $month = 'Қырқүйек';
                            break;
                    case 10:
                            $month = 'Қазан';
                            break;
                    case 11:
                            $month = 'Қараша';
                            break;
                    case 12:
                            $month = 'Желтоқсан';
                            break;
            }
            return "{$day} {$month} {$year}";
    }

    protected function getMonth($month)
    {
            switch ($month) {
                case 1:
                        $month = 'Қаңтар';
                        break;
                case 2:
                        $month = 'Ақпан';
                        break;
                case 3:
                        $month = 'Наурыз';
                        break;
                case 4:
                        $month = 'Сәуір';
                        break;
                case 5:
                        $month = 'Мамыр';
                        break;
                case 6:
                        $month = 'Маусым';
                        break;
                case 7:
                        $month = 'Шілде';
                        break;
                case 8:
                        $month = 'Тамыз';
                        break;
                case 9:
                        $month = 'Қырқүйек';
                        break;
                case 10:
                        $month = 'Қазан';
                        break;
                case 11:
                        $month = 'Қараша';
                        break;
                case 12:
                        $month = 'Желтоқсан';
                        break;
            }
            return $month;
    }
    


    protected function setText($pic, $text, $f_size, $f_family, $width, $center, $margin, $h_info, $c_black, $margin_left = 0){
        $px = 3/4;
        $text_b = explode(' ', $text);
        $text_new = '';
        $test = array();
        $i = 0;
        $kol = 1;
        $q = [0];
        foreach ($text_b as $word) {
            $btext = imagettfbbox($f_size, 0, $f_family, $text_new.' '.$word);
            if($btext[2] > $width){
                if($i <= 5){
                    $test[$i] = $text_new;
                    if($i != 0){
                        $q[$i] = str_replace($test[$i-1],'',$test[$i]);
                    }
                    else{
                        $q[$i] = $test[$i];
                    }
                    $i++;
                } else {
                    $text_new = $text_new.'...';
                    break;
                }
                $text_new = $text_new."\n".$word;
                $kol++;
            }
            else {
                $text_new .= " ".$word;
            }
        }
        if($i != 0){
            $q[$i] = str_replace($test[$i-1],'',$text_new);
        }
        $text_new = trim($text_new);
        $leftt = array();
        for($k = 0;$k<=$i; $k++){
            if(!$q[0]){
                $box1 = imagettfbbox($f_size, 0, $f_family, $text_new);
                if(!$margin_left) $leftt[$k] = $center-round(($box1[2]-$box1[0])/2);
                else $leftt[$k] = $margin_left;
                $h_info+=$margin;
                ImageTTFtext($pic, $f_size, 0, $leftt[$k], $h_info, $c_black, $f_family, $text_new);
                break;
            }
            else{
                $q[$k]=trim($q[$k]);
                $box1 = imagettfbbox($f_size, 0, $f_family, $q[$k]);
                if(!$margin_left) $leftt[$k] = $center-round(($box1[2]-$box1[0])/2);
                else $leftt[$k] = $margin_left;
                $h_info+=$margin;
                ImageTTFtext($pic, $f_size, 0, $leftt[$k], $h_info, $c_black, $f_family, $q[$k]);
            }
        }
        return $h_info;
    }
}
