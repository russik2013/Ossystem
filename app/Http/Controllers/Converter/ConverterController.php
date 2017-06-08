<?php

namespace App\Http\Controllers\Converter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class ConverterController extends Controller
{
    public function doConverten(Request $request){


        $contents1 = File::get($request->file('file'));
        $fileName = explode('.',$request->file('file')->getClientOriginalName());
        dd($request->converteType);

        switch ($fileName[1]) {

            case 'json':
                if($request->converteType == 'csv')
                    echo 'Это я ещё не успел сделать ((( ';
                if($request->converteType == 'xml')
                    print_r($this->jsonToXml($contents1));
                    break;
            case 'xml':
                if($request->converteType == 'csv')
                    echo 'Это я ещё не успел сделать ((( ';
                if($request->converteType == 'json')
                    print_r($this->xmlToJson($contents1));
                        break;
            case 'csv':
                if($request->converteType == 'xml')
                    echo 'Это я ещё не успел сделать ((( ';
                if($request->converteType == 'json')
                    $this->csvToJson($contents1);
                break;
            default: echo 'Это я ещё не успел сделать ((( ';

        }

    }

    public function csvToJson($contents1){

        $array = array_map("str_getcsv", explode("\n", $contents1));

        $json = json_encode($array);

        return $json;
    }



    public function jsonToXml($contents1){


        $xml = simplexml_load_string($contents1);
        $json = json_encode($xml);

        return $json;
    }


    public function xmlToJson($contents1){
        $array = json_decode ($contents1, true);
        //dd($array);
        $xml = new SimpleXMLElement('<root/>');
        return $this->arrayToXml($array, $xml);
    }

    function arrayToXml($array, &$xml){
        foreach ($array as $key => $value) {
            if(is_array($value)){
                if(is_int($key)){
                    $key = "e";
                }
                $label = $xml->addChild($key);
                $this->arrayToXml($value, $label);
            }
            else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }


}
