<?php

/**
 * Common Helpers
 *
 * @package			SIVA_ACL
 * @subpackage		Application Helper
 * @category		Helpers
 * @author			Sajjad Mahmood
 * @company			Simplicity Technologies Private Limited.
 * @description		This will include some common used functions. 
 */
if ( ! function_exists('ROI_values_calculation')){
    /**
     * Get return by performing operation on two values
     *
     * @param integer $operation
     * @param double $nominator
     * @param double $denominator
     * @return float|int
     */
    function ROI_values_calculation($operation,$val1,$val2){
        $val = '';
        switch ($operation){
            case 1:
                //divide
                $val = $val1/$val2;
                break;
            case 2:
                //multiply
                $val = $val1*$val2;
                break;
            default:
                exit();
        }
        return $val ;
    }
}

if ( ! function_exists('is_key_value_exists_in_array')){
    /**
     * This function will excute for getting the particular label against a particular key
     *
     * @param $searchlabel
     * @param $searchval
     * @param $array
     * @return bool
     */
    function is_key_value_exists_in_array($searchlabel, $searchval, $array){
        foreach ($array as $key => $val) {
            if ($val[$searchlabel] === $searchval) {
                return TRUE;
            }
        }
        return FALSE;
    }
}

if ( ! function_exists('is_keys_values_exists_in_array')){
    /**
     * This function will execute for getting the particular label against a particular key
     *
     * @param $search_label_one
     * @param $search_val_one
     * @param $search_label_two
     * @param $search_val_two
     * @param $array
     * @return bool
     */
    function is_keys_values_exists_in_array($search_label_one, $search_val_one, $search_label_two, $search_val_two, $array){
        $found = FALSE;
        foreach ($array as $row) {
            if ((strtolower(trim($row[$search_label_one])) === strtolower(trim($search_val_one))) && (strtolower(trim($row[$search_label_two])) === strtolower(trim($search_val_two)))) {
                $found =  TRUE; break;
            }
        }
        return $found;
    }
}

if ( ! function_exists('is_keys_values_exists_in_array_get_key')){
    /**
     * This function will execute for getting the particular label against a particular key
     *
     * @param $search_label_one
     * @param $search_val_one
     * @param $search_label_two
     * @param $search_val_two
     * @param $array
     * @param $key
     * @return bool
     */
    function is_keys_values_exists_in_array_get_key($search_label_one, $search_val_one, $search_label_two, $search_val_two, $array, $key){
        foreach ($array as $row) {
            if ((strtolower(trim($row[$search_label_one])) === strtolower(trim($search_val_one))) && (strtolower(trim($row[$search_label_two])) === strtolower(trim($search_val_two)))) {
                return $row[$key];
            }
        }
        return null;
    }
}

if ( ! function_exists('search_value_from_array')){
    /**
     * This function will excute for getting the particular label against a particular key
     * e.g. search camera label or person label
     *
     * @param string/integer $id
	 * @param string $match_key
	 * @param string $return_label
     * @param array $array else return false
	 * @return string 
     */
    function search_value_from_array($id, $match_key, $return_label, $array){
        foreach($array as $key => $val){
			if($val[$match_key] == $id){
           		return $val[$return_label];
       		}
   		}
   		return NULL;
    }
}

if ( ! function_exists('unique_valued_multidimensional_array')){
    /**
     * this function will return unique values based on key sent for multidimensional array
     *
     * @param $array
     * @param $key
     * @return array
     */
    function unique_valued_multidimensional_array($array,$key){
        $temp_array = [];
        foreach ($array as &$v) {
            if (!isset($temp_array[$v[$key]]))
                $temp_array[$v[$key]] =& $v;
        }
        $array = array_values($temp_array);
        return $array;
    }
}

