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
class Cep extends Field
{
	var $data = array();
	
	public function __construct()
	{
		$this->data = array('id' => $this->getName(), 'placeholder' => '');
	}
	
    public function references($bairro = null, $logradouro = null, $uf = null, $localidade = null, $cep = null)
	{
		if($bairro) $this->data['bairro'] = $bairro;
		if($logradouro) $this->data['logradouro'] = $logradouro;
		if($uf) $this->data['uf'] = $uf;
		if($localidade) $this->data['localidade'] = $localidade;
		if($cep) $this->data['cep'] = $cep ;
	}
	
    public function tableData($table)
    {
        $table->string($this->getName(), $this->getSize());
        return $table;
    }
    
    public function render($row)
    {
        $value = '';
        if($row)
        {
            $name = $this->getName();
            $value = $row->$name;
        }
        else if($this->getValue())
        {
            $value = $this->getValue();
        }
        
        $required = '';
        if($this->getRequired())
           $required = '<span class="required">*</span>';
        
        $subTitle = '';
        if($this->getSubTitle())
            $subTitle = '<span class="subtitle">'.$this->getSubTitle().'</span>';
        
		$this->data['mask']     = '99999-999';
        $this->data['class']    = 'form-control mask';
        $this->data['onchange'] = 'searchByAddress(this)';
		
        return '<div class="form-group">
                    '.Form::label($this->getName(), $this->getTitle())
                     .$required
                     .$subTitle
                     .Form::text($this->getName(), $value,  $this->data).'
					 <a href="http://m.correios.com.br/movel/buscaCep.do" target="__blank">Não sei o cep</a>
                </div>
				<script type="text/javascript">
					function searchByAddress(obj)
					{
						obj 	  = $(obj);
						var value = obj.val();
						if(value.length === 9)
						{
							$.fancybox("Aguarde...", {
									closeBtn : false,
									closeClick : false,
									helpers : { 
										overlay : {
											closeClick: false
										} // prevents closing when clicking OUTSIDE fancybox
									},
									keys : {
										close: null
									}
							});
							
							value = value.replace("-", "");
							$.post( 
								baseURL+"/ajax/cep", 
								{cep:value},
								function(data) 
								{
									var bairro 	   = obj.attr("bairro");
									var logradouro = obj.attr("logradouro");
									var uf		   = obj.attr("uf");
									var localidade = obj.attr("localidade");
									var cep 	   = obj.attr("cep");
									
									if(bairro != undefined)
									{
										$("input[name="+bairro+"]").val(data.bairro);
									}
									if(logradouro != undefined)
									{
										$("input[name="+logradouro+"]").val(data.logradouro);
									}
									if(uf != undefined)
									{
										$("input[name="+uf+"]").val(data.uf);
									}
									if(localidade != undefined)
									{
										$("input[name="+localidade+"]").val(data.localidade);
									}
									if(cep != undefined)
									{
										$("input[name="+cep+"]").val(data.cep);
									}
									
									$.fancybox.close();
								},
								"json"
							);
						}
					}
				</script>';
    }
}
