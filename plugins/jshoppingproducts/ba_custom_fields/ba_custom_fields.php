<?php
/**
 * @version 0.1.2
 * @author А.П.В.
 * @package ba_custom_fields for Jshopping
 * @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
 * @license GNU/GPL
 **/
defined('_JEXEC') or die('Restricted access');

class plgJshoppingProductsBa_custom_fields extends JPlugin
{
    private $_params;

    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);

        $addon = \JSFactory::getTable('addon', 'jshop');
        $addon->loadAlias('ba_custom_fields');
        $this->_params = (object)$addon->getParams();
    }

    public function onBeforeDisplayProductList(&$products)
    {
        if (isset($this->_params->state_list) && $this->_params->state_list == 0) {
            return;
        }

        if (!isset($this->_params->enable) || $this->_params->enable != 1) {
            return;
        }

        $jshopConfig = \JSFactory::getConfig();
        $doc = \JFactory::getDocument();
        $doc->addStyleSheet(\JURI::root() . 'plugins/jshoppingproducts/ba_custom_fields/ba_custom_fields.css?ver=0.0.3');

        $db = \JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__jshopping_custom_fields'))
            ->where($db->quoteName('position_category') . ' != "0"')
            ->order('`ordering` ASC');
        $db->setQuery($query);
        $list_fields = $db->loadObjectList();

        if ($list_fields) {
            foreach ($products as $product) {
                $query = $db->getQuery(true);
                $query->select('*')
                    ->from($db->quoteName('#__jshopping_custom_fields_values'))
                    ->where($db->quoteName('id_product') . ' = ' . intval($product->product_id))
                    ->where($db->quoteName('lang') . ' = "' . $jshopConfig->cur_lang . '"');
                $db->setQuery($query);
                $list_values = $db->loadAssoc();

                foreach ($list_fields as $field) {
                    $position = $field->position_category;
                    $field_name = 'ba_custom_field_' . $field->id;

                    if ($list_values[$field_name]) {
                        $product->$position .= $this->generate_html_field($field, $list_values[$field_name]);
                    }
                }
            }
        }
    }

    public function onBeforeDisplayProductView(&$view)
    {
        if (isset($this->_params->state_prod) && $this->_params->state_prod == 0) {
            return;
        }

        if (!isset($this->_params->enable) || $this->_params->enable != 1) {
            return;
        }

        $jshopConfig = \JSFactory::getConfig();
        $doc = \JFactory::getDocument();
        $doc->addStyleSheet(\JURI::root() . 'plugins/jshoppingproducts/ba_custom_fields/ba_custom_fields.css?ver=0.1.2');

        $db = \JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__jshopping_custom_fields'))
            ->where($db->quoteName('position_product') . ' != "0"')
            ->order('`ordering` ASC');
        $db->setQuery($query);
        $list_fields = $db->loadObjectList();

        if ($list_fields) {
            $query = $db->getQuery(true);
            $query->select('*')
                ->from($db->quoteName('#__jshopping_custom_fields_values'))
                ->where($db->quoteName('id_product') . ' = ' . intval($view->product->product_id))
                ->where($db->quoteName('lang') . ' = "' . $jshopConfig->cur_lang . '"');
            $db->setQuery($query);
            $list_values = $db->loadAssoc();

            foreach ($list_fields as $field) {
                $position = $field->position_product;
                $field_name = 'ba_custom_field_' . $field->id;

                if ($list_values[$field_name]) {
                    $view->$position .= $this->generate_html_field($field, $list_values[$field_name]);
                }
            }
        }
    }

    function generate_html_field($field, $value)
    {
        $jshopConfig = \JSFactory::getConfig();
        $html = '';

        switch ($field->field_type) {
            case 'input':
            case 'number':
            {
                if ($field->title_site != '') {
                    $html .= '<strong>' . $field->title_site . '</strong> ';
                }
                $html .= '<span>' . $value . '</span>';
                break;
            }
            case 'tel':
            {
                if ($field->title_site != '') {
                    $html .= '<strong>' . $field->title_site . '</strong> ';
                }
                $html .= '<a href="tel:' . $value . '">' . $value . '</a>';
                break;
            }
            case 'email':
            {
                if ($field->title_site != '') {
                    $html .= '<strong>' . $field->title_site . '</strong> ';
                }
                $html .= '<a href="mailto:' . $value . '">' . $value . '</a>';
                break;
            }
            case 'link':
            {
                if ($field->title_site != '') {
                    $html .= '<strong>' . $field->title_site . '</strong> ';
                }
                $params = (isset($field->values_list) && $field->values_list != '') ? $field->values_list : '';
                $value = json_decode($value);
                $value_link = isset($value->f_link) ? $value->f_link : '';
                $value_text = isset($value->f_text) ? $value->f_text : $value->f_link;
                $html .= '<a href="' . $value_link . '" ' . $params . '>' . $value_text . '</a>';
                break;
            }
            case 'area':
            case 'editor':
            {
                if ($field->title_site != '') {
                    $html .= '<div class="field_title"><strong>' . $field->title_site . '</strong></div>';
                }
                $html .= '<div class="field_content">' . $value . '</div>';
                break;
            }
            case 'radio':
            case 'select':
            {
                if ($field->title_site != '') {
                    $html .= '<strong>' . $field->title_site . '</strong> ';
                }
                $html .= '<span>' . $value . '</span>';
                break;
            }
            case 'checkbox':
            case 'combobox':
            {
                if ($field->title_site != '') {
                    $html .= '<dt>' . $field->title_site . '</dt> ';
                }
                $value = json_decode($value);
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $html .= '<dd>' . $v . '</dd>';
                    }
                }
                $html = '<dl>' . $html . '</dl>';
                break;
            }
            case 'image':
            {
                if ($field->title_site != '') {
                    $html .= '<div class="field_title"><strong>' . $field->title_site . '</strong></div>';
                }
                $html .= '
					<div class="field_content">
						<a href="' . $jshopConfig->live_path . 'files/images_customfields/' . $value . '" target="_blank">
							<img src="' . $jshopConfig->live_path . 'files/images_customfields/' . $value . '" alt="" />
						</a>
					</div>
				';
                break;
            }
            case 'gallery':
            {
                if ($field->title_site != '') {
                    $html .= '<div class="field_title"><strong>' . $field->title_site . '</strong></div>';
                }
                $value = json_decode($value);
                if (is_array($value)) {
                    $html .= '<div class="field_content field_gallery">';
                    foreach ($value as $v) {
                        $html .= '
							<div class="field_gallery_item">
								<a href="' . $jshopConfig->live_path . 'files/images_customfields/' . $v . '" target="_blank">
									<img src="' . $jshopConfig->live_path . 'files/images_customfields/' . $v . '" alt="" />
								</a>
							</div>
						';
                    }
                    $html .= '</div>';
                }
                break;
            }
            case 'youtube':
            {
                if ($field->title_site != '') {
                    $html .= '<div class="field_title"><strong>' . $field->title_site . '</strong></div>';
                }
                preg_match('|youtube.com/watch\?v=([a-zA-Z0-9_-]+)|', $value, $code);
                if ($code[1]) {
                    $html .= '<iframe width="100%" height="400px" src="https://www.youtube.com/embed/' . $code[1] . '" frameborder="0"
						allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                } else {
                    $html .= _JSHOP_BACF_YOUTUBE_INCORRECT;
                }
                break;
            }
            case 'calendar':
            {
                if ($field->title_site != '') {
                    $html .= '<strong>' . $field->title_site . '</strong> ';
                }
                $format = (isset($field->values_list) && $field->values_list != '') ? $field->values_list : 'd.m.Y H:i:s';
                if (is_numeric(strtotime($value))) {
                    $value = \JHtml::date($value, $format);
                } else {
                    $value = 'The value is not a date.';
                }
                $html .= '<span>' . $value . '</span>';
                break;
            }
            default:
            {
                $field_html = _JSHOP_BACF_NO_TYPE_FIELD;
                break;
            }
        }

        $html = '<div class="custom_field cfield_' . $field->id . '">' . $html . '</div>';
        return $html;
    }
}