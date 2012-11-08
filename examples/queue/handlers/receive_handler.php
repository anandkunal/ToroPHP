<?php
class ReceiveHandler {
    function get_xhr() {
        echo json_encode(array("payload" => receive_payload()));
    }
}