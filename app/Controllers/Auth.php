<?php namespace App\Controllers;

use App\Models\UserModel;
use Config\Gmail;
use Dompdf\Exception;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

/**
 * Class Auth
 * @package App\Controllers
 * @author Mubasher iqbal
 */
class Auth extends BaseController
{
    private $session;
    private $user_model;
    private $mCurrentDateTime;

    public function __construct(){
        ini_set('display_errors', '1');
        $this->session = session();
        $this->user_model = new UserModel();
        $this->mCurrentDateTime = date("Y-m-d H:i:s");
    }

    /**
     * @return \CodeIgniter\HTTP\RedirectResponse|string|void
     */
    public function index(){
        $data = [
            'pageTitle' => 'Register',
            'validation' => \Config\Services::validation()
        ];
        try{
            if ($this->request->getMethod() === 'post') {
                $validate = [
                    'username' => [
                        'rules'  => 'required|min_length[3]|max_length[50]|is_unique[user.username]',
                        'errors' => [
                            'required'      => 'You must choose a Username.',
                            'min_length'    => 'Your {field} is too short.',
                            'is_unique'     => 'This username is already registered.',
                            'max_length'    => 'Supplied value ({value}) for {field} must have at least {param} characters.',
                        ]
                    ],
                    'email'    => [
                        'rules'  => 'required|valid_email|is_unique[user.email]',
                        'errors' => [
                            'required'      => 'You must enter an email.',
                            'is_unique'     => 'This email is already registered. Please enter another email or login with {value}.',
                            'valid_email'   => 'Please check the Email field. It does not appear to be valid.',
                        ]
                    ],
                    'password' => [
                        'rules'  => 'required|min_length[3]|max_length[50]',
                        'errors' => [
                            'required' => 'You must enter a password.',
                            'min_length' => 'Your {field} is too short.',
                            'max_length' => 'Supplied value ({value}) for {field} must have at least {param} characters.'
                        ]
                    ],
                    'confirm_password' => [
                        'rules'  => 'required|min_length[3]|max_length[50]|matches[password]',
                        'errors' => [
                            'required'  => 'You must enter a password.',
                            'min_length'=> 'Your {field} is too short.',
                            'matches'   =>'Confirm password field doesn\'t match the password field.',
                            'max_length'=> 'Supplied value ({value}) for {field} must have at least {param} characters.'
                        ]
                    ],
                ];
                if(!$this->validate($validate)) {
                    return redirect()->back()->withInput();
                    //return redirect()->to('auth')->withInput();
                }
                $this->user_model->insert([
                    'created_date_time' => $this->mCurrentDateTime,
                    'updated_date_time' => $this->mCurrentDateTime,
                    'email'     => $this->request->getVar('email'),
                    'first_name'=> $this->request->getVar('username'),
                    'last_name' => $this->request->getVar('username'),
                    'username'  => $this->request->getVar('username'),
                    'password'  => password_hash($this->request->getVar('password'),1),
                    'confirm_password' => password_hash($this->request->getVar('confirm_password'),1),
                ]);
                $user_id= $this->user_model->getInsertID();
                $user   = $this->user_model->find($user_id);
                $this->set_session_data($user);
                return redirect()->to(base_url().'/user');
            } else {
                return view('auth/register', $data);
            }
        }catch (\Exception $e){
            print_r($e);
        }
    }

