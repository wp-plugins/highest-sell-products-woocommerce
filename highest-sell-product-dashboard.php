<?php
/*
Plugin Name: Highest Sell Products Woocommerce
Plugin URI: http://odrasoft.com/
Description:  Highest Sells product woocommerce Dashboard Widget Shows the No of Heighest sell Product On the Dashboard .
Version: 0.1
Author: swadeshswain
Author URI: http://odrasoft.com/
License: GPLv2 or later
*/
add_action('wp_dashboard_setup', 'od_woo_sell_dashboard_widgets');
function od_woo_sell_dashboard_widgets() {
global $wp_meta_boxes;
wp_add_dashboard_widget('od_woo_sell_widget', 'WooCommerce Top Sell Product', 'od_sell_dashboard_woo');
}
function od_sell_dashboard_woo() {
global $wpdb;
global $woocommerce ;
$od_woo_pro_no = get_option( 'od_woo_pro_no' );
?>
  <?php
    if ( isset($_POST['submit']) ) { 
        $nonce = $_REQUEST['_wpnonce'];
        if (! wp_verify_nonce($nonce, 'php-woo-od-updatesettings' ) ) {
            die('security error');
        }
        $woo_pro_no = $_POST['woo_pro_no'];
        update_option( 'od_woo_pro_no', $woo_pro_no );
    } 
    $od_woo_pro_no = get_option( 'od_woo_pro_no' );
	?>
<?php
$args = array(
'post_type' => 'product',
'posts_per_page' => $od_woo_pro_no,
'meta_key' => 'total_sales',
'orderby' => 'meta_value_num',
);

$loop = new WP_Query( $args );
if ( $loop->have_posts() ) {
?>
<table class="shop_table my_account_orders" width="100%">

		<thead>
			<tr>
				<td class="product-number"><span class="nobr"><b><?php _e( 'Product Id', 'woocommerce' ); ?></b></span></td>
                <td class="product-image"><span class="nobr"><b><?php _e( 'Image', 'woocommerce' ); ?></b></span></td>
				<td class="product-name"><span class="nobr"><b><?php _e( 'Product Name', 'woocommerce' ); ?></b></span></td>
				<td class="product-price"><span class="nobr"><b><?php _e( 'Price', 'woocommerce' ); ?></b></span></td>
				<td class="product-total"><span class="nobr"><b><?php _e( 'Total Sale', 'woocommerce' ); ?></b></span></td>
				<td class="product-actions"><span class="nobr"><b><?php _e( 'Action', 'woocommerce' ); ?></b></td>
			</tr>
		</thead>
        <tbody>
<?php
while ( $loop->have_posts() ) : $loop->the_post();
//woocommerce_get_template_part( 'content', 'product' );
?><tr class="order">
<td><a href="<?php echo get_permalink() ; ?>" ><?php the_ID() ; ?></a> </td>
<td>
<?php $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>
<img src="<?php echo $url; ?>" height="50" width="50" />
</td>
<td><a href="<?php echo get_permalink() ; ?>" ><?php the_title() ; ?> </a></td>
<td>
<?php //echo $price[0] ; 
$price = get_post_meta( get_the_ID(), '_regular_price');
$sale = get_post_meta( get_the_ID(), '_sale_price');
if($sale[0] !=""){
echo get_woocommerce_currency_symbol() . $sale[0] ;
}
else
{
echo get_woocommerce_currency_symbol() .$price[0] ;
}
 ?>
 </td>
 <td>
 <?php
 $units_sold = get_post_meta( get_the_ID(), 'total_sales', true );
 echo '<p>' . sprintf( __( 'Units Sold: %s', 'woocommerce' ), $units_sold ) . '</p>';
 ?>
 </td>
 <td>
 <a href="<?php echo get_permalink() ; ?>" class="button">View</a>
 
 </td>
</tr>
<?php
endwhile;
?>
</tbody>
	</table>
<div style="border-top: 1px solid #000;">

			<form method="post" action="" id="php_config_page">
				<?php wp_nonce_field('php-woo-od-updatesettings'); ?>                          
				<table class="form-table">
					<tbody>
                    <tr>
						<th><label>No Of Product to Display : </label></th>
						<td>
                                         <Input type = 'text' Name ='woo_pro_no' <?php if($od_woo_pro_no!=""){?>value= '<?php echo $od_woo_pro_no ; ?>' <?php } else { ?> value = '5' <?php } ?> />
                        </td>
                    </tr>
					</tbody>
				</table>
				<p class="submit"><input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit" /></p>  
			</form>
</div>
<?php
 }else {
echo __( 'No products found' );
}
wp_reset_query();

?> 
<?php 
}?>