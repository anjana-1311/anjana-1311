<?php
require_once('../inc/php/config.php');
require_once('../inc/dal/baseclasses/class.database.php');
require_once('../inc/dal/city.child.php');

$options = '';  
$cityId = $_REQUEST['cityId'];
$stateId = $_REQUEST['stateId'];
$action = $_REQUEST['action'];
if(isset($_REQUEST['stateId']) && !empty($_REQUEST['stateId']))
{
        $id = $_REQUEST['stateId'];
        
        $paramArray = array($id);
	$objcityChild = new cityChild();	
	$objcityChild->selectColumn = 'id,name';
        $objcityChild->param = $paramArray;
	$objcityChild->condition  = 'state_id=?';
	$fetchCityData = $objcityChild->selectByColumn();
        $numRow = $objcityChild->numRows;
//        echo "error >>".$objcityChild->error;
        if($numRow > 0)
        {  
            $selected = '';
            $options .= "<option value=''>Select City</option>";
            foreach ($fetchCityData as $cityData)
            {
                if ($cityId == $cityData['id']) 
                {
                    $selected = 'selected="selected"';
                }
                else
                {
                    $selected = "";
                }
                $options .= '<option  value="'.$cityData['id'].'" '.$selected.'>'.$cityData['name'].'</option>';
            } // foreach end here
        } // numrow if end here
        else 
        {
            $options .= "<option value='' >--No City--</option>";
        }
        echo $options;
	
}
else
{
    echo $options .= "<option value=''>Select City</option>";
}

?>
