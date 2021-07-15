<?php
namespace app\components;

use Yii;
use yii\base\Event;

class ErrorResponseHelper
{
    public static function beforeResponseSend(Event $event)
    {
        $except = ["site/get-kota","site/get-kecamatan","site/get-desa",];
        $response = $event->sender;
        $request = Yii::$app->request;

        if (in_array($request->pathInfo, $except)):
            return $event;
        endif;

        if($response->statusCode==500 && Yii::$app->params['debug']){
            dd($response->data);
        }

        $url = str_replace($request->getBaseUrl(), "", $request->getUrl());

        $content_type = strtolower(Yii::$app->request->headers->get("content-type"));

        if ($response->format == "json" || $content_type == "application/json" || is_int(strpos($url, "/api/"))):
            $response->format = "json";

            if ($response->statusCode != 200 && $response->statusText != "OK"):
                if (is_array($response->data)):
                    if ($response->data['message']):
                        $message = $response->data['message'];
                    else:
                        $message = $response->statusText;
                    endif;
                else:
                    $message = $response->statusText;
                endif;

                $response->data = [
                    'success' => false,
                    "message" => $message,
                    'code' => $response->statusCode,
                ];
            else:
                if (is_array($response->data)):
                    self::handleResponseArray($response);
                else:
                    self::handleResponseObject($response);
                endif;
            endif;
        endif;

        return $event;
    }

    /**
     * handle array
     * @param Object $response
     */
    private static function handleResponseArray(&$response)
    {
        if (!isset($response->data['success']) && !(isset($response->data['code']) || isset($response->data['status']))):
            $message = self::getMessage($response);

            $response->data = [
                'success' => true,
                "message" => $message,
                "data" => $response->data,
                'code' => 200,
            ];
        elseif ($response->data['code'] == null || $response->data['status'] == null || $response->data['code'] == 0):
            self::removeKeys($response, 'array');
            $message = self::getMessage($response);

            $response->data["success"] = $response->data["success"] ?? true;
            $response->data["message"] = $message;
            $response->data['data'] = $response->data['data'] ?? [];
            $response->data["code"] = 200;
        endif;
    }

    /**
     * handle object
     * @param Object $response
     */
    private static function handleResponseObject(&$response)
    {
        $res = $response->data;
        if (!isset($res->success) && !(isset($res->code) || isset($res->status))):
            $code = $res->code ?? $res->status;
            $message = self::getMessage($response, 'object');

            $response->data = [
                'success' => true,
                "message" => $message,
                "data" => $response->data,
                'code' => $code ?? 200,
            ];
        elseif ($res->code == null || $res->status == null || $res->code == 0):
            self::removeKeys($response, 'object');
            $message = self::getMessage($response, 'object');
            
            $res->success = $res->success ?? true;
            $res->message = $message;
            $res->data = $res->data ?? [];
            $res->code = 200;
        endif;
    }

    /**
     * Gettig message
     * @param \yii\web\Response $response
     * @param string $type
     * 
     * @return string
     */
    private static function getMessage($response, $type = "array"){
        $message = $response->statusText;
        if(strtolower($type) == 'array'):
            if (isset($response->data['message'])):
                $message = $response->data['message'];
                unset($response->data['message']);
            endif;
        else:
            $res = $response->data;
            if (isset($res->message)):
                $message = $res->message;
                unset($res->message);
            endif;
        endif;

        return $message;
    }

    protected static function removeKeys(&$response, $key = "object")
    {
        $except = ["success", "data", "message", "code", 'token'];

        if (strtolower($key) == "object"):
            $keys = array_keys((array) $response->data);
            foreach ($keys as $key):
                if (in_array($key, $except) == false):
                    unset($response->data->$key);
                endif;
            endforeach;
        else:
            $keys = array_keys($response->data);
            foreach ($keys as $key):
                if (in_array($key, $except) == false):
                    unset($response->data[$key]);
                endif;
            endforeach;
        endif;
    }
}
