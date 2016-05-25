<?php

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
class Date extends Field{
    
    public function tableData($table)
    {
        $table->timestamp($this->getName());
        return $table;
    }
    
    public function render($row)
    {
        $name  = $this->getName();
        if(isset($row->$name))
        {
            $date  = preg_split("[-| ]", $row->$name);
            $year  = ($date[0] != '0000')? $date[0]: 0;
            $month = ($date[1] != '00')  ? $date[1]: 0;
            $day   = ($date[2] != '00')  ? $date[2]: 0;
        }
        else
        {
            $day   = 0;
            $month = 0;
            $year  = 0;
        }
        
        $dayList = array('Dia');
        for($i = 1; $i <= 31; $i++) 
        {
            $aux = ($i < 10)? '0'.$i: $i;
            $dayList[$aux] = $aux;
        }
        $monthList = array('Mês');
        for($i = 1; $i <= 12; $i++) 
        {
            $aux = ($i < 10)? '0'.$i: $i;
            $monthList[$aux] = $aux;
        }
        $yearList = array('Ano');
        for($i = date('Y'); 1900 <= $i; $i--) 
        {
            $yearList[$i] = $i;
        }
        
        $required = '';
        if($this->getRequired())
           $required = '<span class="required">*</span>';
        
        $subTitle = '';
        if($this->getSubTitle())
            $subTitle = '<span class="subtitle">'.$this->getSubTitle().'</span>';
        
        return '<div class="form-group">
                    '.Form::label($this->getName(), $this->getTitle()).'
                    '.$required.'
                    '.$subTitle.'
                    <div class="container marketing date-field">
                        <div class="col-md-4" style="padding-left: 0px">
                            '.Form::select($this->getName().'-day', $dayList, $day,  array('class' => 'form-control')).'
                        </div>
                        <div class="col-md-4" style="padding: 0px">
                            '.Form::select($this->getName().'-month', $monthList, $month,  array('class' => 'form-control')).'
                        </div>
                        <div class="col-md-4" style="padding-right: 0px">
                            '.Form::select($this->getName().'-year', $yearList, $year,  array('class' => 'form-control')).'
                        </div>
                    </div>
                </div>';
    }
    
    public function treatmentValue($row)
    {
        $name  = $this->getName();
        $value = $row->$name;
        if($value != '0000-00-00 00:00:00')
        {
            return date('d/m/Y', strtotime($value));
        }
        
        return '-';
    }
    
    public function run() 
    {
        $day   = Input::get($this->getName().'-day');
        $month = Input::get($this->getName().'-month');
        $year  = Input::get($this->getName().'-year');
        
        if(!$day)   $day = '00';
        if(!$month) $month = '00';
        if(!$year)  $year = '0000';
        
        return "{$year}-{$month}-{$day} 00:00:00";
    }
    
    /**
     * funçao que verifica se o campo foi preenchido
     * 
     * @param type $value
     * @return boolean
     */
    public function requiredFieldIsValid()
    {
        //verifica se o campo é obrigatório e foi preenchido
        $value = $this->loadValue();
        if($value != '0000-00-00 00:00:00')
        {
            $return = array_intersect(array('0000', '00'), preg_split("[-| ]", $value));
            return empty($return);
        }
        return false;
    }
}