if ( ! function_exists('live_notification_from_db')){
    /**
     * this function will return all notification saved in db
     *
     * @return mixed
     */
    function live_notification_from_db(){
        $CI =& get_instance();
        $CI->load->model('exemptlist_model');
        $notifications = $CI->exemptlist_model->getAllNotificationExemptedVehicle();
        return $notifications;
    }
}

if ( ! function_exists('has_duplicates_in_array')){
    /**
     * check if there is any duplicate values exists within the array
     *
     * @param array $array
     * @return boolean TRUE or FALSE
     */
    function has_duplicates_in_array($array){
        return count($array) != count(array_unique($array));
    }
}

if ( ! function_exists('clean_url')){
    /**
     * This function makes any text into a url frienly
     *
     * @param string $text
     * @return string
     */
    function clean_url($text){
        $text=strtolower($text);
        $text=preg_replace('/[^A-Za-z0-9 ]/','',$text);
        $code_entities_match = array(' ','&nbsp;','--','&quot;','&#39;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=');
        $code_entities_replace = array('-','-');
        $text = str_replace($code_entities_match, $code_entities_replace, $text);
        return $text;
    }
}

if ( ! function_exists('clear_extras')){
    /**
     * This function will clear the value will do the stuff like trim, stripslashes etc
     *
     * @param string $iValue
     * @return decimal $rValue
     */
    function clear_extras($iValue){
        $rValue = stripslashes(strip_tags(trim($iValue)));
        return $rValue;
    }
}

if ( ! function_exists('check_access_right')){
    /**
     * Here we need to check the access right is given or not
     * This is used to populate the tree of features means the folders and features
     * For the super admin it will always be TRUE means we allow everything
     *
     * @param string $module_name
     * @param string $check_permission [default is view, it can be add/edit/changestatus/delete/export]
     * @return boolean TRUE in case of access right or FLASE in can of no access rights
     */
    function check_access_right($module_name, $check_permission = 'view'){
        $ci = & get_instance();
        $Allowed = FALSE;
        if($ci->session->userdata('user_type_id') == 1){
            $Allowed =  TRUE;
        } else {
            $permitted_features_urls	= $ci->session->userdata('permitted_features_urls');
            $user_access_features	    = $ci->session->userdata('user_access_features');
            if(!empty($permitted_features_urls) && in_array($module_name, $permitted_features_urls)){
                foreach($user_access_features AS $row) {
                   if($row['permission_url'] == $check_permission && $row['feature_url'] == $module_name) {
                       switch ($check_permission) {                            
                            case 'add':
                            case 'edit':
                            case 'update':
                            case "delete":
                               $Allowed = TRUE;
                               break;
                           default:
                               break;
                       }
                   }
                }
            }
        }
        return $Allowed;
    }
}

if ( ! function_exists('check_feature_permission_type')){
    /**
     * Check if record against feature and role id exists and if active then return true else false
     *
     * @param $feature_id
     * @param $permission_id
     * @return bool
     */
    function check_feature_permission_type($feature_id, $permission_id){
        $ci = & get_instance();
        $ci->load->model('roles_model');
        return $ci->roles_model->checkFeaturesPermissions($feature_id, $permission_id, 1);
    }
}

if ( ! function_exists('correct_image_orientation')){
    /**
     * correct the orientation of the images which are showing horizontally prviously are now being shown vertically
     *
     * @param string $filename
     */
    function correct_image_orientation($filename){
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($filename);
            if($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                if($orientation != 1){
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }
                    if ($deg) {
                        $img = imagerotate($img, $deg, 0);
                    }
                    // then rewrite the rotated image back to the disk as $filename
                    imagejpeg($img, $filename, 95);
                } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists
    }
}

