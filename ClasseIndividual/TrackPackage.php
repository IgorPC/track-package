<?php

namespace IgorPC\TrackPackage;

class TrackPackage
{
    private array $valid = [
        'A', 'B', 'C', 'D' , 'E', 'F', 'G', 'H', 'I', 'J', 'K',
        'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
        'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g',
        'h' , 'i' , 'j' ,'k' , 'l' ,'m' , 'n', 'o', 'p', 'q',
        'r', 's', 't', 'u', 'v', 'w', 'x' , 'y', 'z', '1', '2',
        '3', '4', '5', '6', '7', '8', '9', '0', ':', '/'
    ];

    private array $vowels = [
        'accents' => [
            'á', 'Á', 'â', 'Â', 'ã', 'Ã', 'à', 'Á',
            'é', 'É', 'ê', 'Ê', 'è', 'È',
            'í', 'Í', 'î', 'Î', 'ì', 'Ì',
            'ó', 'Ó', 'ô', 'Ô', 'õ', 'Õ', 'ò', 'Ò',
            'ú', 'Ú', 'û', 'Û', 'ù', 'Ù'
        ],
        'valid' => [
            'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A',
            'e', 'E', 'e', 'E', 'e', 'E',
            'i', 'I', 'i', 'I', 'i', 'I',
            'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O',
            'u', 'U', 'u', 'U', 'u', 'U'
        ]
    ];

    public function trackPackage($trackingCode)
    {
        $html = $this->connectToWs("https://www2.correios.com.br/sistemas/rastreamento/resultado_semcontent.cfm", $trackingCode);
        if($this->verifyError($html)) return false;
        $elements = $this->getElementsFromDOM($html);
        unset($html);
        $response = $this->removeEmptySpaces($elements);
        unset($elements);
        $tracked = $this->getResponse($response);
        unset($response);
        return $tracked;
    }

    private function getResponse($response)
    {
        foreach ($response as $key => $resp){
            $replaced = str_replace(',', ' ', $resp);
            $response[$key] = $replaced;
        }

        $resp = [];

        $count = count($response);

        for ($i = 0; $i < $count; $i+=2){
            array_push($resp, [
                'location' => $response[$i],
                'msg' => $response[$i+1]
            ]) ;
        }
        unset($count);
        unset($response);
        return $resp;
    }

    private function removeEmptySpaces($elements)
    {
        foreach ($elements as $key => $resp){
            $string = '';
            $resp = str_replace($this->vowels['accents'], $this->vowels['valid'], $resp);
            $len = strlen($resp);
            for ($i = 0; $i < $len; $i++){
                if(in_array($resp[$i], $this->valid)){
                    if(!in_array($resp[$i+1],  $this->valid)){
                        $string .= $resp[$i].',';
                    }else{
                        $string .= $resp[$i];
                    }
                }
            }
            $response[$key] = str_replace(',', ' ', $string);
        }
        return $response;
    }

    private function connectToWs($url, $trackingCode)
    {
        $post = array('Objetos' => $trackingCode);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($post));
        unset($post);
        $output = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $html = utf8_encode($output);
        unset($output);

        return $html;
    }

    private function verifyError($html)
    {
        $links = $this->startDOM($html, 'h4');
        $response = [];
        foreach ($links as $key => $link)
        {
            $response[$key] = utf8_decode($link->textContent);
        }
        unset($links);

        if($response) return true;

        return false;
    }

    private function startDOM($html, $element)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $links = $dom->getElementsByTagName($element);
        unset($dom);
        return $links;
    }

    private function getElementsFromDOM($html)
    {
        $links = $this->startDOM($html, 'td');
        $response = [];
        foreach ($links as $key => $link)
        {
            $response[$key] = utf8_decode($link->textContent);
        }
        unset($links);
        return $response;
    }
}