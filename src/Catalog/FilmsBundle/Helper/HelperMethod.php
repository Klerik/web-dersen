<?php
namespace Catalog\FilmsBundle\Helper;

class HelperMethod
{
    const MB100 = 104857600;
    const MB5 = 5242880;

    private $pathUploadFile = 'uploads/';

    public function setPathUploadFile($pathUploadFile)
    {
        $this->pathUploadFile = $pathUploadFile;
    }

    public static function ukr_rusToTranslit($text)
    {
        $trans_arr = array (
            "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
            "е"=>"e", "ё"=>"yo","ж"=>"j","з"=>"z","и"=>"y",
            "й"=>"i","к"=>"k","л"=>"l", "м"=>"m","н"=>"n",
            "о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t",
            "у"=>"y","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch",
            "ш"=>"sh","щ"=>"sh","ы"=>"i","э"=>"e","ю"=>"u",
            "я"=>"ya",
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
            "Е"=>"E","Ё"=>"Yo","Ж"=>"J","З"=>"Z","И"=>"Y",
            "Й"=>"I","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"Y","Ф"=>"F","Х"=>"H","Ц"=>"C","Ч"=>"Ch",
            "Ш"=>"Sh","Щ"=>"Sh","Ы"=>"I","Э"=>"E","Ю"=>"U",
            "Я"=>"Ya",
            "ь"=>"","Ь"=>"","ъ"=>"","Ъ"=>"",
            "ї"=>"i","і"=>"i","ґ"=>"g","є"=>"ye",
            "Ї"=>"I","І"=>"I","Ґ"=>"G","Є"=>"YE",
            ' '=>'_', '—'=>'-', '+'=>'_'
        );
        return strtr($text, $trans_arr);
    }

    function validFile($file)
    {
        $err_SizeLimit = array(0, 'You select a file that exceeds the size limit.');
        switch ($file->getMimeType()) {
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/x-png':
            case 'image/png': {
                if($file->getClientSize() > $this::MB5)
                    return $err_SizeLimit;

                return array(1,'image');
            } break;
            case 'video/mp4':
            case 'video/ogg':
            case 'video/webm': {
                if($file->getClientSize() > $this::MB100)
                    return $err_SizeLimit;

                return array(1, $file->getMimeType());
            } break;
            default:
                return array(0, 'You have selected invalid file format.');
        }

        return array(0, 'Error');
    }

    public function uploadFile($file)
    {
        $result = $this->validFile($file);
        if(!$result[0]) return $result[1];
        $type = $result[1];

        $path =  $this->pathUploadFile;
        $name = time().'_'.$this::ukr_rusToTranslit($file->getClientOriginalName());
        $path_name = $path.$name;

        if($file->move($path, $name))
            return array('type' => $type, 'url' => $path_name);
        else
           return false;
    }
}
