<?php namespace App\Controllers;

use App\Models\UserModel;
use Dompdf\Exception;

/**
 * Class Users
 * @package App\Controllers
 */
class Users extends Authorization{

    protected  $mTempFileName;
    protected  $mCurrentDateTime;
    public function __construct(){
        parent::__construct();
        if($this->check_session() === 2){
            return redirect()->to(base_url().'/login');
        }
        $this->mCurrentDateTime = date("Y-m-d H:i:s");
    }

    /**
     * List all users
     *
     * @return string
     */
	public function index(){
	    try {
            $data['grid_heading'] = 'Users';
            $data['breadcrumb_heading'] = 'Users';
            $data['card_title'] = 'All users';
            $user_model = new UserModel();
            $data['info'] = $user_model->findAll();
            return view('users/data_grid', $data);
        }catch (Exception $exception){
	        print_r($exception);
        }
	}

    /**
     * Get and update user profile
     *
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|string|void
     * @throws \ReflectionException
     */
	public function get($id){
        try {
            $validation =  \Config\Services::validation();

            $update_password = FALSE;
            $user_model = new UserModel();
            $data = [
                'id'                =>  $id,
                'breadcrumb_heading'=>  'Profile',
                'pageTitle'         =>  'Profile',
                'grid_heading'      =>  'User Profile',
                'card_title'        =>  'Users details.',
                'validation'        =>  $validation,
            ];
            $this->mTempFileName = time() . "_" . $id;
            if ($this->request->getMethod() === 'post') {
                $last_name  = $this->request->getVar('last_name');
                $first_name = $this->request->getVar('first_name');
                $username   = $this->request->getPost('username');
                $address    = $this->request->getPost('address');
                $mobile     = $this->request->getPost('mobile');
                $phone      = $this->request->getPost('phone');
                $email      = $this->request->getPost('email');
                $password   = $this->request->getPost('password');
                $confirm_password   = $this->request->getPost('confirm_password');
                if($password != null){
                    $update_password                = TRUE;
                    $validate['password']           = [
                        'rules'  => 'required|min_length[3]|max_length[50]',
                        'errors' => [
                            'required'  => 'You must enter a password.',
                            'min_length'=> 'Your {field} is too short.',
                            'max_length'=> 'Supplied value ({value}) for {field} must have at least {param} characters.'
                        ]
                    ];
                    $validate['confirm_password']   = [
                        'rules'  => 'required|min_length[3]|max_length[50]|matches[password]',
                        'errors' => [
                            'required'  => 'You must enter a password.',
                            'min_length'=> 'Your {field} is too short.',
                            'matches'   => 'Confirm password field doesn\'t match the password field.',
                            'max_length'=> 'Supplied value ({value}) for {field} must have at least {param} characters.'
                        ]
                    ];
                }
                $validate['email']      = [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'You must enter an email.'
                    ]
                ];
                $validate['phone']      = [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'You must enter your phone.'
                    ]
                ];
                $validate['mobile']     = [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'You must enter your mobile.'
                    ]
                ];
                $validate['address']    = [
                    'rules'  => 'required',
                    'errors' => [
                        'required'  => 'You must enter address.',
                    ]
                ];
                $validate['username']   = [
                    'rules'  => 'required|min_length[3]|max_length[50]|is_unique[user.username,user_id,'.$id.']',
                    'errors' => [
                        'min_length'    => 'Your {field} is too short.',
                        'required'      => 'You must choose a Username.',
                        'is_unique'     => 'This username is already registered.',
                        'max_length'    => 'Supplied value ({value}) for {field} must have at least {param} characters.',
                    ]
                ];
                $validate['last_name']  = [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'You must enter your last name.'
                    ]
                ];
                $validate['first_name'] = [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => 'You must enter your first name.'
                    ]
                ];
                if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                    $picture = $this->request->getFile('image');
                    if ($picture->isValid()) {
                        $validate['image'] = [
                            'rules'  => 'ext_in[image,jpg,jpeg,png]|max_size[image, 2048]|is_image[image]',
                            'errors' => [
                                'is_image'  => 'Image format not accepted',
                                'ext_in'    => 'Image must be of jpeg, jpg or png format.',
                                'max_size'  => 'Maximum 2048 KB or 2 MB file size is allowed.',
                            ]
                        ];
                    }
                }
                if( !$this->validate($validate) ) {
                    return redirect()->to(base_url().'/profile/'.$id)->withInput();
                }
                $user_data['email']             = $email;
                $user_data['phone']             = $phone;
                $user_data['mobile']            = $mobile;
                $user_data['address']           = $address;
                $user_data['username']          = $username;
                $user_data['last_name']         = $last_name;
                $user_data['first_name']        = $first_name;
                $user_data['updated_date_time'] = $this->mCurrentDateTime;
                if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                    $target_file    = basename($_FILES["image"]["name"]);
                    $file_type      = pathinfo($target_file, PATHINFO_EXTENSION);
                    $name = mt_rand(1000, 50000) . '_' . time() . '_' . $id . '_' . $first_name . '-' . trim($last_name);
                    $path = IMAGES_PATH.$id.'/';
                    if(!is_dir($path)){
                        mkdir($path, 0777, true);
                    }
                    $picture->move($path, $name.'.'.$file_type);
                    $user_data['profile_image']     = $name.'.'.$file_type;
                    $user_data['profile_image_path']= $path;
                    $path_thumbnail = $path.'/thumbnail/';
                    if(!is_dir($path_thumbnail)){
                        mkdir($path_thumbnail, 0777, true);
                    }
                    $this->crop(FCPATH.$path.$name.'.'.$file_type, FCPATH.$path. 'thumbnail/'.$name.'.'.$file_type, 192, 192 );
                    $user_data['thumbnail_path']= $path_thumbnail;
                    $path_icon = $path.'/icon/';
                    if(!is_dir($path_icon)){
                        mkdir($path_icon, 0777, true);
                    }
                    $this->crop(FCPATH.$path.$name.'.'.$file_type, FCPATH.$path. 'icon/'.$name.'.'.$file_type, 34, 34 );
                    $user_data['icon_path']= $path_icon;

                    $session_array['userData'] = [
                        'icon_path'         => $path_icon,
                        'thumbnail_path'    => $path_thumbnail,
                        'profile_image'     => $name.'.'.$file_type,
                    ];
                    session()->set($session_array);
                }
                if($update_password){
                    $user_data['password']          = password_hash($password, 1);
                    $user_data['confirm_password']  = password_hash($confirm_password, 1);
                }
                $user_model->update($id, $user_data);
                return redirect()->to(base_url().'/user');
            }else {
                $data['info'] = $user_model->find($id);
                return view('users/profile/index', $data);
            }
        }catch (Exception $exception){
            print_r($exception);
        }
    }

    public function crop($from, $to, $width, $height){
	    try{
	        //FCPATH.$path.$name.'.'.$file_type
            //FCPATH.$path. 'thumbnail/'.$name.'.'.$file_type
            \Config\Services::image()
                ->withFile($from)
                ->fit($width, $height, 'center')
                ->save($to);
            return true;
        }catch (Exception $e){
	        print_r($e);
            return false;
        }
    }

}
