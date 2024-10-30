<?php 
	global $c4d_plugin_manager;
	$selectbox = array();
	$options = isset($c4d_plugin_manager['c4d-woo-cpp-select-box']) && $c4d_plugin_manager['c4d-woo-cpp-select-box'] != '' ? $c4d_plugin_manager['c4d-woo-cpp-select-box'] : '3,6,9,12,15';
	$selected = (isset($c4d_plugin_manager['c4d-woo-cpp-default']) && $c4d_plugin_manager['c4d-woo-cpp-default'] != '') ? $c4d_plugin_manager['c4d-woo-cpp-default'] : wc_get_default_products_per_row() * wc_get_default_product_rows_per_page();

	$options = explode( ',', $options);
	if (is_array($options)) {
		foreach ($options as $key => $value) {
			$selectbox[] = array('text' => $value, 'value' => $value);
		}
	}
	$selected = isset($_GET['product_perpage']) ? esc_attr($_GET['product_perpage']) : $selected;
?>
<form class="c4d-woo-cpp-form" action="">
	<select name="product_perpage">
		<?php foreach ($selectbox as $option): ?>
			<?php 
				$checked = '';
				if ($selected == $option['value']) {
					$checked = 'selected="selected"';
				}
			?>
			<option <?php echo $checked; ?> value="<?php esc_attr_e($option['value']); ?>"><?php echo $option['text']; ?></option>
		<?php endforeach; ?>
	</select>
</form>