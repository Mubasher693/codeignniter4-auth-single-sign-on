<?php namespace App\Controllers;

use App\Models\UserModel;
use Dompdf\Exception;

/**
 * Class Authorization
 * @package App\Controllers
 */
class Authorization extends BaseController {

    private $session;

    /**
     * Authorization constructor.
     * @param array $array
     */
    public function __construct($array = array()){
        ini_set('display_errors', '1');
        $this->session = session();
    }

    /**
     * @param array $array
     * @return bool|\CodeIgniter\HTTP\RedirectResponse|void
     */
    public function check_session($array = array()){
        if($this->session->get('userData.user_id')){
            return 1;
        }
        return 2;
    }
}