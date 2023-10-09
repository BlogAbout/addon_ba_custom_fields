<?php
/**
 * @version 0.1.2
 * @author А.П.В.
 * @package ba_custom_fields for Jshopping
 * @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
 * @license GNU/GPL
 **/
defined('_JEXEC') or die('Restricted access');

\JSFactory::loadExtLanguageFile('ba_custom_fields');

$params = (object)$this->params;
$yes_no_options = array();
$yes_no_options[] = \JHtml::_('select.option', '1', \JText::_('JYES'));
$yes_no_options[] = \JHtml::_('select.option', '0', \JText::_('JNO'));

$field_type = array(
    0 => _JSHOP_BACF_FIELD_TYPE,
    'input' => _JSHOP_BACF_FIELD_TYPE_INPUT,
    'number' => _JSHOP_BACF_FIELD_TYPE_NUMBER,
    'tel' => _JSHOP_BACF_FIELD_TYPE_TEL,
    'email' => _JSHOP_BACF_FIELD_TYPE_EMAIL,
    'link' => _JSHOP_BACF_FIELD_TYPE_LINK,
    'area' => _JSHOP_BACF_FIELD_TYPE_AREA,
    'editor' => _JSHOP_BACF_FIELD_TYPE_EDITOR,
    'radio' => _JSHOP_BACF_FIELD_TYPE_RADIO,
    'checkbox' => _JSHOP_BACF_FIELD_TYPE_CHECKBOX,
    'select' => _JSHOP_BACF_FIELD_TYPE_SELECT,
    'combobox' => _JSHOP_BACF_FIELD_TYPE_COMBOBOX,
    'image' => _JSHOP_BACF_FIELD_TYPE_IMAGE,
    'gallery' => _JSHOP_BACF_FIELD_TYPE_GALLERY,
    'youtube' => _JSHOP_BACF_FIELD_TYPE_YOUTUBE,
    'calendar' => _JSHOP_BACF_FIELD_TYPE_CALENDAR
);

$position_product = array(
    0 => _JSHOP_BACF_POSITION_PRODUCT,
    '_tmp_product_html_start' => _JSHOP_BACF_POSITION_PRODUCT_START,
    '_tmp_product_html_before_image' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_IMAGE,
    '_tmp_product_html_body_image' => _JSHOP_BACF_POSITION_PRODUCT_BODY_IMAGE,
    '_tmp_product_html_after_image' => _JSHOP_BACF_POSITION_PRODUCT_AFTER_IMAGE,
    '_tmp_product_html_before_image_thumb' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_IMAGE_THUMB,
    '_tmp_product_html_after_image_thumb' => _JSHOP_BACF_POSITION_PRODUCT_AFTER_IMAGE_THUMB,
    '_tmp_product_html_after_video' => _JSHOP_BACF_POSITION_PRODUCT_AFTER_VIDEO,
    '_tmp_product_html_before_atributes' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_ATRIBUTES,
    '_tmp_product_html_after_atributes' => _JSHOP_BACF_POSITION_PRODUCT_AFTER_ATRIBUTES,
    '_tmp_product_html_after_freeatributes' => _JSHOP_BACF_POSITION_PRODUCT_AFTER_FREEATRIBUTES,
    '_tmp_product_html_before_price' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_PRICE,
    '_tmp_var_bottom_price' => _JSHOP_BACF_POSITION_PRODUCT_BOTTOM_PRICE,
    '_tmp_var_bottom_allprices' => _JSHOP_BACF_POSITION_PRODUCT_BOTTOM_ALLPRICES,
    '_tmp_product_html_after_ef' => _JSHOP_BACF_POSITION_PRODUCT_AFTER_EF,
    '_tmp_product_html_before_buttons' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_BUTTONS,
    '_tmp_product_html_buttons' => _JSHOP_BACF_POSITION_PRODUCT_BUTTONS,
    '_tmp_product_html_after_buttons' => _JSHOP_BACF_POSITION_PRODUCT_AFTER_BUTTONS,
    '_tmp_product_html_before_demofiles' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_DEMOFILES,
    '_tmp_product_html_before_review' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_REVIEW,
    '_tmp_product_review_before_submit' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_SUBMIT,
    '_tmp_product_html_before_related' => _JSHOP_BACF_POSITION_PRODUCT_BEFORE_RELATED,
    '_tmp_product_html_end' => _JSHOP_BACF_POSITION_PRODUCT_END
);

