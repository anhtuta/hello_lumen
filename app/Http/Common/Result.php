<?php

namespace App\Http\Common;

use Illuminate\Contracts\Support\Arrayable;

class Result implements Arrayable {
    private $message;
    private $data;
    private $meta;

    public function res($message, $data = null, $meta = null) {
        $this->message = $message;
        $this->data = $data;
        $this->meta = $meta;
        return $this;
    }

    public function successRes($data, $meta = null) {
        $this->message = "SUCCESS";
        $this->data = $data;
        $this->meta = $meta;
        return $this;
    }

    public function failRes($data = null) {
        $this->message = "FAIL";
        $this->data = $data;
        return $this;
    }

    /***** Getters, setters *****/
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

    public function toArray() {
        return array(
            "message" => $this->message,
            "data" => $this->data,
            "meta" => $this->meta,
        );
    }
}