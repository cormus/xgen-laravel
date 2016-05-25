<?php
use Cartalyst\Sentry\Hashing\Sha256Hasher;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Text
 *
 * @author Alex
 */
class Password extends Field{
    
    
    public function tableData($table)
    {
        $table->string($this->getName(), $this->getSize());
        return $table;
    }
    
    public function render($row)
    {
        $required = '';
        if($this->getRequired())
           $required = '<span class="required">*</span>';
        
        $subTitle = '';
        if($this->getSubTitle())
            $subTitle = '<span class="subtitle">'.$this->getSubTitle().'</span>';
        
        
        $data = array
        (
            'id'          => $this->getName(), 
            'placeholder' => '',
            'class'       => 'form-control'
        );
        
        return '<div class="form-group">
                    '.Form::label($this->getName(), $this->getTitle())
                     .$required
                     .$subTitle
                     .Form::password($this->getName(), $data).'
                </div>';
    }
    
    public function run() {
        $value = Input::get($this->getName());
        if($value)
        {
            //cria o rash do sentry para ser salvo no banco de dados
            $Sha256Hasher = new Sha256Hasher();
            return $Sha256Hasher->hash($value);
        }
        return null;
    }
}
