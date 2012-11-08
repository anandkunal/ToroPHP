<?php
class DashboardHandler {
    function get() {
        $stats = get_stats();
        include("views/dashboard.php");
    }
}