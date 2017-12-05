<?php
if (isset($_POST['couponcode']))
    { apply_coupon($_POST['couponcode']); }; 

function apply_coupon($couponcode) { 
	echo "sdsds";exit();
    global $woocommerce; WC()->cart->remove_coupons();
    $ret = WC()->cart->add_discount( $couponcode ); 
    $array = array('return' => $ret); print_r(json_encode($array)); 
}
exit;
 ?>