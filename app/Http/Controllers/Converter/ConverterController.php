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
        //dd($request);
       // dd(Input::all());
      //  $validate =['file' => 'mimes: application/vnd.ms-excel, text/xml, application/octet-stream'];
   //     $validate =['file' => 'mimes:csv',];
  //      $this->validate($request, $validate);


        $contents1 = File::get($request->file('file'));

        $row = 0;
        while (($data = fgetcsv($contents1, 1000, ",")) !== FALSE) {

            // edit for number of horisontal fields
            $data2["person1"][$row] = $data[0];
            $data2["person2"][$row] = $data[1];
            $data2["person3"][$row] = $data[2];

            $row=$row+1;
        }
 



        // $xml = simplexml_load_string($contents1);
      //  $json = json_encode($xml);


        //    $firstLine = explode ("\r\n",$contents1);


       // json_encode($contents1);
      //  dd($json);
        //$path = $request->file('avatar')->store('avatars');

       // return $path;
        //$newFile = Storage::putFileAs('photos', new File('/path/to/photo'), 'photo.jpg');
        //dd($path);

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
