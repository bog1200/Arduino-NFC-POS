<?php
require("../config.php");
var_dump($_SERVER['REQUEST_METHOD']);
var_dump($_POST);
var_dump($_GET);
// if request is post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "POST ";
    // if post contains mac, ip, and name
    if (isset($_POST['mac']) && isset($_POST['ip']) && isset($_POST['name'])) {
        // if mac is not empty
        if (empty($_POST['mac']) || !filter_var($_POST['mac'], FILTER_VALIDATE_MAC)) {
            exit(610); // Invalid MAC
        }
        echo "MAC ";
        // if ip is valid
        if (empty($_POST['ip']) || !filter_var($_POST['ip'], FILTER_VALIDATE_IP)) {
            exit(611); // Invalid IP
        }
        echo "IP ";
        // if name is valid
        if (!empty($_POST['name']) &&  preg_match('/^[a-zA-Z0-9]+$/', $_POST['name'])) {
            exit(612); // Invalid name
        }

        // if name is not too long or too short
        if (strlen($_POST['name']) < 3 || strlen($_POST['name']) > 20) {
            exit(613); // Invalid name
        }
        echo "NAME ";
        // if mac already exists
        $mac = R::findOne('devices', 'mac=?', [$_POST['mac']]);
        if ($mac) {
            // update ip and name
            $mac->name = $_POST['name'];
            $mac->ip = $_POST['ip'];
            $id = R::store($mac);
            echo "<h1>Updated device with id: $id</h1>";
        } else {
            // create new device
            $device = R::dispense('devices');
            $device->name = $_POST['name'];
            $device->ip = $_POST['ip'];
            $device->name = $_POST['name'];
            $device->mac = $_POST['mac'];
            $id = R::store($device);
            echo "<h1>Created device with id: $id<h1>";
        }
    }
}
// if request is get
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // if get contains mac
    if (isset($_GET['mac'])) {
        // if mac is not empty
        if (!empty($_GET['mac']) || !filter_var($_GET['mac'], FILTER_VALIDATE_MAC)) {
            // exit("Invalid MAC");
            echo "Invalid MAC";
        }
        // if mac exists
        $mac = R::findOne('devices', 'mac=?', [$_GET['mac']]);
        if ($mac) {
            // return ip and name
            echo $mac->ip . " " . $mac->name;
        }
    }
}
