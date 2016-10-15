<?php
class Queries extends Model{
    /** __construct */
    public function __construct(){
        parent::__construct();
    }

    /**
     * FUNCTION: validation
     * This function validates post data, and should be called for any update or create model calls.
     * @param mixed array $data, $type can define different standards depending on if edit for example.
     */
    public function validation($data, $type){
        $return = $data;
        foreach($data as $key => $input){
            $temp = null;
            $input = is_array($input) ? FormInput::trimArray($input) : FormInput::checkInput($input);
            $return[$key] = $input;

            //title
            if($key == 'name'){
                //Max length
                $temp = Form::MaxLength($input, 100);
                if($temp[0] != true){
                    $return['error'][$key] = 'Name should not exceed 100 characters';
                }

                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Name cannot be empty';
                }
            }

            //email
            if($key == 'email'){
                //Description
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Email cannot be empty';
                }

                //Is email
                $temp = Form::ValidateEmail($input);
                if($temp[0] != true){
                    $return['error'][$key] = "Email should be in a valid email format";
                }
            }

            //question
            if($key == 'question'){
                //question
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Question cannot be empty';
                }
            }
        }
        return $return;
    }

    /**
     * FUNCTION: createData
     * This function adds a new query to the Database
     * @param mixed $data Array of Data
     */
    public function createData($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null) {
            return $data;
        }else{
            $dbTable = 'queries';
            $postData = array(
                'question' => $data['question'],
                'name' => $data['name'],
                'email' => $data['email'],
            );

            $this->_db->insert($dbTable, $postData);

            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
        }
    }



}?>