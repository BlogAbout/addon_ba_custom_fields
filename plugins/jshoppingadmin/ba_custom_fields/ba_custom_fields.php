<?php
/**
* @version 0.0.7
* @author А.П.В.
* @package ba_custom_fields for Jshopping
* @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
* @license GNU/GPL
**/
defined('_JEXEC') or die('Restricted access');

class plgJshoppingAdminBa_custom_fields extends JPlugin {
	private $_params;
	
	public function __construct($subject, $config) {
		JSFactory::loadExtLanguageFile('ba_custom_fields');
		parent::__construct($subject, $config);
		$addon = JTable::getInstance('addon', 'jshop');
		$addon->loadAlias('ba_custom_fields');
		$jshopConfig = JSFactory::getConfig();
		$this->_params = (object)$addon->getParams();
	}
	
	public function onBeforeSaveAddons(&$params, &$post, &$row) {
		if (empty($params['name_addon']) || $params['name_addon'] != 'ba_custom_fields')
			return false;
		
		$db = JFactory::getDbo();
		
		if ($post['dinamic_field']) {
			$dinamic_fields = $post['dinamic_field'];
			
			$query = $db->getQuery(true);
			$query->select('`id`')
				->from($db->quoteName('#__jshopping_custom_fields'));
			$db->setQuery($query);
			$result = $db->loadColumn();
			
			$remove_fields = array_diff($result, $dinamic_fields['field_id']);
			
			if ($remove_fields) {
				$query = $db->getQuery(true);
				$conditions = array(
					$db->quoteName('id') . ' IN (' . implode(',', $remove_fields) . ')'
				);
				$query->delete($db->quoteName('#__jshopping_custom_fields'))
					->where($conditions);
				$db->setQuery($query);
				$result = $db->execute();
				
				$remove_sql = array();
				foreach($remove_fields as $id_field) {
					$remove_sql[] = "DROP `ba_custom_field_" . $id_field . "`";
				}
				$query = "ALTER TABLE `#__jshopping_custom_fields_values` " . implode(', ', $remove_sql) . ";";
				$db->setQuery($query);
				$db->query();
			}
			
			foreach ($dinamic_fields as $fname => $field) {
				foreach ($field as $key => $value) {
					$result_fields[$key][$fname] = $value;
				}
			}
			
			foreach($result_fields as $field) {
				if ($field['field_type'] == '0') {
					continue;
				}
				
				$field_data = new stdClass();
				$field_data->field_type = $field['field_type'];
				$field_data->title_admin = $field['title_admin'];
				$field_data->title_site = $field['title_site'];
				if (isset($field['ordering'])) {
					$field_data->ordering = $field['ordering'];
				}
				$field_data->values_list = $field['values_list'];
				$field_data->position_admin = $field['position_admin'];
				$field_data->position_product = $field['position_product'];
				$field_data->position_category = $field['position_category'];
				$field_data->multilang = isset($field['multilang']) ? $field['multilang'] : 0;
				
				if ($field['field_id'] == 0) {
					$result = $db->insertObject('#__jshopping_custom_fields', $field_data);
					$field_id = $db->insertid();
					$alter_text = 'ADD';
				} else {
					$field_data->id = $field_id = $field['field_id'];
					$result = $db->updateObject('#__jshopping_custom_fields', $field_data, 'id');
					$alter_text = 'MODIFY COLUMN';
				}
				
				switch($field['field_type']) {
					case 'area':
					case 'editor':
					case 'gallery':  {
						$query = "ALTER TABLE `#__jshopping_custom_fields_values` " . $alter_text . " `ba_custom_field_" . $field_id . "` text;";
						break;
					}
					default: {
						$query = "ALTER TABLE `#__jshopping_custom_fields_values` " . $alter_text . " `ba_custom_field_" . $field_id . "` VARCHAR(255);";
					}
				}
				$db->setQuery($query);
				$db->query();
			}
		} else {
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__jshopping_custom_fields'));
			$db->setQuery($query);
			$result = $db->execute();
			
			$query = "DROP TABLE IF EXISTS `#__jshopping_custom_fields_values`";
			$db->setQuery($query);
			$db->query();

			$query = "
				CREATE TABLE `#__jshopping_custom_fields_values` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`id_product` int(11) unsigned NOT NULL,
					`lang` varchar(100) NOT NULL,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
			";
			$db->setQuery($query);
			$db->query();
		}
	}
	
