<?php
    class Response {
        public function response($data, $status = 200) {
            header("Content-Type: application/json");
            header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
            echo json_encode($data);
        }
    
        private function _requestStatus($code) {
            $status = [
                200 => "OK",
                201 => "Created",
                204 => "No Content",
                400 => "Bad Request",
                404 => "Not Found",
                500 => "Internal Server Error",
            ];
            return $status[$code] ?? $status[500];
        }
    }
    