if ( ! function_exists('count_notification')){
    /**
     * Count notifications
     *
     * @param $read_status
     * @return mixed
     * @throws Exception
     */
    function count_notification($read_status=-1){
        $exception_type = 'Notification count error.';
        $ci = & get_instance();
        $ci->load->model('notification_model');
        $ci->load->library('session');
        $user_id = $ci->session->userdata('user_id');
        if(empty($user_id)){
            return 0;
        }
        $filter_args = array(
            "read"=>$read_status,
            "user"=>$user_id,
            "status"=>array(1)
        );
        $response = $ci->notification_model->get_count_notification_v2($filter_args);
        if(!empty($response) && array_key_exists('message',$response)){
            if(array_key_exists('status',$response) && empty($response['status']) || $response['status'] != 200){
                throw new Exception($exception_type.' '.$response['message']);
            }
            return $response['message'];
        }
        throw new Exception($exception_type);
    }
}

if ( ! function_exists('get_number_in_number_format')){
    /**
     * This function will be used to convert number in number_format
     *
     * @param float $number
     * @param integer $decimalPlaces
     * @param boolean $showInMBT default is true
     * @return string
     */
    function get_number_in_number_format($number, $decimalPlaces = 2,$showInMBT=true){
        $convertedNumber = abs($number);
        if($convertedNumber > 1000000000000 && $showInMBT) {
            $convertedNumber = '<span data-toggle="tooltip" title="'.number_format($number, $decimalPlaces).'" data-original-title="'.number_format($number, $decimalPlaces).'">'.round(($convertedNumber/1000000000000), 3).' Trillion</span>';
        } elseif($convertedNumber > 1000000000 && $showInMBT){
            $convertedNumber = '<span data-toggle="tooltip" title="'.number_format($number, $decimalPlaces).'" data-original-title="'.number_format($number, $decimalPlaces).'">'.round(($convertedNumber/1000000000), 3).' Billion</span>';
        } elseif($convertedNumber > 1000000 && $showInMBT){
            $convertedNumber = '<span data-toggle="tooltip" title="'.number_format($number, $decimalPlaces).'" data-original-title="'.number_format($number, $decimalPlaces).'">'.round(($convertedNumber/1000000), 2).' Million</span>';
        } else {
            $convertedNumber = number_format($convertedNumber, $decimalPlaces);
        }
        $numberSignStr = '';
        if($number < 0){
            $numberSignStr = '-';
        }
        return $numberSignStr.$convertedNumber;
    }
}

if ( ! function_exists('get_camera_info')){
    /**
     * This function will excute for getting the info of the video
     * FFMPEG is used for this purpose, this function will get the fps and duration in form of seconds.miliseconds
     *
     * @param string $video
     * @return array $video_info else return false
     */
    function get_camera_info($input_String){
        $frames_per_second  = 0;
        if($input_String != ''){
            preg_match('/Video: (.*?) fps/is', $input_String , $result);
            if(!empty($result) && isset($result[0]) && $result[0] != ''){
                $fps_string = substr(strrchr($result[0], ","), 1);
                if($fps_string != '' && strpos($fps_string, 'fps') !== false){
                    $frames_per_second = trim(str_replace('fps', '', $fps_string));
                }
            }
        }
        return $frames_per_second;
    }
}

if ( ! function_exists('get_laneInformation')){
    /**
     * get lane information based on its id
     * @param $lane_id
     * @return mixed
     */
    function get_laneInformation($lane_id){
        $ci = & get_instance();
        $ci->load->model('lanes_model');
        return $ci->lanes_model->getLanesById($lane_id);
    }
}

if ( ! function_exists('get_unique_array')) {
    function get_unique_array($dataArray, $key)
    {
        $result = array();
        $i = 0;
        $key_array = array();
        foreach ($dataArray as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $result[$i] = $val;
            }
            $i++;
        }
        return $result;
    }
}

if ( ! function_exists('get_json_response'))
{
    /**
     * Returns the Json Config array from config/json_response.php
     *
     * @return	array
     */
    function get_json_response()
    {
        static $_json_config;

        if (empty($_json_config))
        {
            $_json_config = file_exists(APPPATH.'config/json_response.php') ? include(APPPATH.'config/json_response.php') : array();

            if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/json_response.php'))
            {
                $_json_config = array_merge($_json_config, include(APPPATH.'config/'.ENVIRONMENT.'/json_response.php'));
            }
        }

        return $_json_config;
    }
}