	public function onDisplayProductEditTabsEndTab(&$row, &$lists, &$tax_value) {
		if (!isset($this->_params->enable) || $this->_params->enable != 1) {
			return;
		}
		
		echo '<li><a href="#product_ba_custom_fields" data-toggle="tab">Custom Fields</a></li>';
	}
	
	public function onDisplayProductEditTabsEnd(&$pane, &$row, &$lists, &$tax_value, &$currency) {
		if (!isset($this->_params->enable) || $this->_params->enable != 1) {
			return;
		}
		
		$jshopConfig = JSFactory::getConfig();
		$model_langs = JModelLegacy::getInstance('Languages','JshoppingModel');
		$languages = $model_langs->getAllLanguages(1);
		
		echo '<div id="product_ba_custom_fields" class="tab-pane"><div class="col100"><table class="admintable">';
		
		echo '
			<style>
				.custom_field_image {
					width: 150px;
				}
				.gallery-block-list {
					
				}
				.gallery-block-list:after {
					content: "";
					display: table;
					clear: both;
				}
				.gallery-block-list .custom_field_gallery {
					width: 150px;
					margin-right: 5px;
					float: left;
				}
			</style>
			<script>
				function deleteImageCustomField(elem) {
					jQuery(elem).parents(".image_block").hide().find("input.image_remove").val(jQuery(elem).parents(".image_block").find("input.image_old").val());
				}
			</script>
		';
		
		$db = JFactory::getDbo();
		$query = "
			SELECT *
			FROM `#__jshopping_custom_fields`
			ORDER BY `ordering`
		";
		$db->setQuery($query);
		$fields_list = $db->loadObjectList();
		if ($fields_list) {
			if ($row->product_id) {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('*')
					->from($db->quoteName('#__jshopping_custom_fields_values'))
					->where($db->quoteName('id_product') . ' = ' . $row->product_id);
				$db->setQuery($query);
				$result = $db->loadObjectList();
			}
			
			$fields_product = array();
			if ($result) {
				foreach($result as $field_id) {
					$fields_product[$field_id->lang] = $field_id;
				}
			}
			
			foreach($fields_list as $field) {
				$field_name = 'ba_custom_field_' . $field->id;
				if ($field->multilang) {
					foreach($languages as $lang) {
						$field_value = isset($row->product_id) ? $fields_product[$lang->language]->$field_name : '';
						$field_html = $this->generate_html_field($field, $field_value, $lang->language);
						echo '<tr>
								<td class="key">' . $field->title_admin . ' (' . $lang->name . ')</td>
								<td>' . $field_html . '</td>
							</tr>
						';
					}
				} else {
					$field_value = isset($row->product_id) ? $fields_product[$jshopConfig->defaultLanguage]->$field_name : '';
					$field_html = $this->generate_html_field($field, $field_value, $jshopConfig->defaultLanguage);
					echo '<tr>
							<td class="key">' . $field->title_admin . ' 2</td>
							<td>' . $field_html . '</td>
						</tr>
					';
				}
			}
		}
		
		echo '</table></div><div class="clr"></div></div>';
	}
	
