<?php

namespace azasar\expo;

use yii\base\Component;

class ExpoPush extends Component
{
    const EXPO_PUSH_ENDPOINT = 'https://exp.host/--/api/v2/push/send';
    private $_ch;

    public function notify($token, $message)
    {
        $postData[] = $message + ['to' => $token];

        $ch = $this->prepareCurl();

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = $this->executeCurl($ch);

        return $response;
    }

    private function prepareCurl()
    {
        $this->_ch = $this->_ch ?? curl_init();

        $ch = $this->_ch;

        // Set opts
        curl_setopt($ch, CURLOPT_URL, self::EXPO_PUSH_ENDPOINT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'content-type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return $ch;
    }

    private function executeCurl($ch)
    {
        $response = [
            'body' => curl_exec($ch),
            'status_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
        ];

        return json_decode($response['body'], true)['data'];
    }

}


?>
