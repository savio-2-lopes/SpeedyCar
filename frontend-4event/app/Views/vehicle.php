<?php require_once('../Controllers/VehicleController.php');   ?>
<?php require_once('../template/header.php'); ?>

<div class="container mt-34">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between">
                <h4>VEÍCULO</h4>
                <button type="button" class="btn d-flex justify-content-center align-items-center btn-sm btn-dark rounded-circle" data-bs-toggle="modal" data-bs-target="#form_modal" onclick="registerView()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 3a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0v-8A.5.5 0 0 1 8 3zm4.5 4.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1 0-1h8a.5.5 0 0 1 .5.5z" />
                    </svg>
                </button>
            </div>
            <hr />
        </div>

        <div class="col-lg-6 mt-4">
            <h6>Lista de veículos</h6>
            <ul class="list-group mt-3 shadow-none">
                <?php
                if (!$veiculos || !$veiculos->view()) {
                    echo '
                    <li class="list-group-item d-flex justify-content-center align-items-center">
                        <span class="text-center">Sem Dados</dados>
                    </li>';
                } else {
                    foreach ($veiculos->view() as $veiculo) {
                        echo '
                        <li id="item_' . $veiculo["id"] . '" class="list-group-item d-flex justify-content-between align-items-center list-vehicle mb-4" onclick="getDetailsData(' . $veiculo["id"] . ')">
                            <div class="vehicle-list-item">
                                <span class="text-uppercase" id="marca">' . $veiculo["marca"] . '</span>
                                <span class="text-green" id="veiculo">' . $veiculo["veiculo"] . '</span>
                                <span id="ano">' . $veiculo["ano"] . '</span>
                                <input type="hidden" id="descricao" value="' . $veiculo["descricao"] . '" />
                                <input type="hidden" id="vendido" value="' . $veiculo["vendido"] . '" />
                            </div>
                            <span>
                                <i class="fa fa-tag fa-lg" aria-hidden="true"></i>
                            </span>
                        </li>';
                    }
                }
                ?>
            </ul>
        </div>

        <div class="col-lg-6 mt-4">
            <h6>Detalhes</h6>
            <div class="card bg-light border-0 rounded-0 mt-3 mb-3" style="max-width: 100rem;">
                <div id="detalhes" class="card-body row">
                    <?php
                    if (!$veiculos || !$veiculos->view()) {
                    ?>
                        <div class="col-lg-12">
                            <div class="text-center text-center form-label mt-4">Sem dados</div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col-lg-12">
                            <h5 id="detail_veiculo" class="text-green"><?php echo $veiculos->view()[0]['veiculo'] ?></h5>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-label mt-4">Marca:</div>
                            <span id="detail_marca" class="card-title text-uppercase text-muted"><?php echo $veiculos->view()[0]['marca'] ?></span>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-label mt-4">Ano:</div>
                            <span id="detail_ano" class="card-title text-muted"><?php echo $veiculos->view()[0]['ano'] ?></span>
                        </div>

                        <div class="col-lg-12 mt-5">
                            <p id="detail_descricao" class="card-text"><?php echo $veiculos->view()[0]['descricao'] ?></p>
                        </div>
                    <?php
                    } ?>
                </div>

                <?php
                if ($veiculos && $veiculos->view()) {
                ?>
                    <div id="card-footer" class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <input type="hidden" id="saleDetailVehicle" name="vendido" value="" />
                            <button type="button" class="btn btn-dark btn-lg rounded-0" data-bs-toggle="modal" data-bs-target="#form_modal" id="editButton" onclick="editView(<?php echo $veiculos->view()[0]['id'] ?>)">
                                <i class="me-2 fa fa-pencil" aria-hidden="true"></i>
                                Editar
                            </button>
                            <button type="button" id="deleteButton" class="btn btn-dark btn-lg rounded-0" onclick="deleteData(<?php echo $veiculos->view()[0]['id'] ?>)">
                                <i class="me-2 fa fa-trash" aria-hidden="true"></i>
                                Excluir
                            </button>
                        </div>
                        <span>
                            <i class="fa fa-tag fa-lg" aria-hidden="true"></i>
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal modal-xl fade" id="form_modal" tabindex="-1" aria-labelledby="form_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-2 shadow-none border-0 rounded-0">
            <div class="modal-header">
                <h2 class="modal-title">VEÍCULO</h2>
            </div>

            <div class="modal-body">
                <form id="form" method="post" action="/Controllers/VehicleController.php">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="modelVehicle" class="form-label mt-4">Veículo</label>
                                <input name="veiculo" id="modelVehicle" value="" class="form-control">
                            </div>
                        </div>

                        <input type="hidden" name="id" id="idVehicle" value="">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="brandVehicle" class="form-label mt-4">Marca</label>
                                <input name="marca" id="brandVehicle" value="" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="yearVehicle" class="form-label mt-4">Ano</label>
                                    <input name="ano" id="yearVehicle" value="" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="saleVehicle" class="form-label mt-4">Vendido</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="1" id="saleVehicle" name="vendido">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="descriptionVehicle" class="form-label mt-4">Descrição</label>
                                <textarea class="form-control" id="descriptionVehicle" name="descricao" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer mt-4">
                <button type="button" class="btn btn-dark rounded-0 border-0 btn-lg btn-add" onclick="saveData()">ADD</button>
                <button type="button" class="btn btn-dark rounded-0 border-0 btn-lg" data-bs-dismiss="modal">FECHAR</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
<script src="../assets/js/scripts.js"></script>