	public function onBeforeDisplaySaveProduct(&$post, &$product) {
		if (!isset($this->_params->enable) || $this->_params->enable != 1) {
			return;
		}
		
		$jinput = JFactory::getApplication()->input;
		$jshopConfig = JSFactory::getConfig();
		require_once($jshopConfig->path . 'lib/uploadfile.class.php');
		
		$model_langs = JModelLegacy::getInstance('Languages','JshoppingModel');
		$languages = $model_langs->getAllLanguages(1);
		
		$db = JFactory::getDbo();
		$query = "
			SELECT *
			FROM `#__jshopping_custom_fields`
		";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if ($result) {
			$ba_custom_fields = array();
			foreach($result as $field) {
				switch($field->field_type) {
					case 'link': {
						if ($field->multilang) {
							foreach($languages as $lang) {
								$field_value = array(
									'f_link' => JComponentHelper::filterText($jinput->get('ba_custom_field_' . $field->id . '_' . $lang->language, '', 'raw')),
									'f_text' => JComponentHelper::filterText($jinput->get('ba_custom_field_' . $field->id . '_' . $lang->language . '_text', '', 'raw'))
								);
								$product->ba_custom_fields[$field->id][$lang->language] = $field_value;
							}
						} else {
							$field_value = array(
								'f_link' => JComponentHelper::filterText($jinput->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage, '', 'raw')),
								'f_text' => JComponentHelper::filterText($jinput->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage . '_text', '', 'raw'))
							);
							foreach($languages as $lang) {
								$product->ba_custom_fields[$field->id][$lang->language] = $field_value;
							}
						}
						break;
					}
					case 'image': {
						if ($field->multilang) {
							foreach($languages as $lang) {
								$field_value = $jinput->files->get('ba_custom_field_' . $field->id . '_' . $lang->language);
								$upload = new UploadFile($field_value);
								$upload->setAllowFile(array('jpeg','jpg','gif','png'));
								$upload->setDir($jshopConfig->path . 'files/images_customfields');
								$upload->setFileNameMd5(0);
								$upload->setFilterName(1);
								$image_old = $jinput->get('ba_custom_field_' . $field->id . '_' . $lang->language . '_old', '', 'raw');
								$image_remove = $jinput->get('ba_custom_field_' . $field->id . '_' . $lang->language . '_remove', '', 'raw');
								if ($image_remove != 'no') {
									@unlink($jshopConfig->path . 'files/images_customfields/' . $image_remove);
									$product->ba_custom_fields[$field->id][$lang->language] = '';
								}
								if ($upload->upload()){
									if ($image_old) {
										@unlink($jshopConfig->path . 'files/images_customfields/' . $image_old);
									}
									$product->ba_custom_fields[$field->id][$lang->language] = $upload->getName();
									@chmod($jshopConfig->path . 'files/images_customfields/' . $product->ba_custom_fields[$field->id][$lang->language], 0777);
								} else {
									if ($upload->getError() != 4) {
										JError::raiseWarning('', _JSHOP_ERROR_UPLOADING_IMAGE);
										saveToLog('error.log', _JSHOP_BACF_ERROR_UPLOADING_IMAGE . $upload->getError());
									}
								}
							}
						} else {
							$field_value = $jinput->files->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage);
							$upload = new UploadFile($field_value);
							$upload->setAllowFile(array('jpeg','jpg','gif','png'));
							$upload->setDir($jshopConfig->path . 'files/images_customfields');
							$upload->setFileNameMd5(0);
							$upload->setFilterName(1);
							$image_old = $jinput->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage . '_old', '', 'raw');
							$image_remove = $jinput->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage . '_remove', '', 'raw');
							if ($image_remove != 'no') {
								@unlink($jshopConfig->path . 'files/images_customfields/' . $image_remove);
								$product->ba_custom_fields[$field->id][$lang->language] = '';
								foreach($languages as $lang) {
									$product->ba_custom_fields[$field->id][$lang->language] = '';
								}
							}
							$name_image = '';
							if ($upload->upload()) {
								if ($image_old) {
									@unlink($jshopConfig->path . 'files/images_customfields/' . $image_old);
								}
								$name_image = $upload->getName();
								@chmod($jshopConfig->path . 'files/images_customfields/' . $product->ba_custom_fields[$field->id][$lang->language], 0777);
								foreach($languages as $lang) {
									$product->ba_custom_fields[$field->id][$lang->language] = $name_image;
								}
							} else {
								if ($upload->getError() != 4) {
									JError::raiseWarning('', _JSHOP_ERROR_UPLOADING_IMAGE);
									saveToLog('error.log', _JSHOP_BACF_ERROR_UPLOADING_IMAGE . $upload->getError());
								}
							}
						}
						break;
					}
					case 'gallery': {
						if ($field->multilang) {
							foreach($languages as $lang) {
								$images_array = $jinput->files->get('ba_custom_field_' . $field->id . '_' . $lang->language);
								$images_result = array();
								$images_old = $jinput->get('ba_custom_field_' . $field->id . '_' . $lang->language . '_old', '', 'ARRAY');
								$images_remove = $jinput->get('ba_custom_field_' . $field->id . '_' . $lang->language . '_remove', '', 'ARRAY');
								foreach($images_array as $file) {
									$upload = new UploadFile($file);
									$upload->setAllowFile(array('jpeg','jpg','gif','png'));
									$upload->setDir($jshopConfig->path . 'files/images_customfields');
									$upload->setFileNameMd5(0);
									$upload->setFilterName(1);
									if ($images_remove) {
										foreach($images_remove as $i_remove) {
											if ($i_remove != 'no') {
												@unlink($jshopConfig->path . 'files/images_customfields/' . $i_remove);
												if (($key = array_search($i_remove, $images_old)) !== FALSE) {
													unset($images_old[$key]);
												}
											}
										}
									}
									if ($upload->upload()){
										$new_file_name = $upload->getName();
										$images_result[] = $new_file_name;
										@chmod($jshopConfig->path . 'files/images_customfields/' . $new_file_name, 0777);
									} else {
										if ($upload->getError() != 4) {
											JError::raiseWarning('', _JSHOP_ERROR_UPLOADING_IMAGE);
											saveToLog('error.log', _JSHOP_BACF_ERROR_UPLOADING_IMAGE . $upload->getError());
										}
									}
								}
								if (!is_array($images_old))
									$images_old = array();
								if (!is_array($images_result))
									$images_result = array();
								$images = array_merge($images_result, $images_old);
								$product->ba_custom_fields[$field->id][$lang->language] = $images;
							}
						} else {
							$images_array = $jinput->files->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage);
							$images_result = array();
							$images_old = $jinput->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage . '_old', '', 'ARRAY');
							$images_remove = $jinput->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage . '_remove', '', 'ARRAY');
							
							foreach($images_array as $file) {
								$upload = new UploadFile($file);
								$upload->setAllowFile(array('jpeg','jpg','gif','png'));
								$upload->setDir($jshopConfig->path . 'files/images_customfields');
								$upload->setFileNameMd5(0);
								$upload->setFilterName(1);
								
								if ($images_remove) {
									foreach($images_remove as $i_remove) {
										if ($i_remove != 'no') {
											@unlink($jshopConfig->path . 'files/images_customfields/' . $i_remove);
											if (($key = array_search($i_remove, $images_old)) !== FALSE) {
												unset($images_old[$key]);
											}
										}
									}
								}
								
								if ($upload->upload()){
									$new_file_name = $upload->getName();
									$images_result[] = $new_file_name;
									@chmod($jshopConfig->path . 'files/images_customfields/' . $new_file_name, 0777);
								} else {
									if ($upload->getError() != 4) {
										JError::raiseWarning('', _JSHOP_ERROR_UPLOADING_IMAGE);
										saveToLog('error.log', _JSHOP_BACF_ERROR_UPLOADING_IMAGE . $upload->getError());
									}
								}
							}
							
							if (!is_array($images_old))
								$images_old = array();
							if (!is_array($images_result))
								$images_result = array();
							$images = array_merge($images_result, $images_old);
							
							foreach($languages as $lang) {
								$product->ba_custom_fields[$field->id][$lang->language] = $images;
							}
						}
						break;
					}
					default: {
						if ($field->multilang) {
							foreach($languages as $lang) {
								$field_value = $jinput->get('ba_custom_field_' . $field->id . '_' . $lang->language, '', 'raw');
								$field_value = JComponentHelper::filterText($field_value);
								$product->ba_custom_fields[$field->id][$lang->language] = $field_value;
							}
						} else {
							$field_value = $jinput->get('ba_custom_field_' . $field->id . '_' . $jshopConfig->defaultLanguage, '', 'raw');
							$field_value = JComponentHelper::filterText($field_value);
							foreach($languages as $lang) {
								$product->ba_custom_fields[$field->id][$lang->language] = $field_value;
							}
						}
						break;
					}
				}
			}
		}
	}
	
	public function onAfterSaveProduct(&$product) {
		if (!isset($this->_params->enable) || $this->_params->enable != 1) {
			return;
		}
		
		if (!empty($product->ba_custom_fields)) {
			$db = JFactory::getDbo();
			$model_langs = JModelLegacy::getInstance('Languages','JshoppingModel');
			$languages = $model_langs->getAllLanguages(1);
			
			foreach($languages as $lang) {
				$query = $db->getQuery(true);
				$query->select('*')
					->from($db->quoteName('#__jshopping_custom_fields_values'))
					->where($db->quoteName('id_product') . ' = ' . $product->product_id)
					->where($db->quoteName('lang') . ' = "' . $lang->language . '"');
				$db->setQuery($query);
				$fields_product = $db->loadAssoc();
				
				$field_data = new stdClass();
				$field_data->id_product = $product->product_id;
				$field_data->lang = $lang->language;
				foreach($product->ba_custom_fields as $key => $val) {
					$field_name = 'ba_custom_field_' . $key;
					if (is_array($val[$lang->language])) {
						$field_data->{$field_name} = json_encode($val[$lang->language]);
					} else {
						$field_data->{$field_name} = $val[$lang->language];
					}
				}
				
				if ($fields_product) {
					$field_data->id = $fields_product['id'];
					$result = $db->updateObject('#__jshopping_custom_fields_values', $field_data, 'id');
				} else {
					$result = $db->insertObject('#__jshopping_custom_fields_values', $field_data);
					$field_id = $db->insertid();
				}
			}
		}
	}
	
	public function onAfterRemoveProduct(&$ids) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$conditions = array(
			$db->quoteName('id_product') . ' IN (' . implode(',', $ids) . ')'
		);
		$query->delete($db->quoteName('#__jshopping_custom_fields_values'))
			->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
	}
	
	function generate_html_field($field, $field_value, $lang) {
		$jshopConfig = JSFactory::getConfig();
		$field_html = '';
		$field_name = 'ba_custom_field_' . $field->id . '_' . $lang;
		
		switch($field->field_type) {
			case 'input': {
				$field_html = '<input type="text" class="inputbox w100" name="' . $field_name . '" value="' . $field_value . '" />';
				break;
			}
			case 'number': {
				$params = (isset($field->values_list) && $field->values_list != '') ? $field->values_list : '';
				$field_html = '<input type="number" ' . $params . ' class="inputbox wide" name="' . $field_name . '" value="' . $field_value . '" />';
				break;
			}
			case 'tel': {
				$field_html = '<input type="tel" class="inputbox wide" name="' . $field_name . '" value="' . $field_value . '" />';
				break;
			}
			case 'email': {
				$field_html = '<input type="email" class="inputbox wide" name="' . $field_name . '" value="' . $field_value . '" />';
				break;
			}
			case 'link': {
				$field_value = json_decode($field_value);
				$field_link = isset($field_value->f_link) ? $field_value->f_link : '';
				$field_text = isset($field_value->f_text) ? $field_value->f_text : '';
				
				$field_html = '<input type="text" class="inputbox" name="' . $field_name . '" value="' . $field_link . '" placeholder="Link" />';
				$field_html .= '<input type="text" class="inputbox" name="' . $field_name . '_text" value="' . $field_text . '" placeholder="Text" />';
				break;
			}
			case 'area': {
				$field_html = '<textarea name="' . $field_name . '" class="wide" rows="5">' . $field_value . '</textarea>';
				break;
			}
			case 'editor': {
				$editor = JFactory::getEditor();
				$field_html = $editor->display($field_name, $field_value, '100%', '350', '75', '20');
				break;
			}
			case 'radio': {
				if ($field->values_list != '') {
					$values_list = explode("\n", $field->values_list);
					foreach($values_list as $f_v) {
						$field_html = '<label><input type="radio" name="' . $field_name . '" value="' . trim($f_v) . '" ' . ((trim($f_v) == $field_value) ? 'checked="checked"' : '') . ' /> ' . trim($f_v) . '</label><br />';
					}
				} else {
					$field_html = _JSHOP_BACF_NO_VALUES_FIELD;
				}
				break;
			}
			case 'checkbox': {
				if ($field->values_list != '') {
					$field_value = json_decode($field_value);
					if (!is_array($field_value)) {
						$field_value = array();
					}
					$values_list = explode("\n", $field->values_list);
					foreach($values_list as $f_v) {
						$field_html = '<label><input type="checkbox" name="' . $field_name . '[]" value="' . trim($f_v) . '" ' . ((in_array(trim($f_v), $field_value)) ? 'checked="checked"' : '') . ' /> ' . trim($f_v) . '</label><br />';
					}
				} else {
					$field_html = _JSHOP_BACF_NO_VALUES_FIELD;
				}
				break;
			}
			case 'select': {
				if ($field->values_list != '') {
					$values_list = explode("\n", $field->values_list);
					$field_html = '<select name="' . $field_name . '" class="inputbox">';
						$field_html .= '<option value="">- Выберите -</option>';
						foreach($values_list as $f_v) {
							$field_html .= '<option value="' . trim($f_v) . '" ' . ((trim($f_v) == $field_value) ? 'selected="selected"' : '') . '>' . trim($f_v) . '</option>';
						}
					$field_html .= '</select>';
				} else {
					$field_html = _JSHOP_BACF_NO_VALUES_FIELD;
				}
				break;
			}
			case 'combobox': {
				if ($field->values_list != '') {
					$field_value = json_decode($field_value);
					if (!is_array($field_value)) {
						$field_value = array();
					}
					$values_list = explode("\n", $field->values_list);
					$field_html = '<select name="' . $field_name . '[]" class="inputbox" size="10" multiple="multiple">';
						foreach($values_list as $f_v) {
							$field_html .= '<option value="' . trim($f_v) . '" ' . ((in_array(trim($f_v), $field_value)) ? 'selected="selected"' : '') . '>' . trim($f_v) . '</option>';
						}
					$field_html .= '</select>';
				} else {
					$field_html = _JSHOP_BACF_NO_VALUES_FIELD;
				}
				break;
			}
			case 'image': {
				if ($field_value) {
					$field_html = '
						<div class="custom_field_image image_block">
							<div><img src="' . $jshopConfig->live_path . 'files/images_customfields/' . $field_value . '" alt="" /></div>
							<div style="padding-bottom:5px;" class="link_delete_foto">
								<a class="btn btn-micro" href="#" onclick="deleteImageCustomField(jQuery(this)); return false;">
									<img src="components/com_jshopping/images/publish_r.png">' . _JSHOP_DELETE_IMAGE . '
								</a>
							</div>
							<input type="hidden" class="image_old" name="' . $field_name . '_old" value="' . ((isset($field_value)) ? $field_value : '') . '" />
							<input type="hidden" class="image_remove" name="' . $field_name . '_remove" value="no" />
						</div>
					';
				}
				$field_html .= '<input type="file" name="' . $field_name . '" />';
				break;
			}
			case 'gallery': {
				$field_value = json_decode($field_value);
				if (is_array($field_value)) {
					$field_html .= '<div class="gallery-block-list">';
						foreach($field_value as $f_v) {
							$field_html .= '
								<div class="custom_field_gallery image_block">
									<div><img src="' . $jshopConfig->live_path . 'files/images_customfields/' . $f_v . '" alt="" /></div>
									<div style="padding-bottom:5px;" class="link_delete_foto">
										<a class="btn btn-micro" href="#" onclick="deleteImageCustomField(jQuery(this)); return false;">
											<img src="components/com_jshopping/images/publish_r.png">' . _JSHOP_DELETE_IMAGE . '
										</a>
									</div>
									<input type="hidden" class="image_old" name="' . $field_name . '_old[]" value="' . ((isset($f_v)) ? $f_v : '') . '" />
									<input type="hidden" class="image_remove" name="' . $field_name . '_remove[]" value="no" />
								</div>
							';
						}
					$field_html .= '</div>';
				}
				$field_html .= '<input type="file" name="' . $field_name . '[]" multiple />';
				break;
			}
			case 'youtube': {
				$field_html = '<input type="text" class="inputbox wide" name="' . $field_name . '" value="' . $field_value . '" />';
				break;
			}
			case 'calendar': {
				$field_html = JHTML::_('calendar', $field_value, $field_name, 'ba_custom_field_' . $field->id, '%Y-%m-%d %H:%M:%S', null);
				break;
			}
			default: {
				$field_html = _JSHOP_BACF_NO_TYPE_FIELD;
				break;
			}
		}
		
		return $field_html;
	}
}
?>