<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{

    protected $table      = 'user';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['first_name','last_name','username','email','phone','mobile','address','password','confirm_password','profile_image','profile_image_path','thumbnail_path','icon_path','login_oauth_uid'];

    public function getList(){
        return $this->orderBy('user_id', 'ASC')->findAll();
    }
}