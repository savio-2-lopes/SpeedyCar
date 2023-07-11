let data_response;

$("#searchData").keyup(function () {
  text = $(this).val();
  let id;

  if (text.length > 3) {
    $(".list-group").html("");

    $.ajax({
      url: "/Controllers/VehicleController.php?q=" + text,
      type: "GET",
      async: false,
      dataType: "json",
      data: { q: text },
      success: function (res) {
        data_response = res.data;
        $.each(data_response, function (i, val) {
          if (!id) {
            id = val.id;
          }
          $(".list-group").append(
            '<li id="item_' +
              val.id +
              '" class="list-group-item d-flex justify-content-between align-items-center list-vehicle mb-4" onclick="getDetailsData(' +
              val.id +
              ')">' +
              '<div class="vehicle-list-item">' +
              '<span class="text-uppercase" id="marca">' +
              val.marca +
              "</span>" +
              '<span class="text-green" id="veiculo">' +
              val.veiculo +
              "</span>" +
              '<span id="ano">' +
              val.ano +
              "</span>" +
              '<input type="hidden" id="descricao" value="' +
              val.descricao +
              '" />' +
              '<input type="hidden" id="vendido" value="' +
              val.vendido +
              '" />' +
              "</div>" +
              "<span>" +
              '<i class="fa fa-tag fa-lg" aria-hidden="true"></i>' +
              "</span>" +
              "</li>"
          );
        });
        getDetailsData(id);
      },
      error: function (err) {
        $(".list-group").append(
          '<li class="list-group-item d-flex justify-content-between align-items-center list-vehicle mb-4"><span class="text-center">Sem dados</span></li>'
        );
      },
    });
  }
});

function getDetailsData(id) {
  $("#item_" + id + " span, #item_" + id + " input").each(function () {
    let valueData = $(this).attr("id");
    if ($(this).attr("type") == "hidden") {
      if (valueData == "descricao") {
        $("#detail_" + valueData).html($(this).val());
      } else {
        $("#detail_" + valueData).val($(this).val());
      }
    } else {
      $("#detail_" + valueData).html($(this).html());
    }

    $("#editButton").attr("onClick", "editView(" + id + ")");
    $("#deleteButton").attr("onClick", "deleteData(" + id + ")");
  });
}

function editView(id) {
  $(".modal-title").html("Editar Veículo");
  $(".btn-add").html("UPDATE");
  $("#brandVehicle").val($("#detail_marca").html());
  $("#modelVehicle").val($("#detail_veiculo").html());
  $("#yearVehicle").val($("#detail_ano").html());
  $("#descriptionVehicle").val($("#detail_descricao").html());
  $("#idVehicle").val(id);
  if ($("#saleDetailVehicle").val() == 1) {
    $("#saleVehicle").attr("checked", "checked");
  }
}

function registerView() {
  $(".modal-title").html("Novo Veículo");
  $(".btn-add").html("ADD");

  $("#modelVehicle").val("");
  $("#brandVehicle").val("");
  $("#yearVehicle").val("");
  $("#descriptionVehicle").val("");
  $("#idVehicle").val("");

  $("#vendido").removeAttr("checked");
}

function deleteData(id) {
  Swal.fire({
    title: "Deseja realmente excluir esse veículo?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#364147",
    cancelButtonColor: "#364147",
    confirmButtonText: "Sim!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/Controllers/VehicleController.php?id=" + id,
        type: "DELETE",
        async: false,
        dataType: "json",
        success: function (res) {
          window.location.href = "/Views/vehicle.php";
        },
        error: function (err) {
          window.location.href = "/Views/vehicle.php";
        },
      });
    }
  });
}

function saveData() {
  let err = false;

  $("#form input, #form select, #form textarea").each(function () {
    if ($(this).val() === "" || $(this).val() === undefined) {
      if ($(this).attr("id") !== "idVehicle") {
        err = true;
        console.log(err)
        return false;
      }
    }
  });


  if (!err) {
    $("#form").submit();
  }
}