if ( ! function_exists('get_json_config'))
{
    /**
     * Returns the Json Config array from config/json_config.php
     *
     * @return	array
     */
    function get_json_config()
    {
        static $_json_config;

        if (empty($_json_config))
        {
            $_json_config = file_exists(APPPATH.'config/json_config.php') ? include(APPPATH.'config/json_config.php') : array();

            if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/json_config.php'))
            {
                $_json_config = array_merge($_json_config, include(APPPATH.'config/'.ENVIRONMENT.'/json_config.php'));
            }
        }

        return $_json_config;
    }
}

if ( ! function_exists('get_lanes_count_by_location')){
    /**
     * Get Lanes count by location
     *
     * @param null $id
     * @param null $status
     * @return mixed
     * @throws Exception
     */
    function get_lanes_count_by_location($id = null,$status = null){
        $exception_type = 'Internal server error.';
        $ci = & get_instance();
        $ci->load->model('lanes_model');
        $response = $ci->lanes_model->get_count_all_lanes($id);
        if(!empty($response) && array_key_exists('message',$response)){
            if(array_key_exists('status',$response) && empty($response['status']) || $response['status'] != 200){
                throw new Exception($exception_type.' '.$response['message']);
            }
            return $response['message'];
        }
        throw new Exception($exception_type);

    }
}

if ( ! function_exists('get_ip_devices_count_by_lane')){
    /**
     * Get Ip devices count by Ip device
     *
     * @param null $id
     * @param null $status
     * @param null $category
     * @return mixed
     * @throws Exception
     */
    function get_ip_devices_count_by_lane($id = null, $status = null, $category = null){
        $exception_type = 'Internal server error.';
        $ci = & get_instance();
        $ci->load->model('ip_devices_model');
        $response = $ci->ip_devices_model->get_count_all_ip_devices("", $id, $category);
        if(!empty($response) && array_key_exists('message',$response)){
            if(array_key_exists('status',$response) && empty($response['status']) || $response['status'] != 200){
                throw new Exception($exception_type.' '.$response['message']);
            }
            return $response['message'];
        }
        throw new Exception($exception_type);

    }
}

if ( ! function_exists('get_ip_devices_count_by_location')){
    /**
     * Get ip device count by location
     *
     * @param null $location
     * @param null $category
     * @param int $status
     * @return mixed
     * @throws Exception
     */
    function get_ip_devices_count_by_location($location = null, $category = null, $status=1){
        $exception_type = 'Internal server error.';
        $ci = & get_instance();
        $ci->load->model('ip_devices_model');
        $response = $ci->ip_devices_model->get_count_ip_devices_by_location($location, $category, $status);
        if(!empty($response) && array_key_exists('message',$response)){
            if(array_key_exists('status',$response) && empty($response['status']) || $response['status'] != 200){
                throw new Exception($exception_type.' '.$response['message']);
            }
            return $response['message'];
        }
        throw new Exception($exception_type);

    }
}

if ( ! function_exists('get_notification')){
    /**
     * Get notifications
     *
     * @param $read_status
     * @return mixed
     * @throws Exception
     */
    function get_notification($read_status=-1){
        $exception_type = 'Notification get error.';
        $ci = & get_instance();
        $ci->load->model('notification_model');

        $ci->load->library('session');
        $user_id = $ci->session->userdata('user_id');
        if(empty($user_id)){
            $response = array();
            return $response;
        }
        $filter_args = array(
            "user"=>$user_id,
            "status"=>array(1)
        );
        
        $response = $ci->notification_model->get_notifications_v2($filter_args);
        if(!empty($response) && array_key_exists('message',$response)){
            if(array_key_exists('status',$response) && empty($response['status']) || $response['status'] != 200){
                throw new Exception($exception_type.' '.$response['message']);
            }
            return $response['message'];
        }
        throw new Exception($exception_type);
    }
}

