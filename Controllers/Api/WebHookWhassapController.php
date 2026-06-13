<?php

class WebHookWhassapController
{
    public function VerifyWebHook()
    {
        try {
            $token_WebHook = defined('WHATSAPP_WEBHOOK_TOKEN') ? WHATSAPP_WEBHOOK_TOKEN : ''; // [SEGURIDAD C5]
            $json = file_get_contents('php://input');
            // $data = json_decode($json);
            echo ($json);
        } catch (Exception $e) {
            echo $e->getMessage();
            http_response_code(404);
        }
    }
}
