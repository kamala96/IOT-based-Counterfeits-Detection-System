<?php
namespace App\Validation;
use App\Models\IntermediaryModel;

class Userrules{

  public function validateUser(string $str, string $fields, array $data){
    $model = new IntermediaryModel();
    $user = $model->where('int_mail', $data['email'])->first();

    if(!$user)
      return false;

    return password_verify($data['password'], $user['int_password']);
  }
}
