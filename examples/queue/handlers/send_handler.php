<?php
class SendHandler {
    function post() {
        if (isset($_POST['payload']) && strlen(trim($_POST['payload'])) > 0) {
            send_payload($_POST['payload']);
        }
        header("Location: /");
    }
}