<?php

class WebHookWhassapController
{
    public function VerifyWebHook()
    {
        try {
            $token_WebHook="PRUEBAWEBHOOK2023WHASSAPBUSNESS!";
            $json = file_get_contents('php://input');
            // $data = json_decode($json);
            echo ($json);
        } catch (Exception $e) {
            echo $e->getMessage();
            http_response_code(404);
        }
    }
}
