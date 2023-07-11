<?php

require '../Service/VehicleService.php';

use Service\VehicleService;

class VehicleController
{
    public $method;
    public $arr;

    function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->action();
    }

    function action()
    {
        $vehicleService = new VehicleService();
        if ($this->method === "POST") {
            if (!isset($_POST['id']) || $_POST['id'] === "") {
                unset($_POST['id']);
                if (!isset($_POST['vendido'])) {
                    $_POST['vendido'] = "0";
                }
                $res = $vehicleService->post($_POST);
                if ($res) {
                    header("Location: /Views/vehicle.php");
                } else {
                    header("Location: /Views/vehicle.php");
                }
            } else if (isset($_POST['id']) && $_POST['id'] !== "" && is_numeric($_POST['id'])) {  // Corrigido para $_POST['id']
                if (!isset($_POST['vendido'])) {
                    $_POST['vendido'] = "0";
                }
                $res = $vehicleService->put($_POST);
                if ($res) {
                    header("Location: /Views/vehicle.php");
                } else {
                    header("Location: /Views/vehicle.php");
                }
            }
        }

        if ($this->method === "DELETE") {
            if (isset($_GET['id']) && $_GET['id'] !== "" && is_numeric($_GET['id'])) {
                $res = $vehicleService->delete($_GET['id']);
                if ($res) {
                    header("Location: /Views/vehicle.php");
                } else {
                    header("Location: /Views/vehicle.php");
                }
            }
        }

        if ($this->method === "GET" && isset($_GET['q'])) {
            $this->arr = $vehicleService->getFind($_GET['q']);
        } else {
            $this->arr = $vehicleService->getVehicle();
        }
    }

    public function view()
    {
        return isset($this->arr['data']) ? $this->arr['data'] : null;
    }
}

$veiculos = new VehicleController();