$position_category = array(
    0 => _JSHOP_BACF_POSITION_CATEGORY,
    '_tmp_var_start' => _JSHOP_BACF_POSITION_CATEGORY_VAR_START,
    '_tmp_var_image_block' => _JSHOP_BACF_POSITION_CATEGORY_IMAGE_BLOCK,
    '_tmp_var_bottom_foto' => _JSHOP_BACF_POSITION_CATEGORY_BOTTOM_FOTO,
    '_tmp_var_bottom_old_price' => _JSHOP_BACF_POSITION_CATEGORY_BOTTOM_OLD_PRICE,
    '_tmp_var_bottom_price' => _JSHOP_BACF_POSITION_CATEGORY_BOTTOM_PRICE,
    '_tmp_var_top_buttons' => _JSHOP_BACF_POSITION_CATEGORY_TOP_BUTTONS,
    '_tmp_var_buttons' => _JSHOP_BACF_POSITION_CATEGORY_BUTTONS,
    '_tmp_var_bottom_buttons' => _JSHOP_BACF_POSITION_CATEGORY_BOTTOM_BUTTONS,
    '_tmp_var_end' => _JSHOP_BACF_POSITION_CATEGORY_END
);

$db = \JFactory::getDbo();
$query = "
	SELECT *
	FROM `#__jshopping_custom_fields`
	ORDER BY `ordering`
";
$db->setQuery($query);
$fields_list = $db->loadObjectList();

