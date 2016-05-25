<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image
 *
 * @author Alex
 */
class Imagebox extends Field{
    
   
	var $path = null;
	
    public function tableData($table)
    {
        $table->text($this->getName());
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
        
		$value = '';
		if($row)
		{
			$name  = $this->getName();
			$value = $row->$name;
		}
		else if($this->getValue())
		{
			$value = $this->getValue();
		}

		$html = '<ul class="lista-imagens">';
		$images = json_decode($value);
		if(!empty($images))
		{
			foreach($images as $i => $image)
			{
				$html .= '<li class="float-l" id="image-'.($i + 1).'"><a class="remover" href="javascript:responsive_filemanager_remove('.($i + 1).')">remover</a><br /><img src="'.URL::to("imagem.php?p={$image}&w=100&h=100").'" data="'.$image.'" class="'.$this->getName().'-img"/></li>';
			}
		}
		$html .= '<li class="mais-imagem" 	id="'.$this->getName().'-imgs"><a href="bawer/tinymce_4.1.9/plugins/filemanager/dialog.php?type=1&field_id='.$this->getName().'-url" class="iframe-btn" type="button"><img src="'.URL::to('bawer/xgen/mais-imagens.jpg').'"/></a></li></ul>';
		
	 	return '<div class="form-group">
                    '.Form::label($this->getName(), $this->getTitle())
                     .$required
                     .$subTitle
                     .'<input name="'.$this->getName().'"     id="'.$this->getName().'" type="hidden" value=\''.$value.'\' id="'.$this->getName().'"/>
					   <input name="'.$this->getName().'-url" id="'.$this->getName().'-url" type="hidden"/>
					   <div class="width-100-l">'.$html.'</div>
                </div>
				
				<script type="text/javascript">
					 var baseImg = "'.URL::asset('/').'";
					 $(function(){
						$(".iframe-btn").fancybox({
							  "width"	 : "880px",
							  "height"	 : "570px",
							  "type"	 : "iframe",
							  "autoScale": false
						});
					 });
					 
					 function responsive_filemanager_callback(field_id)
					 {
					 	var i = $(".lista-imagens li").length;
						var url = $("#"+field_id).val();
						url = url.replace(baseImg, "");
						$("#'.$this->getName().'-imgs").before(\'<li class="float-l" id="image-\'+i+\'"><a class="remover" href="javascript:responsive_filemanager_remove(\'+i+\')">remover</a><br /><img src="\'+baseImg+\'imagem.php?p=\'+url+\'&w=100&h=100" data="\'+url+\'" class="'.$this->getName().'-img"/></li>\');
						var imgs = [];
						$(".'.$this->getName().'-img").each(function(i, data){
							imgs.push($(data).attr("data"));
						});
						$("#'.$this->getName().'").val(JSON.stringify(imgs));
					 }
					 
					 function responsive_filemanager_remove(data)
					 {
					 	$("#image-"+data).remove();
						var imgs = [];
						$(".'.$this->getName().'-img").each(function(i, data){
							imgs.push($(data).attr("data"));
						});
						$("#'.$this->getName().'").val(JSON.stringify(imgs));
					 }
				</script>';
    }
    
    public function treatmentValue($row)
    {
        $name = $this->getName();
        if($row->$name)
        {
			$images = json_decode(str_replace('\'', '"', $row->$name));
            if(!empty($images))
            {
                return '<img src="'.URL::to("imagem.php?p={$images[0]}&w=100&h=100").'"/>';
            }
        }
    }
}
