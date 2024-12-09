<?php
use Modules\VehicleManagement\Entities\VehicleCategory;
$categories = VehicleCategory::all();
foreach($categories as $category){
    echo $category->name.'<br>';
}
?>