$standart_fields = '
	<tr class="control-dinamic">
		<td>
			<input type="hidden" name="dinamic_field[field_id][]" value="0" />
			<div class="btn-group">
				<a class="remove btn button btn-danger" aria-label="' . _JSHOP_BACF_FIELD_REMOVE . '"><span class="icon-minus" aria-hidden="true"></span></a>
			</div>
		</td>
		<td>' . \JHtml::_('select.genericlist', $field_type, 'dinamic_field[field_type][]', 'class = "inputbox form-control form-select"', 'value', 'text', 0) . '</td>
		<td><input type="text" name="dinamic_field[title_admin][]" value="" placeholder="' . _JSHOP_BACF_TITLE_ADMIN . '" title="' . _JSHOP_BACF_TITLE_ADMIN_DESC . '" class="inputbox form-control form-input" style="width: 190px;" /></td>
		<td><input type="text" name="dinamic_field[title_site][]" value="" placeholder="' . _JSHOP_BACF_TITLE_SITE . '" title="' . _JSHOP_BACF_TITLE_SITE_DESC . '" class="inputbox form-control form-input" style="width: 190px;" /></td>
		<td><input type="number" min="0" step="1" name="dinamic_field[ordering][]" value="" placeholder="' . _JSHOP_BACF_ORDER . '" title="' . _JSHOP_BACF_ORDER_DESC . '" class="inputbox form-control form-input" style="width: 60px;" /></td>
		<td><textarea name="dinamic_field[values_list][]" placeholder="' . _JSHOP_BACF_VALUES . '" title="' . _JSHOP_BACF_VALUES_DESC . '" class="inputbox form-control form-textarea"></textarea></td>
		<td>' . \JHtml::_('select.genericlist', $position_product, 'dinamic_field[position_product][]', 'class = "inputbox form-control form-select"', 'value', 'text', 0) . '</td>
		<td>' . \JHtml::_('select.genericlist', $position_category, 'dinamic_field[position_category][]', 'class = "inputbox form-control form-select"', 'value', 'text', 0) . '</td>
		<td>' . \JHtml::_('select.genericlist', $yes_no_options, 'dinamic_field[multilang[][]', 'class="inputbox form-control form-select form-select-color-state" style="width: 120px;"', 'value', 'text', 0) . '</td>
	</tr>
';

$style = "
	.jshop_edit .controls {
		display: block;
	}
";

$script = "
	jQuery(function($) {
		$(document)
			.on('click', 'div.ba_custom_fields a.add', function(e) {
				e.preventDefault();
				var new_elem = '" . str_replace("\n", '', $standart_fields) . "';
				$('table.ba_list_fields tbody').append(new_elem);
				$('.control-dinamic select').each(function() {
					$(this).chosen(\"updated\")
				});
			})
			.on('click', '.control-dinamic a.remove', function(e) {
				e.preventDefault();
				var elem = $(this).parents('.control-dinamic');
				elem.remove();
			});
	});
";

\JFactory::getDocument()->addStyleDeclaration($style);
\JFactory::getDocument()->addScriptDeclaration($script);
?>
<fieldset class="form-horizontal">
    <legend><?php echo _JSHOP_BACF_NAME; ?></legend>

    <div class="control-group">
        <div class="control-label">
            <label class="hasTooltip" title="<?php echo _JSHOP_BACF_NAME_ENABLE_DESC; ?>"><?php echo _JSHOP_BACF_NAME_ENABLE; ?></label>
        </div>

        <div class="controls">
            <?php echo \JHtml::_('select.genericlist', $yes_no_options, 'params[enable]', 'class="inputbox form-control form-select form-select-color-state"', 'value', 'text', (isset($params->enable) ? $params->enable : 1)); ?>
        </div>
    </div>

    <legend><?php echo _JSHOP_BACF_LIST_FIELDS; ?></legend>

    <table class="admintable ba_list_fields">
        <thead>
        <th></th>
        <th title="<?php echo _JSHOP_BACF_FIELD_TYPE_DESC; ?>"><?php echo _JSHOP_BACF_FIELD_TYPE; ?></th>
        <th title="<?php echo _JSHOP_BACF_TITLE_ADMIN_DESC; ?>"><?php echo _JSHOP_BACF_TITLE_ADMIN; ?></th>
        <th title="<?php echo _JSHOP_BACF_TITLE_SITE_DESC; ?>"><?php echo _JSHOP_BACF_TITLE_SITE; ?></th>
        <th title="<?php echo _JSHOP_BACF_ORDER_DESC; ?>"><?php echo _JSHOP_BACF_ORDER; ?></th>
        <th title="<?php echo _JSHOP_BACF_VALUES_DESC; ?>"><?php echo _JSHOP_BACF_VALUES; ?></th>
        <th title="<?php echo _JSHOP_BACF_POSITION_PRODUCT_DESC; ?>"><?php echo _JSHOP_BACF_POSITION_PRODUCT; ?></th>
        <th title="<?php echo _JSHOP_BACF_POSITION_CATEGORY_DESC; ?>"><?php echo _JSHOP_BACF_POSITION_CATEGORY; ?></th>
        <th title="<?php echo _JSHOP_BACF_MULTILANG_DESC; ?>"><?php echo _JSHOP_BACF_MULTILANG; ?></th>
        </thead>
        <tbody>
        <?php
        if ($fields_list) {
            foreach($fields_list as $field) {
                ?>
                <tr class="control-dinamic">
                    <td>
                        <input type="hidden" name="dinamic_field[field_id][]" value="<?php echo (isset($field->id) ? $field->id : 0); ?>" />
                        <div class="btn-group">
                            <a class="remove btn button btn-danger" aria-label="<?php echo _JSHOP_BACF_FIELD_REMOVE; ?>"><span class="icon-minus" aria-hidden="true"></span></a>
                        </div>
                    </td>
                    <td><?php echo \JHtml::_('select.genericlist', $field_type, 'dinamic_field[field_type][]', 'class = "inputbox form-control form-select"', 'value', 'text', (isset($field->field_type) ? $field->field_type : 0)); ?></td>
                    <td><input type="text" name="dinamic_field[title_admin][]" value="<?php echo (isset($field->title_admin) ? $field->title_admin : ''); ?>" placeholder="<?php echo _JSHOP_BACF_TITLE_ADMIN; ?>" title="<?php echo _JSHOP_BACF_TITLE_ADMIN_DESC; ?>" class="inputbox form-control form-input" style="width: 190px;" /></td>
                    <td><input type="text" name="dinamic_field[title_site][]" value="<?php echo (isset($field->title_site) ? $field->title_site : ''); ?>" placeholder="<?php echo _JSHOP_BACF_TITLE_SITE; ?>" title="<?php echo _JSHOP_BACF_TITLE_SITE_DESC; ?>" class="inputbox form-control form-input" style="width: 190px;" /></td>
                    <td><input type="number" min="0" step="1" name="dinamic_field[ordering][]" value="<?php echo (isset($field->ordering) ? $field->ordering : 0); ?>" placeholder="<?php echo _JSHOP_BACF_ORDER; ?>" title="<?php echo _JSHOP_BACF_ORDER_DESC; ?>" class="inputbox form-control form-input" style="width: 60px;" /></td>
                    <td><textarea name="dinamic_field[values_list][]" placeholder="<?php echo _JSHOP_BACF_VALUES; ?>" title="<?php echo _JSHOP_BACF_VALUES_DESC; ?>" class="inputbox form-control form-textarea"><?php echo (isset($field->values_list) ? $field->values_list : ''); ?></textarea></td>
                    <td><?php echo \JHtml::_('select.genericlist', $position_product, 'dinamic_field[position_product][]', 'class = "inputbox form-control form-select"', 'value', 'text', (isset($field->position_product) ? $field->position_product : 0)); ?></td>
                    <td><?php echo \JHtml::_('select.genericlist', $position_category, 'dinamic_field[position_category][]', 'class = "inputbox form-control form-select"', 'value', 'text', (isset($field->position_category) ? $field->position_category : 0)); ?></td>
                    <td><?php echo \JHtml::_('select.genericlist', $yes_no_options, 'dinamic_field[multilang][]', 'class="inputbox form-control form-select form-select-color-state" style="width: 120px;"', 'value', 'text', (isset($field->multilang) ? $field->multilang : 0)); ?></td>
                </tr>
                <?php
            }
        } else {
            echo $standart_fields;
        }
        ?>
        </tbody>
    </table>

    <div class="btn-group ba_custom_fields">
        <a class="add btn button btn-success" aria-label="<?php echo _JSHOP_BACF_FIELD_ADD; ?>"><span class="icon-plus" aria-hidden="true"></span></a>
    </div>

    <input type="hidden" name="params[name_addon]" value="ba_custom_fields" />
</fieldset>