if ( ! function_exists('get_ip_device_info')){
    /**
     * Get ip_device_info
     *
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function get_ip_device_info($id){
        if($id !='') {
            $exception_type = 'Ip_device Action.';
            $ci = & get_instance();
            $ci->load->model('ip_devices_model');
            $response = $ci->ip_devices_model->get_ip_device_info_by_id($id, TRUE, TRUE);
            if (!empty($response) && array_key_exists('message', $response)) {
                if (array_key_exists('status', $response) && empty($response['status']) || $response['status'] != 200) {
                    throw new Exception($exception_type . ' ' . $response['message']);
                }
                return $response['message'];
            }
            throw new Exception($exception_type);
        }
        return null;
    }
}

if ( ! function_exists('get_image_from_lmdb')){
    /**
     * Get image from lmdb using curl
     *
     * @param $key
     * @return null
     */
    function get_image_from_lmdb($key){
        $data = array();
        if($key !='') {
            $image_data = array();
            $image_data['Key'] = $key;
            //Submit the above JSON string via CURL POST
            $curl_handle = curl_init();
            //create a new cURL resource to url
            curl_setopt($curl_handle, CURLOPT_URL, LMDB_URL_FOR_PERCEPTION);
            //attach JSON string to the GET fields
            curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($image_data));
            //return response instead of outputting 
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
            //execute the request 
            $result = curl_exec($curl_handle);
            //close cURL resource
            curl_close($curl_handle);
            $image_encoded_string = '';
            if($result != ''){ $image_encoded_string = base64_encode($result); }
            $data['ImageEncodedString'] = $image_encoded_string;
        }
        return $data;
    }
}

if ( ! function_exists('get_features_with_no_permissions')){
    /**
     * @param $permalink
     * @return array
     */
    function get_features_with_no_permissions($permalink){
        $data = array('dashboard','reports');
        if(in_array($permalink, $data)){ return TRUE; }
        return FALSE;
    }
}

if ( ! function_exists('secondsToWords')){
    /**
     * Convert seconds into days, hours miniutes
     *
     * @param integer $seconds
     * @return string $message
     * @throws Exception
     */
    function secondsToWords($seconds){
        $ret = "";

        /*** get the days ***/
        $days = intval(intval($seconds) / (3600*24));
        if($days> 0)
        {
            $ret .= "$days:";
        } else {
            $ret .= "0:";
        }

        /*** get the hours ***/
        $hours = (intval($seconds) / 3600) % 24;
        if($hours > 0)
        {
            $ret .= "$hours:";
        } else {
            $ret .= "0:";
        }

        /*** get the minutes ***/
        $minutes = (intval($seconds) / 60) % 60;
        if($minutes > 0)
        {
            $ret .= "$minutes:";
        } else {
            $ret .= "0:";
        }

        /*** get the seconds ***/
        $seconds = intval($seconds) % 60;
        if ($seconds > 0) {
            $ret .= "$seconds";
        } else {
            $ret .= "0";
        }

        return $ret;
    }
}

if ( ! function_exists('is_employee_exists_with_payroll_company_in_array')){
    /**
     * This function will execute for getting the particular label against a particular key
     *
     * @param $employee_no_key
     * @param $employee_no
     * @param $payroll_no_key
     * @param $payroll_no
     * @param $company_key
     * @param $company 
     * @param $array
     * @return bool
     */
    function is_employee_exists_with_payroll_company_in_array($employee_no_key, $employee_no, $payroll_no_key, $payroll_no, $company_key, $company, $array){
        $found = FALSE;
        foreach ($array as $row) {
            if ((strtolower(trim($row[$employee_no_key])) === strtolower(trim($employee_no))) && (strtolower(trim($row[$payroll_no_key])) === strtolower(trim($payroll_no))) && (strtolower(trim($row[$company_key])) === strtolower(trim($company)))) {
                $found =  TRUE; break;
            }
        }
        return $found;
    }
}

