<?php
require_once('../inc/php/config.php');
require_once('../inc/dal/baseclasses/class.database.php');
require_once('../inc/dal/state.child.php');
require_once('../inc/dal/city.child.php');

$countryId = $_REQUEST['countryId'];
$stateId = $_REQUEST['stateId'];
$action = $_REQUEST['action'];
$html = '';
if(isset($_REQUEST['countryId']) && !empty($_REQUEST['countryId']))
{ 
    $id = $_REQUEST['countryId'];
    
    $paramArray = array($id);
    $objstateChild = new stateChild();
    $objstateChild->selectColumn = 'id,name';
    $objstateChild->param  = $paramArray;
    $objstateChild->condition = 'country_id='.$id;
    $fetchStateData = $objstateChild->selectByColumn();
    $numRow = $objstateChild->numRows;
   
    if($numRow > 0)
    {
        $options ='';
        $selected = '';
        $options .= "<option value=''>Select State</option>";
        foreach($fetchStateData as $stateData)
        {                   
            if ($stateId == $stateData['id']) 
            {
                $selected = 'selected="selected"';
            }
            else
            {
                $selected = "";
            }
           
            $options .= '<option  value="'.$stateData[id].'" '.$selected.'>'.$stateData[name].'</option>';
        }
    }
    else 
    {
        $options .= "<option value='' >--No State--</option>";
    }
     echo $options;
}
else
{
    echo $options .= "<option value=''>Select State</option>";
}
?>
