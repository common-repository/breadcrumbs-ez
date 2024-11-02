<div class="wrap">
<h2>Breadcrumbs EZ</h2>
<p>
Add the code to your theme: echo do_shortcode('[breadcrumbs]');<br /><br />
Alternatively, you can also use the [breadcrumbs] shortcode if implementing in a single post or page.<br />
</p>
<form method="post" action="options.php">
<?php 
wp_nonce_field('update-options');
settings_fields('ezcrumbs_settings-group');
$ezcrumbs_options = get_option('ezcrumbs_settings');
 ?>
<style>.ezcrumbs_td{width:60%;}</style>		
<table class="form-table">

<tr valign="top">
<th scope="row">Delimiter:</th>
<td><input type="text" name="ezcrumbs_settings[delimiter]" value="<?php echo ezcrumbs_d2s($ezcrumbs_options['delimiter']); ?>" /></td>
<td class="ezcrumbs_td">Delimiter between two levels</td>
</tr>


<tr valign="top">
<th scope="row">Before:</th>
<td><input type="text" name="ezcrumbs_settings[before]" value="<?php echo ezcrumbs_d2s($ezcrumbs_options['before']); ?>" /></td>
<td class="ezcrumbs_td">Code before the breadcrumb section</td>
</tr>

<tr valign="top">
<th scope="row">After:</th>
<td><input type="text" name="ezcrumbs_settings[after]" value="<?php echo ezcrumbs_d2s($ezcrumbs_options['after']); ?>" /></td>
<td class="ezcrumbs_td">Code after the breadcrumb section</td>
</tr>

<tr valign="top">
<th scope="row">Before Current:</th>
<td><input type="text" name="ezcrumbs_settings[before_current]" value="<?php echo ezcrumbs_d2s($ezcrumbs_options['before_current']); ?>" /></td>
<td class="ezcrumbs_td">Code before the current crumb</td>
</tr>

<tr valign="top">
<th scope="row">After Current:</th>
<td><input type="text" name="ezcrumbs_settings[after_current]" value="<?php echo ezcrumbs_d2s($ezcrumbs_options['after_current']); ?>" /></td>
<td class="ezcrumbs_td">Code after the current crumb</td>
</tr>

<tr valign="top">
<th scope="row">Show Home:</th>
<td><input type="text" name="ezcrumbs_settings[show_home]" value="<?php echo ezcrumbs_d2s($ezcrumbs_options['show_home']); ?>" /></td>
<td class="ezcrumbs_td">Show/hide the home link</td>
</tr>

<tr valign="top">
<th scope="row">Home:</th>
<td><input type="text" name="ezcrumbs_settings[home]" value="<?php echo ezcrumbs_d2s($ezcrumbs_options['home']); ?>" /></td>
<td class="ezcrumbs_td">Text for the 'Home' link</td>
</tr>



</tr>

</table>

<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
function ezcrumbs_d2s($st){
	return str_replace('"',"'",$st);
}