if ( ! function_exists('get_key_by_employee_payroll_company_in_array')){
    /**
     * This function will execute for getting the particular label against a particular key
     *
     * @param $employee_no_key
     * @param $employee_no
     * @param $payroll_no_key
     * @param $payroll_no
     * @param $company_key
     * @param $company 
     * @param $array
     * @param $key
     * @return bool
     */
    function get_key_by_employee_payroll_company_in_array($employee_no_key, $employee_no, $payroll_no_key, $payroll_no, $company_key, $company, $array, $key){
        foreach ($array as $row) {
            if ((strtolower(trim($row[$employee_no_key])) === strtolower(trim($employee_no))) && (strtolower(trim($row[$payroll_no_key])) === strtolower(trim($payroll_no))) && (strtolower(trim($row[$company_key])) === strtolower(trim($company)))) {
                return $row[$key];
            }
        }
        return null;
    }
}

if ( ! function_exists('search_code_from_array')){
    /**
     * BULK IMPORT EMPLOYEES
     * This function is used to get the actual code (the value saved in DB) if the lowercase matches with value provided     
     *
     * @param string $csv_value
	 * @param string $code_key
     * @param array $array 
	 * @return string that is matched with db else the csv value
     */
    function search_code_from_array($csv_value, $code_key, $array){
        foreach($array as $val){
			if(strtolower(trim($val[$code_key])) == strtolower(trim($csv_value))){
           		return $val[$code_key];
       		}
   		}
   		return $csv_value;
    }
}

if ( ! function_exists('get_date_time'))
{
    /**
     * Returns the date time both or any one
     *
     * @param $date_time
     * @param $choice
     * @return bool|string
     */
    function get_date_time($date_time, $choice = null)
    {
        $_date_config = '';
        if($date_time != ''){
            switch ($choice){
                case 1: // return date only
                    $_date_config = date("d/m/Y", strtotime($date_time));
                    break;
                case 2: // return time only
                    $_date_config = date("H:i:s", strtotime($date_time));
                    break;
                default: // return date and time both
                    $_date_config = date("d/m/Y H:i:s", strtotime($date_time));
            }
        }
        return $_date_config;
    }
}

if ( ! function_exists('search_value_from_array_except_id')){
    /**
     * BULK IMPORT EMPLOYEES
     * This function is used to get the actual key (the value saved in DB) if the lowercase matches with value provided
     *
     * @param $value
     * @param $key
     * @param $array
     * @param $except_value
     * @param $except_key
     * @return string
     */
    function search_value_from_array_except_id($value, $key, $array, $except_value, $except_key){
        foreach($array as $val){
            if( strtolower( trim($val[$key]) ) == strtolower($value) && $except_value != $val[$except_key]){
                return TRUE;
            }
        }
        return FALSE;
    }
}

