<?php

namespace App\Http\Common;

use Illuminate\Contracts\Support\Arrayable;

class Result implements Arrayable {
    private $code;
    private $message;
    private $data;
    private $meta;

    public function successRes($data, $meta = null) {
        $this->code = 200000;
        $this->message = "SUCCESS";
        $this->data = $data;
        $this->meta = $meta;
        return $this;
    }

    public function failRes($data = null) {
        $this->code = 400000;
        $this->message = "FAIL";
        $this->data = $data;
        return $this;
    }

    public function res($code, $message, $data = null) {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        return $this;
    }

    /***** Getters, setters *****/
    public function getCode() {
        return $this->code;
    }
    public function setCode($code) {
        $this->code = $code;
    }
    public function getMessage() {
        return $this->message;
    }
    public function setMessage($message) {
        $this->message = $message;
    }
    public function getData() {
        return $this->data;
    }
    public function setData($data) {
        $this->data = $data;
    }
    public function getMeta() {
        return $this->meta;
    }
    public function setMeta($meta) {
        $this->meta = $meta;
    }
    public function getStatus() {
        if(is_int($this->code)) {
            return $this->code / 1000;
        }
        return 200;
    }

    public function toArray() {
        return array(
            "code" => $this->code,
            "message" => $this->message,
            "data" => $this->data,
            "meta" => $this->meta,
        );
    }
}