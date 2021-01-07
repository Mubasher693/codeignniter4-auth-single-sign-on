<?php namespace App\Controllers;

use App\Models\UserModel;
use Dompdf\Exception;

class Test extends Authorization
{

    protected  $mTempFileName;
    protected  $mCurrentDateTime;
    public function __construct(){
        parent::__construct();
        if(!$this->check_session()){
            return redirect()->to(base_url('/login') );
        }
        $this->mCurrentDateTime = date("Y-m-d H:i:s");
    }

	public function index(){
	    try {
            $validation = \Config\Services::validation();
            $pic = $this->request->getFile('image');
            if ($pic->isValid()) {
                //let set the rules
                $profile_pic = [
                    'photo' => [
                        'label' => 'Image',
                        'rules' => 'uploaded[image]|max_size[image,2048]|is_image[image]',
                        'errors' => [
                            'max_size' => 'The uploaded image not accepted',
                            'is_image' => 'Image format not accepted'
                        ]
                    ]
                ];
                //now let validate the picture
                if (!$this->validate($profile_pic)) {
                    //let store the error here
                    $errors = array(
                        'error' => $validation->listErrors('error_msg')
                    );
                    //show the error
                    session()->setFlashdata($errors);
                    return redirect()->back()->withInput();
                } else {
                    //this is to get random
                    $name = $pic->getRandomName();
                    $pic->move('./assets/images/blog/', $name);
                }
            }
        }catch (Exception $exception){
	        print_r($exception);
        }
	}

	public function get($id){
        try {
            $update_password = FALSE;
            $user_model = new UserModel();
            $data = [
                'id'                =>  $id,
                'breadcrumb_heading'=>  'Profile',
                'pageTitle'         =>  'Profile',
                'grid_heading'      =>  'User Profile',
                'card_title'        =>  'Users details.',
                'validation'        =>  \Config\Services::validation()
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
                        'min_length'=> 'Your {field} is too short.',
                        'is_unique' => 'This username is already registered.',
                        'max_length'=> 'Supplied value ({value}) for {field} must have at least {param} characters.',
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
                    $this->load->library('form_validation');
                    $validate['image'] = [
                        'rules'  => 'ext_in[jpg, jpeg, png]|max_size[image, 2048]|is_image[image]',
                        'errors' => [
                            'is_image'  => 'Image format not accepted',
                            'ext_in'    => 'Image must be of jpeg, jpg or png format.',
                            'max_size'  => 'Maximum 2048 KB or 2 MB file size is allowed.',
                        ]
                    ];
                }
                if( !$this->validate($validate) ) {
                    //let store the error here
                    $errors = array(
                        'error' => $validate->listErrors('error_msg')
                    );
                    //show the error
                    session()->setFlashdata($errors);
                    return redirect()->back()->withInput();
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
                    $profile_image = $this->do_upload_profile_image($this->mTempFileName, $id, $first_name, $last_name);
                    $data_to_save['profile_image'] = $profile_image;
                }
                if($update_password){
                    $user_data['password']          = password_hash($password, 1);
                    $user_data['confirm_password']  = password_hash($confirm_password, 1);
                }
                $user_model->update($id, $user_data);
                return redirect()->back()->withInput();
            }else {
                $data['info'] = $user_model->find($id);
                return view('users/profile/index', $data);
            }
        }catch (Exception $exception){
            print_r($exception);
        }
    }

    public function profile_image_upload(){
        try {
            $config = array(
                'upload_path'   => TEMP_PATH,
                'allowed_types' => "jpg|jpeg",
                'overwrite'     => TRUE,
                'max_size'      => "10048000" // Can be set to particular file size , here it is 10 MB(2048 Kb)
            );
            $target_file            = basename($_FILES["image"]["name"]);
            $file_type              = pathinfo($target_file, PATHINFO_EXTENSION);
            $config['file_name']    = $this->mTempFileName . '.' . $file_type;
            $this->load->library('upload', $config);
            $this->mMessage = TRUE;
            if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                if ($this->upload->do_upload('image')) {
                    $upload_data    = $this->upload->data();
                    $_POST['image'] = $upload_data['file_name'];
                    $this->mMessage = TRUE;
                } else {
                    $this->mMessage = FALSE;
                }
            } else {
                if ($this->mEmployeeId == 0) { $this->mMessage = FALSE; }
            }
        }catch (Exception $e){
            $error = '<div id="error-box" class="container callout callout-warning" style="margin: 0 auto;padding: 5px 0px 5px 0px;text-align: center;">'.$e->getMessage().'</div>';
            print_r($error);
        }
        catch (Error $e) {
            $error = '<div id="error-box" class="container callout callout-warning" style="margin: 0 auto;padding: 5px 0px 5px 0px;text-align: center;">'.$e->getMessage().'</div>';
            print_r($error);
        }finally{
            return $this->mMessage;
        }
    }

    /**
     * @param $id
     * @param $first_name
     * @param $last_name
     * @return string
     * @throws Exception
     */
    public function do_upload_profile_image($temp_file_name, $id, $first_name, $last_name){
        try {
            $target_file    = basename($_FILES["image"]["name"]);
            $file_type      = pathinfo($target_file, PATHINFO_EXTENSION);
            $profile_image  = mt_rand(1000, 50000) . '_' . time() . '_' . $id . '_' . $first_name . '-' . trim($last_name) . '.' . $file_type;
            $path = IMAGES_PATH.$id.'/';
            if(!is_dir($path)){
                mkdir($path, 0777, true);
            }
            helper('common');
            rename(TEMP_PATH . $temp_file_name . '.' . $file_type, $path . $profile_image);//rename the temp file which is uploaded
            correct_image_orientation($path . $profile_image); //correct image orientation
            chmod($path . $profile_image,0777);
            //creating thumbnail
            if(!is_dir($path. 'thumbnail/')){
                mkdir($path. 'thumbnail/', 0777);
            }
            $this->createThumbnail($path. 'thumbnail/', $path.$profile_image);
            chmod($path . 'thumbnail/' . $profile_image, 0777);
            return $profile_image;
        }catch (Exception $exception){
            throw new Exception($exception);
        }
    }

    /**
     * Helper Method
     *
     * This function will create the thumbnail
     *
     * @param $filename
     * @return bool|null
     */
    public function createThumbnail($path, $filename){
        try {
            $this->mMessage = TRUE;
            $config = array(
                'image_library' => 'gd2',
                'source_image' => $filename,
                'new_image' => $path, //save as new image //need to create thumbs
                'maintain_ratio' => TRUE,
                'create_thumb' => TRUE,
                'thumb_marker'  => '' ,
                'width' => IMAGE_THUMBNAIL_WIDTH,
                'height' => IMAGE_THUMBNAIL_HEIGHT
            );
            $this->load->library('image_lib');
            $this->image_lib->initialize($config);
            if(!$this->image_lib->resize()){
                $this->form_validation->set_message('do_image_upload', 'image_err|'.strip_tags($this->image_lib->display_errors()));
                $this->mMessage = FALSE;
            }
            $this->image_lib->clear();
        }catch (Exception $e){
            $error = '<div id="error-box" class="container callout callout-warning" style="margin: 0 auto;padding: 5px 0px 5px 0px;text-align: center;">'.$e->getMessage().'</div>';
            print_r($error);
        }
        catch (Error $e) {
            $error = '<div id="error-box" class="container callout callout-warning" style="margin: 0 auto;padding: 5px 0px 5px 0px;text-align: center;">'.$e->getMessage().'</div>';
            print_r($error);
        }finally{
            return $this->mMessage;
        }
    }

}
