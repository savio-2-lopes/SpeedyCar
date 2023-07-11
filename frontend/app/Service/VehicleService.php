<?php

namespace Service;

class VehicleService
{
    private static $url = "http://localhost:8000/veiculos";

    function getVehicle()
    {
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, self::$url);
        curl_setopt($objCurl, CURLOPT_HTTPGET, 1);
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($objCurl), true);
        curl_close($objCurl);
        return $response;
    }

    function getVeiculo($id)
    {
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, self::$url . "/" . $id);
        curl_setopt($objCurl, CURLOPT_HTTPGET, 1);
        $response = curl_exec($objCurl);
        curl_close($objCurl);
        return $response;
    }

    function getFind($param)
    {
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, self::$url . "/find?q=" . $param);
        curl_setopt($objCurl, CURLOPT_HTTPGET, 1);
        $response = curl_exec($objCurl);
        curl_close($objCurl);
        return $response;
    }

    function post($params)
    {
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, self::$url);
        curl_setopt($objCurl, CURLOPT_POST, 1);
        curl_setopt($objCurl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($objCurl), true);
        curl_close($objCurl);
        return $response;
    }

    function put($params)
    {
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, self::$url . "/" . $params["id"]);
        curl_setopt($objCurl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($objCurl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($objCurl), true);
        curl_close($objCurl);
        return $response;
    }
    
    function delete($id)
    {
        $objCurl = curl_init();
        curl_setopt($objCurl, CURLOPT_URL, self::$url . "/" . $id);
        curl_setopt($objCurl, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($objCurl);
        curl_close($objCurl);
        return $response;
    }
}