if ( ! function_exists('get_employee_thumbnail')){
    /**
     * Get get_employee_thumbnail
     *
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function get_employee_thumbnail($id){
        if($id !='') {
            $exception_type = 'Employee Thumbnail.';
            $ci = & get_instance();
            $ci->load->model('employees_model');
            $response = $ci->employees_model->get_employee_thumbnail($id, TRUE, TRUE);
            if (!empty($response) && array_key_exists('message', $response)) {
                if (array_key_exists('status', $response) && empty($response['status']) || $response['status'] != 200) {
                    throw new Exception($exception_type . ' ' . $response['message']);
                }
                return $response['message'];
            }
            throw new Exception($exception_type);
        }
        return null;
    }
}

if ( ! function_exists('get_image_result_code_text')){
    /**
     * Get get_image_result_code_text
     *
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function get_image_result_code_text($id){
        $code_array = array(
            0   => 'Image not Found',
            1   => 'Vector Created',
            2   => ' ',
            3   => 'Has Duplicate',
            4   => 'Format Problem',
            5   => 'Resolution Problem',
            6   => 'Face size,position or detection Problem',
            7   => 'Eyes detection problem',
            8   => 'Face Tilt Problem',
            9   => 'Illumination Problem',
            10  => 'Blur Problem',
            11  => 'Some thing went wrong. Please try again.'
        );
        return $code_array[$id];
    }
}


if ( ! function_exists('standard_date')){
    /**
     * Convert date strings to standard_date
     *
     * @param $date
     * @return Date
     * @throws Exception
     */
    function standard_date($date){
        $temp_date_arr = explode('/',$date);
        $temp_date = $date;
        if(count($temp_date_arr) > 1){
            if(!is_numeric($temp_date_arr[1]) || $temp_date_arr[0] > 12){
                $temp_date = str_replace('/', '-', $date);
            }
        }else{
            $temp_date_arr = explode('-',$date);
            if(count($temp_date_arr) > 1){
                if(!is_numeric($temp_date_arr[0]) || $temp_date_arr[1] > 12){
                    $temp_date = $temp_date_arr[1] . "-" . $temp_date_arr[0] . "-".$temp_date_arr[2];
                }    
            }
        }
        return date("m/d/Y",strtotime($temp_date));
    }
}

if ( ! function_exists('standard_date_time')){
    /**
     * Convert date strings to standard_date_time
     *
     * @param $date
     * @return Date
     * @throws Exception
     */
    function standard_date_time($date){
        $temp_date_arr = explode('/',$date);
        $temp_date = $date;
        if(count($temp_date_arr) > 1){
            if(!is_numeric($temp_date_arr[1]) || $temp_date_arr[0] > 12){
                $temp_date = str_replace('/', '-', $date);
            }
        }else{
            $temp_date_arr = explode('-',$date);
            if(count($temp_date_arr) > 1){
                if(!is_numeric($temp_date_arr[0]) || $temp_date_arr[1] > 12){
                    $temp_date = $temp_date_arr[1] . "-" . $temp_date_arr[0] . "-".$temp_date_arr[2];
                }    
            }
        }
        return date("m/d/Y H:i:s",strtotime($temp_date));
    }
}

if ( ! function_exists('email_send')){
    /**
     * Send an email notification to system admin when AI Engine is Down
     * @param $subject
     * @param $emailMessage
     * @param $to
     * @param $cc    
     * @return send
     */
    function email_send($subject, $emailMessage, $to, $cc=null){
		$CI =& get_instance();
		$CI->load->library('email', EMAIL_CONFIG_SMTP);
		$CI->email->set_newline("\r\n");
		$CI->email->from(SENDER_EMAIL, SNEDER_NAME);	
		$CI->email->to($to);
        (isset($cc) ? $CI->email->cc($cc) : '');
		$CI->email->subject($subject);
		$CI->email->message($emailMessage.signature_html());
		return $CI->email->send(); 
    }
}

if ( ! function_exists('signature_html')){
    /**
     * Roshni Signature HTML used in email
     *     
     * @return string $html
     */
    function signature_html(){
		$html = '<br />ALEC<br />
				 <b style="color:#ffc502">Email:</b> support@stech.ai';
		return $html;
    }
}

