<?php
function get_queue_size() {
    $query = MySQL::getInstance()->query("SELECT count(id) as count FROM queue");
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result['count'];
}

function get_operation_counts() {
    $query = MySQL::getInstance()->query("SELECT type, count FROM operations WHERE type='send' or type='receive' ORDER BY type ASC");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function get_stats() {
    $stats = array('size' => 0, 'sends' => 0, 'receives' => 0);
    $stats['size'] = get_queue_size();
    $operation_counts = get_operation_counts();
    $stats['receives'] = $operation_counts[0]['count'];
    $stats['sends'] = $operation_counts[1]['count'];
    return $stats;
}

function log_operation($operation_type) {
    $query = MySQL::getInstance()->prepare("UPDATE operations SET count=count+1 WHERE type = :operation_type");
    $query->bindValue(':operation_type', $operation_type, PDO::PARAM_STR);
    $query->execute();
}

function receive_payload() {
    $query = MySQL::getInstance()->query("SELECT id, payload FROM queue ORDER BY id ASC LIMIT 1");
    $queued_payload = $query->fetch(PDO::FETCH_ASSOC);
    if ($queued_payload) {
        $query = MySQL::getInstance()->prepare("DELETE FROM queue WHERE id = :id");
        $query->bindValue(':id', $queued_payload['id'], PDO::PARAM_INT);
        $query->execute();
        log_operation('receive');
        return $queued_payload['payload'];
    }
    else {
        return null;
    }
}

function send_payload($payload) {
    $query = MySQL::getInstance()->prepare("INSERT INTO queue (payload) VALUES (:payload)");
    $query->bindValue(':payload', $payload, PDO::PARAM_STR);
    $query->execute();
    log_operation('send');
}