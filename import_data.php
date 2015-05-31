<?php

$mysql_config = array(
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'asapp',
    'database' => 'messages',
);

// Create connection
$conn = new mysqli($mysql_config['server'], $mysql_config['username'], $mysql_config['password'], $mysql_config['database']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('sample_conversations.json'));

$sql = "DELETE FROM messages;";
$conn->query($sql);

foreach($data->Issues as $issue)
{
    $IssueId = $issue->IssueId;
    $CompanyGroupId = $issue->CompanyGroupId;

    foreach($issue->Messages as $MessageNum => $message)
    {
        $sql = "INSERT INTO messages (IssueId, CompanyGroupId, MessageNum, IsFromCustomer, Text)
        VALUES ($IssueId, $CompanyGroupId, $MessageNum, {$message->IsFromCustomer}, '".$conn->escape_string($message->Text)."');";
        $conn->query($sql);
    }
}

