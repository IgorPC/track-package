<?php


namespace IgorPC\TrackPackage\Helpers;


class Http
{
    private function getHttpReturn($url, $context)
    {
        $response = file_get_contents($url, false, $context);
        $headers = explode(" ", $http_response_header[0]);
        if($headers[1] != "200"){
            $return = [
                'StatusCode' => $headers[1],
                'Content' => $response
            ];
        }else{
            $return = $response;
        }
        unset($response);
        return $return;
    }

    public static function send(String $method, String $url, array $content = null, array $headers = null)
    {
        $allowed_methods = ['GET', 'POST', 'PUT', 'DELETE'];
        $confirm = false;
        foreach ($allowed_methods as $allowed){
            if($method == $allowed){
                $confirm = true;
            }
        }
        if($confirm != true){
            return false;
        }else{
            unset($confirm);
        }

        if($content == null){
            $content = '';
        }
        if($headers == null){
            $headers = 'Content-Type: application/json';
        }else{
            $headers['Content-Type'] = 'application/json';
            foreach ($headers as $key => $value){
                $flattened[] = $key.': '.$value;
            }
            $headers = implode("\r\n", $flattened);
        }

        $context = stream_context_create([
            'http' => [
                'ignore_errors' => true,
                'method' => $method,
                'header' => $headers,
                'content' => json_encode($content)
            ]
        ]);

        return self::getHttpReturn($url, $context);
    }
}