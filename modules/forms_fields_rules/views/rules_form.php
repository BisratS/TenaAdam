<?php
/**
 * Этот файл является частью программы "CRM Руководитель" - конструктор CRM систем для бизнеса
 * https://www.rukovoditel.net.ru/
 * 
 * CRM Руководитель - это свободное программное обеспечение, 
 * распространяемое на условиях GNU GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
 * 
 * Автор и правообладатель программы: Харчишина Ольга Александровна (RU), Харчишин Сергей Васильевич (RU).
 * Государственная регистрация программы для ЭВМ: 2023664624
 * https://fips.ru/EGD/3b18c104-1db7-4f2d-83fb-2d38e1474ca3
 */
?>

<?php echo ajax_modal_template_header(TEXT_RULE_FOR_FIELD) ?>

<?php echo form_tag('rules_form', url_for('forms_fields_rules/rules','action=save&entities_id=' . $_GET['entities_id'] . (isset($_GET['id']) ? '&id=' . $_GET['id']:'') ),array('class'=>'form-horizontal')) ?>
<div class="modal-body">
  <div class="form-body ajax-modal-width-790">
      
      <div class="form-group">
	<label class="col-md-3 control-label" for="is_active"><?php echo TEXT_IS_ACTIVE ?></label>
        <div class="col-md-9">	
              <p class="form-control-static"><?php echo input_checkbox_tag('is_active',$obj['is_active'],array('checked'=>($obj['is_active']==1 ? 'checked':''))) ?></p>
        </div>			
      </div>
  
<?php 
$choices = array();
$fields_query = db_query("select f.*, t.name as tab_name from app_fields f, app_forms_tabs t where f.type in ('fieldtype_entity','fieldtype_entity_ajax','fieldtype_entity_multilevel','fieldtype_dropdown','fieldtype_dropdown_multiple','fieldtype_checkboxes','fieldtype_radioboxes','fieldtype_user_accessgroups','fieldtype_grouped_users','fieldtype_boolean_checkbox','fieldtype_boolean','fieldtype_autostatus','fieldtype_stages','fieldtype_color') and f.entities_id='" . _get::int('entities_id') . "' and f.forms_tabs_id=t.id order by t.sort_order, t.name, f.sort_order, f.name");
while($v = db_fetch_array($fields_query))
{
	$choices[$v['id']] = fields_types::get_option($v['type'],'name',$v['name']); 
}
?>

  <div class="form-group">
  	<label class="col-md-3 control-label" for="name"><?php echo TEXT_SELECT_FIELD ?></label>
    <div class="col-md-9">	
  	  <?php echo select_tag('fields_id',$choices,$obj['fields_id'],array('class'=>'form-control input-medium required ','onChange'=>'get_fields_choices()')) ?>
  	  <?php echo tooltip_text(TEXT_AVAILABLE_FIELDS . ': ' . TEXT_FIELDTYPE_DROPDOWN_TITLE . ', ' . TEXT_FIELDTYPE_RADIOBOXES_TITLE . ', ' . TEXT_FIELDTYPE_CHECKBOXES_TITLE) ?>
    </div>			
  </div>  
  
	<div id="fields_choices"></div>  
   
        
    <div class="form-group">
  	<label class="col-md-3 control-label" for="sort_order"><?php echo TEXT_SORT_ORDER ?></label>
        <div class="col-md-9">	
              <?php echo input_tag('sort_order',$obj['sort_order'],array('class'=>'form-control input-xsmall')) ?>
        </div>			
    </div>     
   </div>
</div>

<?php echo ajax_modal_template_footer() ?>

</form> 

<script>
	function get_fields_choices()
	{		
		fields_id = $('#fields_id').val();

		$('#fields_choices').html('<div class="ajax-loading"></div>');
			
		$('#fields_choices').load('<?php echo url_for('forms_fields_rules/rules','action=get_fields_choices&entities_id=' . $_GET['entities_id'] . '&id=' . $obj['id'])?>&fields_id='+fields_id, function(response, status, xhr){

                    if (status == "error") {                                 
                        $(this).html('<div class="alert alert-error"><b>Error:</b> ' + xhr.status + ' ' + xhr.statusText+'<div>'+response +'</div></div>')                    
                    }
                    else
                    {	    		    
                        appHandleChosen()
                        
                        apply_fields_by_tab()

                        jQuery(window).resize();      
                    }			 	
                })
	}
        
        function apply_fields_by_tab()
        {            
            $('.apply-fields-by-tab').click(function(){
                data = $(this).data()                                
                $('#'+data.applyTo).val(data.fields.split(',')).trigger("chosen:updated")
            })
        }
	
  $(function() {
  	$('#rules_form').validate({ignore:''});       
        
        get_fields_choices();                                                                
        
        
  });
  
</script>   
    
 
