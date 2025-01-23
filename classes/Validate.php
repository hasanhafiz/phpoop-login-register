<?php 

class Validate {
    private $_passed = false;
    private $_erros = [];
    private $_db = null;
    
    // initialize db
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }
    
    /** 
     $items = [  
            'username' => [
                'required' => true,
                'min' => 2,
                'max' => 2,
                'unique' => 'users'
                ]
            ],    
     */
    public function check( $source, $items = [] ) {
        // name required is true 
        // name min is 2
        // $item = fieldname = username
        // $rules = [
            // 'min' => 2,
            // 'max' => 6
        // ]
        
        foreach ($items as $item => $rules) {
            // rule = 'min', $rules_value = 2
            foreach ($rules as $rule => $rule_value) {
                // echo "{$item} {$rule} must be {$rule_value} <br/>";
                $field_value = trim( $source[$item] ); // field value is entered by user.
                // echo $field_value . "<br/>";
                
                // check required rule
                if ( empty( $field_value ) && $rule == 'required' ) {
                    $this->addError( "{$item} is required <br/>" );
                } elseif ( !empty( $field_value ) ) {
                    // check the other rules
                    switch ($rule) {
                        case 'min':
                            if ( strlen($field_value) < $rule_value ) {
                                $this->addError( "{$item} must be minimum of {$rule_value} chars <br/>" );
                            }
                            break;
                        case 'max':
                            if ( strlen($field_value) > $rule_value ) {
                                $this->addError( "{$item} must be maximum of {$rule_value} characters <br/>" );
                            }
                            break;
                        case 'matches':
                            if ( $field_value != $source[$rule_value] ) {
                                $this->addError( "{$item} must match with {$rule_value}  <br/>" );
                            }
                            break;
                        case 'unique':
                            $check_duplicate = $this->_db->get($rule_value, [$item, '=', $field_value])->count();
                            if ( $check_duplicate ) {
                                $this->addError( "{$item} already exists. <br/>" );
                            }
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }
            } // end innder foreach
        } // end foreach
        
        if ( empty( $this->_erros ) ) {
            $this->_passed = true;
        }
        
        return $this;
    }
    
    public function passed() {
        return $this->_passed;
    }
    
    public function addError(string $error): void
    {
        $this->_erros[] = $error; 
    }
    
    public function errors() {
        return $this->_erros;
    }
    
}