    /**
     * @return \CodeIgniter\HTTP\RedirectResponse|string|void
     */
    public function login(){
        try{
            $data = [
                'pageTitle' => 'Login',
                'validation' => \Config\Services::validation()
            ];
            if ($this->request->getMethod() === 'post') {
                $validate = [
                    'email'    => [
                        'rules'  => 'required',
                        'errors' => [
                            'required' => 'You must enter an email.'
                        ]
                    ],
                    'password' => [
                        'rules'  => 'required',
                        'errors' => [
                            'required' => 'You must enter a password.',
                        ]
                    ],
                ];
                if( !$this->validate($validate) ) {
                    return redirect()->back()->withInput();
                    //return redirect()->to('login')->withInput();
                }
                list($status, $data) = $this->exists($this->request->getPost('email'), $this->request->getPost('password'));
                if ($status == 200) {
                    $this->set_session_data($data);
                    return redirect()->to(base_url().'/user');
                }else{
                    $message = '';
                    if ($status == 404) {
                        $message = "Invalid password.";
                    }
                    if ($status == 400) {
                        $message = "Invalid email.";
                    }
                    $validate = [
                        'credentials'    => [
                            'rules'  => 'required',
                            'errors' => [
                                'required' => $message.' Please try again. '
                            ]
                        ],
                    ];
                    $this->validate($validate);
                    return redirect()->back()->withInput();
                }
            }else {
                $response       = $this->facebook_auth();
                if(is_array($response)){
                    $data['fb_login_button'] = $response[1];
                }
                $gAuth          = config('Gmail');
                $google_client  = new \Google_Client();
                $data['form']   = 'auth/login';
                $google_client->addScope($gAuth->Scope);
                $google_client->setClientId($gAuth->ClientId); //Define your ClientID
                $google_client->setRedirectUri($gAuth->RedirectUri); //Define your Redirect Uri
                $google_client->setClientSecret($gAuth->ClientSecret); //Define your Client Secret Key
                if(isset($_GET["code"])) {
                    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
                    if(!isset($token["error"])) {
                        $google_client->setAccessToken($token['access_token']);
                        $this->session->set('access_token', $token['access_token']);
                        $google_service = new \Google_Service_Oauth2($google_client);
                        $user_info      = $google_service->userinfo->get();
                        $account        = $this->user_model->where('login_oauth_uid', $user_info['id'])->first();
                        if($account) {
                            $user_data = array(
                                'email'             => $user_info['email'],
                                'last_name'         => ($user_info['familyName'])? $user_info['familyName']   : $user_info['givenName'],
                                'first_name'        => ($user_info['givenName']) ? $user_info['givenName']    : $user_info['familyName'],
                                'updated_date_time' => $this->mCurrentDateTime
                            );
                            $this->user_model->set($user_data);
                            $this->user_model->where('login_oauth_uid', $user_info['id']);
                            $this->user_model->update();
                        } else {
                            $insert_data = [
                                'email'             => $user_info['email'],
                                'username'          => $user_info['givenName'].' '.$user_info['familyName'],
                                'last_name'         => ($user_info['familyName'])? $user_info['familyName']   : $user_info['givenName'],
                                'first_name'        => ($user_info['givenName']) ? $user_info['givenName']    : $user_info['familyName'],
                                'login_oauth_uid'   => $user_info['id'],
                                'created_date_time' => $this->mCurrentDateTime,
                                'updated_date_time' => $this->mCurrentDateTime,
                            ];
                            $this->user_model->insert($insert_data);
                        }
                        $user_account = $this->user_model->where('login_oauth_uid', $user_info['id'])->first();
                        $this->set_session_data($user_account,'login_oauth_uid','gmail');
                    }
                }
                $login_button = '';
                if(!$this->session->get('access_token')) {
                    $login_button = '<a href="'.$google_client->createAuthUrl().'" class="btn btn-block btn-danger">
                                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google
                                        </a>';
                    $data['gm_login_button'] = $login_button;
                }else{
                    if($user_account){
                        return redirect()->to(base_url().'/user');
                    }
                }
                return view('auth/login', $data);
            }
        }catch (\Exception $e){
            print_r($e);
        }
    }

    public function set_session_data($data,$login_key=null,$login_from=null){
        try{
            /*$session_array = [
                'logged_in'         => TRUE,
                'session_id'        => session_id(),
                'email'             => $data['email'],
                'user_id'           => $data['user_id'],
                'username'          => $data['username'],
                'icon_path'         => $data['icon_path'],
                'profile_image'     => $data['profile_image'],
                'thumbnail_path'    => $data['thumbnail_path'],
                'remote_address'    => $_SERVER['REMOTE_ADDR'],
                'created_date_time' => $data['created_date_time'],
                'user_agent'        => $_SERVER['HTTP_USER_AGENT'],
                'full_name'         => $data['first_name'].' '.$data['last_name'],
            ];
            $this->session->set($session_array);*/
            $this->session->set('isLoggedIn', true);
            $login_session = [
                'session_id'        => session_id(),
                'email'             => $data['email'],
                'user_id'           => $data['user_id'],
                'username'          => $data['username'],
                'icon_path'         => $data['icon_path'],
                'profile_image'     => $data['profile_image'],
                'thumbnail_path'    => $data['thumbnail_path'],
                'remote_address'    => $_SERVER['REMOTE_ADDR'],
                'created_date_time' => $data['created_date_time'],
                'user_agent'        => $_SERVER['HTTP_USER_AGENT'],
                'full_name'         => $data['first_name'].' '.$data['last_name'],
            ];
            if($login_from != ''){
                $login_session['login_key']     = $login_key;
                $login_session['login_from']    = $login_from;
                $login_session['social_login']  = true;
            }
            $this->session->set('userData',$login_session);
        }catch (Exception $exception){
            print_r($exception);
        }
    }

