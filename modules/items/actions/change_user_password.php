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

  if($current_entity_id!=1)
  {
    redirect_to('dashboard/access_forbidden');
  }

  if(!users::has_access('update'))
  {
    redirect_to('dashboard/access_forbidden');
  }


  switch($app_module_action)
  {
    case 'change':
        
        $password = $_POST['password_new'];
        $password_confirm = $_POST['password_confirmation'];
        
        $error = false;
        
        if($password!=$password_confirm)
        {
          $error = true;
          $alerts->add(TEXT_ERROR_PASSOWRD_CONFIRMATION,'error');
        }
        
         if(strlen($password)<CFG_PASSWORD_MIN_LENGTH)
        {
          $error = true;
          $alerts->add(TEXT_ERROR_PASSOWRD_LENGTH,'error');
        }
        
        if(CFG_IS_STRONG_PASSWORD)
        {
            if(!preg_match('/[A-Z]/', $password) or !preg_match('/[0-9]/', $password) or !preg_match('/[^\w]/', $password))
            {                
                $error = true;
                $alerts->add(TEXT_STRONG_PASSWORD_TIP, 'error');
            }
        }
        
        
        
        if(!$error)
        {
          $hasher = new PasswordHash(11, false);
          
          $sql_data = array();  
          $sql_data['password']=$hasher->HashPassword($password);
          
          db_perform('app_entity_1',$sql_data,'update',"id='" . db_input($current_item_id). "'");
          
          $obj = db_find('app_entity_1',$current_item_id);
          
          $options = array('to' => $obj['field_9'],
                 'to_name' => $obj['field_7'] . ' ' . $obj['field_8'],
                 'subject'=>TEXT_USER_PWD_CHANGED_EMAIL_SUBJECT,
                 'body'=>TEXT_USER_PWD_CHANGED_EMAIL_BODY . '<p><b>' . TEXT_LOGIN_DETAILS . '</b></p><p>' . TEXT_USERNAME .': ' . $obj['field_12'] . '<br>' . TEXT_PASSWORD . ': ' . $password . '</p><p><a href="' . url_for('users/login','',true) . '">' . url_for('users/login','',true). '</a></p>',
                 'from'=> $app_user['email'],
                 'from_name'=>'noreply' );
                 
          users::send_email($options);
          
          $alerts->add(TEXT_USER_PASSWORD_UPDATED,'success');
        }
        
        
        redirect_to('items/change_user_password','path=' . $current_path);
      break;
  }