<?php

function validateData($data)
{
  print_r($data["marca"]);

  if (empty($data['marca']) || empty($data['veiculo'])) {
    return false;
  }

  return true;
}
