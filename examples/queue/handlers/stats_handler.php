<?php
class StatsHandler {
    function get_xhr() {
        echo json_encode(get_stats());
    }
}