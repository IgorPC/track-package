<?php


namespace IgorPC\TrackPackage\Middleware;

class AuthConfig
{
    private String $key = "";
    private $token_exp = "+30 minutes";
    private string $alg = "HS256";
    private String $typ = "JWT";
    private String $delimiter = 'Yhs';

    public static function auth(int $id)
    {
        return (new AuthConfig())->generateToken($id);
    }

    private function generateToken(int $id)
    {
       $header = [
           "alg" => $this->alg,
           "typ" => $this->typ
       ];

       $header = json_encode($header);
       $header = base64_encode($header);

       $now = date('Y-m-d H:i:s');

       $payload = [
            'uid' => $id,
            'exp' => date('Y-m-d H:i:s', strtotime($now . $this->token_exp))
       ];

       $payload = str_replace('=', $this->delimiter, $payload);

       $payload = json_encode($payload);
       $payload = base64_encode($payload);

       $signature = hash_hmac('sha256', $header.'.'.$payload, $this->key, true);
       $signature = base64_encode($signature);
       $signature = str_replace(['+', '/', '='], ['-', '_', ''], $signature);

       $token = $header.'.'.$payload.'.'.$signature;
       return $token;
    }

    public function validateToken($token)
    {
        return $this->validate($token);
    }

    private function validate($token)
    {
        $parts = explode('.', $token);
        if(count($parts) != 3) return send(["error" => "invalid token"], 403);

        $header = $parts[0];
        $payload = $parts[1];
        $signature = $parts[2];

        if(!$this->verifySignature($header, $payload, $signature)) return send(["error" => "invalid token"], 403);
        unset($parts);

        $header = base64_decode($header);
        $header = json_decode($header);

        if(!property_exists($header, 'alg') || !property_exists($header, 'typ')) return send(["error" => "invalid token"], 403);

        $payload = base64_decode($payload);
        $payload = json_decode($payload);

        if(!property_exists($payload, 'uid') || !property_exists($payload, 'exp')) return send(["error" => "invalid token"], 403);

        $now = date('Y-m-d H:i:s');

        if(strtotime($now) > strtotime($payload->exp)){
            return send(["error" => "expired token"], 403);
        }

        return false;
    }

    private function verifySignature($header, $payload, $signature)
    {
        $valid = hash_hmac('sha256', $header.'.'.$payload, $this->key, true);
        $valid = base64_encode($valid);
        $valid = str_replace(['+', '/', '='], ['-', '_', ''], $valid);

        if($signature != $valid){
            return false;
        }else{
            return true;
        }
    }
}