if(!function_exists('notif')){
    function notif($params, $permission, $alert){
        $ci = & get_instance();
        
        $ci->load->model('users_model');
        $ci->load->model('lookups_model');
        $data_array = $ci->users_model->get_all_user_with_permission($permission);
        $users = $data_array['message'];
        // print_r($users);
        
        $roles = array_column($users, 'role_id');
        $user_ids = array_column($users, 'user_id');
        $roles = array_unique($roles);
        $user_ids = array_unique($user_ids);
        $user_ids[] = 1;
        $push = implode(",",$roles);
        $params["push"] = "0," . $push;
        // parr($users);
        $ci->load->model('notification_model');
        $response = $ci->notification_model->add_notification_v2($params, $user_ids);
        if(!empty($response) && array_key_exists('message',$response)){
            if(array_key_exists('status',$response) && empty($response['status']) || $response['status'] != 200){
                throw new Exception($response['message']);
            }
            $params['notification_id'] = $response['message'];
        }
        $response = $ci->lookups_model->get_notification_subtype($params["type"], $params["sub_type"]);
        if(!empty($response) && array_key_exists('message',$response)){
            if(array_key_exists('status',$response) && empty($response['status']) || $response['status'] != 200){
                throw new Exception($response['message']);
            }
            $notification_subtype = $response['message'];
        }
        
        $params["sub_type_text"] =$notification_subtype[0]["value"];
        $params["alert"] =$alert;
        
        $url = GLOBAL_IP_SOCKET . "/live/notification";
        return post_CURL($url, $params);
        return 0;
    }
}

if(!function_exists('post_CURL')){
    function post_CURL($_url, $_param){
        $postData = '';
        //create name value pairs seperated by &
        foreach($_param as $k => $v) 
        { 
          $postData .= $k . '='.$v.'&'; 
        }
        rtrim($postData, '&');


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

        $output=curl_exec($ch);

        curl_close($ch);

        return $output;
    }
}

if(!function_exists('url_encode')){
    function url_encode($str){
        return urlencode(utf8_encode($str));
    }
}
if(!function_exists('url_decode')){
    function url_decode($str){
        return urldecode(utf8_decode($str));
    }
}
if(!function_exists('str_encrypt')){
    function str_encrypt($str){
        $CI =& get_instance();
        $CI->load->library('encrypt');
        return $CI->encrypt->encode($str);
    }
}
if(!function_exists('str_decrypt')){
    function str_decrypt($str){
        $CI =& get_instance();
        $CI->load->library('encrypt');
        return $CI->encrypt->decode($str);
    }
}

if(!function_exists('subtype_icon')){
    function subtype_icon($subtype_key){
        switch ($subtype_key) {
            case 1:
            case 5:
                return "check";
            case 2:
                return "info";
            case 3:
                return "warning";
                break;
            case 4:
                return "times";            
        };
        return "circle-thin";
    }
}

if(!function_exists('sync_status_by_notification_subtype')){
    function sync_status_by_notification_subtype($subtype_key){
        switch ($subtype_key) {
            case 1:
            case 5:
                return 1;
            case 2:
                return 1;
            case 3:
                return 2;
                break;
            case 4:
                return 2;            
        };
        return 2;
    }
}

if(!function_exists('arr_to_nested')){
    /**
     * convert 1D array to nested array
     * input array
     * array(
     *  0   => array(
     *      'notification_type' =>  7,
     *      'key'               =>  1,
     *      'value'             =>  'success'
     *      ),
     *  1   => array(
     *      'notification_type' =>  7,
     *      'key'               =>  2,
     *      'value'             =>  'info'
     *      ),
     *  3   => array(
     *      'notification_type' =>  6,
     *      'key'               =>  1,
     *      'value'             =>  'success'
     *      ),
     * );
     * 
     * output
     * array(
     *  6   => array(
     *          array(
     *              'notification_type' =>  6,
     *              'key'               =>  1,
     *              'value'             =>  'success'    
     *          ),
     *      ),
     *  7   => array(
     *          array(
     *              'notification_type' =>  7,
     *              'key'               =>  1,
     *              'value'             =>  'success'
     *          ),
     *          array(
     *              'notification_type' =>  7,
     *              'key'               =>  2,
     *              'value'             =>  'info'
     *          ),
     *      )
     * );
     */
    function arr_to_nested($arr, $key){
        $result = array();
        foreach($arr as $k => $v){
            $result[$v[$key]][] = $v;
        }
        return $result;
    }
}

/* End of file common_helper.php */
/* Location: ./application/helpers/common_helper.php */