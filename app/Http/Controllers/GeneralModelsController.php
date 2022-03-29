<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class GeneralModelsController extends Controller
{


    public function getModelAttributes($model_name) {
        $path = app_path() . "/Models";
        $models = GeneralModelsController::getModels($path);
        $models = array_map(function($model) use ($path) {
            return str_replace($path . '/', '', $model);
        }, (array)$models );

        if(! in_array($model_name, $models)) {
            return new Response(['error' => true, 'message' => 'Wrong model name!'], 404);
        }
        $class = "App\Models\\" . $model_name;

        $schema = Schema::getColumnListing((new $class())->getTable());


        return new Response([
            'model' => $model_name,
            'schema' => $schema
        ], 200);
    }

    public static function getModels($path){
        $out = [];
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            $filename = $path . '/' . $result;
            if (is_dir($filename)) {
                $out = array_merge($out, self::getModels($filename));
            }else{
                $out[] = substr($filename,0,-4);
            }
        }
        return $out;
    }
}
