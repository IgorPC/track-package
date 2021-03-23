<?php


namespace IgorPC\TrackPackage\Helpers;


class Validator
{
    public static function required(array $fields, array $data)
    {
        $valid = false;
        foreach ($fields as $field)
        {
            if(!array_key_exists($field, $data))
            {
                $valid[] = $field." field is required";
            }
        }
        if($valid == false){
            return $valid;
        }else{
            return send(["error" => $valid], 403);
        }
    }

    public static function refused(array $fields, array $data)
    {
        $valid = false;
        foreach ($fields as $field)
        {
            if(array_key_exists($field, $data))
            {
                $valid = $field;
            }
        }
        if($valid){
            return send(['error' => $field. " field is invalid"], 403);
        }else{
            return $valid;
        }
    }
}