    /**
     * @return \CodeIgniter\HTTP\RedirectResponse|void
     */
    public function logout(){
        try{
            $this->session->remove(['access_token', 'isLoggedIn', 'userData']);
            return redirect()->to(base_url().'/login');
        }catch (Exception $exception){
            print_r($exception);
        }
    }

    /**
     * @param $email
     * @param $password
     * @return array
     */
    private function exists($email, $password) {
        $account = $this->user_model->where('email', $email)->first();
        if ($account != NULL) {
            if (password_verify($password, $account['password'])) {
                return array(200, $account);
            }
            return array(404,array());
        }
        return array(400,array());
    }

    /**
     * @param $password
     * @param $username
     * @return bool
     */
    public function check_valid($password,$username){
        if(!$this->users_model->isValid($username,sha1($password))){
            $this->form_validation->set_message('check_valid', 'login_err|Sorry, but you have provided invalid username or password.');
            return FALSE;
        }
        return TRUE;
    }

    public function facebook_auth(){
        try{
            $config_fb = config('Facebook');
            $fb = new Facebook([
                'app_id'                => $config_fb->app_id,
                'app_secret'            => $config_fb->app_secret,
                'default_graph_version' => $config_fb->default_graph_version,
            ]);
            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['email']; // optional
            try {
                if (isset($_SESSION['facebook_access_token'])) {
                    $accessToken = $_SESSION['facebook_access_token'];
                } else {
                    $accessToken = $helper->getAccessToken();
                }
            } catch(FacebookResponseException $e) {
                // When Graph returns an error
                return [$e->getCode() ,'Graph returned an error: ' . $e->getMessage()];
            } catch(FacebookSDKException $e) {
                // When validation fails or other local issues
                return [$e->getCode() ,'Facebook SDK returned an error: ' . $e->getMessage()];
            }
            if (isset($accessToken)) {
                if (isset($_SESSION['facebook_access_token'])) {
                    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                } else {
                    // getting short-lived access token
                    $this->session->set('facebook_access_token', (string) $accessToken);
                    // OAuth 2.0 client handler
                    $oAuth2Client = $fb->getOAuth2Client();
                    // Exchanges a short-lived access token for a long-lived one
                    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                    $this->session->set('facebook_access_token', (string) $longLivedAccessToken);
                    // setting default access token to be used in script
                    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                }
                // redirect the user to the profile page if it has "code" GET variable
                if (isset($_GET['code'])) {
                    header('Location: profile.php');
                }
                // getting basic info about user
                try {
                    $profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
                    $requestPicture = $fb->get('/me/picture?redirect=false&height=200'); //getting user picture
                    $picture = $requestPicture->getGraphUser();
                    $profile = $profile_request->getGraphUser();
                    $fbid = $profile->getProperty('id');           // To Get Facebook ID
                    $fbfullname = $profile->getProperty('name');   // To Get Facebook full name
                    $fbemail = $profile->getProperty('email');    //  To Get Facebook email
                    $fbpic = "<img src='".$picture['url']."' class='img-rounded'/>";
                    # save the user nformation in session variable
                    $_SESSION['fb_id'] = $fbid.'</br>';
                    $_SESSION['fb_name'] = $fbfullname.'</br>';
                    $_SESSION['fb_email'] = $fbemail.'</br>';
                    $_SESSION['fb_pic'] = $fbpic.'</br>';
                } catch(FacebookResponseException $e) {
                    // When Graph returns an error
                    session_destroy();
                    // redirecting user back to app login page
                    header("Location: ./");
                    return [$e->getCode() ,'Graph returned an error: ' . $e->getMessage()];
                } catch(FacebookSDKException $e) {
                    // When validation fails or other local issues
                    return [$e->getCode() ,'Facebook SDK returned an error: ' . $e->getMessage()];
                }
            } else {
                // replace your website URL same as added in the developers.Facebook.com/apps e.g. if you used http instead of https and you used
                $loginUrl = $helper->getLoginUrl($config_fb->app_url, $permissions);
                /*$button = '<a href="' . $loginUrl . '">Log in with Facebook!</a>';*/
                $button = '<a href="'.$loginUrl.'" class="btn btn-block btn-primary">
                            <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                           </a>';
                return array(200,$button);
            }
        }catch (\Exception $e){
            return [$e->getCode() , $e->getMessage()];
        }
    }
}
