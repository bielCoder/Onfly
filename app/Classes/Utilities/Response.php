<?php

namespace App\Classes\Utilities;


class Response
{
   public function format($object, $type, $method, $data, $token , $message, $status)
   {

      if($message == null)
      {
         return response() -> json([$object => [
            "header" => [
               "content-type" => $type,
               "method" => $method,
            ],
            "data" => $data,
            "status" => $status
         ]]) -> setStatusCode($status);;
      }

      if($data == null)
      {
         return response() -> json([$object => [
            "header" => [
               "content-type" => $type,
               "method" => $method
            ],
            "message" => $message,
            "status" => $status
         ]]) -> setStatusCode($status);;
      }

      if($token !== null)
      {
         return response() -> json([$object => [
            "header" => [
               "content-type" => $type,
               "method" => $method
            ],
            "data" => $data,
            "token" => $token,
            "message" => $message,
            "status" => $status
         ]]) -> setStatusCode($status);;
      }


      return response() -> json([$object => [
         "header" => [
            "content-type" => $type,
            "method" => $method
         ],
         "data" => $data,
         "message" => $message,
         "status" => $status
      ]]) -> setStatusCode($status);;
   }



   public function error($object,$type,$method,$message,$status)
   {
      return response() -> json([$object => [
         "header" => [
            "content-type" => $type,
            "method" => $method
         ],
         "message" => $message,
         "status" => $status
         ]]) -> setStatusCode($status);
   }
}
