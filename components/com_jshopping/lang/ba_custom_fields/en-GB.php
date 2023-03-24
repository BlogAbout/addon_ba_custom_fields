<?php
/**
 * @version 0.1.1
 * @author А.П.В.
 * @package ba_custom_fields for Jshopping
 * @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
 * @license GNU/GPL
 **/
defined('_JEXEC') or die('Restricted access');

define('_JSHOP_BACF_NAME', "Custom product fields");
define('_JSHOP_BACF_NAME_ENABLE', "Enable");
define('_JSHOP_BACF_NAME_ENABLE_DESC', "Enable or not the output of additional product fields.");
define('_JSHOP_BACF_ACTIVATION_KEY', "Activation key");
define('_JSHOP_BACF_ACTIVATION_KEY_DESC', "Enter the activation key received when purchasing the add-on. Otherwise, the addon will not work.");
define('_JSHOP_BACF_ACTIVATION_KEY_ERROR', "Custom Fields: The activation key is not specified or is not valid. Make sure you enter the correct activation key.");

define('_JSHOP_BACF_LIST_FIELDS', "List of fields");
define('_JSHOP_BACF_FIELD_ADD', "Add");
define('_JSHOP_BACF_FIELD_REMOVE', "Delete");
define('_JSHOP_BACF_FIELD_REMOVE_ALERT', "This action will permanently delete both the field itself and all data associated with this field added to the products earlier.");

define('_JSHOP_BACF_FIELD_TYPE', "Field type");
define('_JSHOP_BACF_FIELD_TYPE_DESC', "Select the type of field to display.");
define('_JSHOP_BACF_FIELD_TYPE_INPUT', "Input");
define('_JSHOP_BACF_FIELD_TYPE_NUMBER', "Number");
define('_JSHOP_BACF_FIELD_TYPE_TEL', "Phone");
define('_JSHOP_BACF_FIELD_TYPE_EMAIL', "E-mail");
define('_JSHOP_BACF_FIELD_TYPE_LINK', "Link");
define('_JSHOP_BACF_FIELD_TYPE_AREA', "Textarea");
define('_JSHOP_BACF_FIELD_TYPE_EDITOR', "Text Editor");
define('_JSHOP_BACF_FIELD_TYPE_RADIO', "Radiobutton");
define('_JSHOP_BACF_FIELD_TYPE_CHECKBOX', "Checkbox");
define('_JSHOP_BACF_FIELD_TYPE_SELECT', "Select");
define('_JSHOP_BACF_FIELD_TYPE_COMBOBOX', "Combobox");
define('_JSHOP_BACF_FIELD_TYPE_IMAGE', "Image");
define('_JSHOP_BACF_FIELD_TYPE_GALLERY', "Gallery");
define('_JSHOP_BACF_FIELD_TYPE_YOUTUBE', "Video Youtube");
define('_JSHOP_BACF_FIELD_TYPE_CALENDAR', "Calendar");

define('_JSHOP_BACF_TITLE_ADMIN', "Title in admin panel");
define('_JSHOP_BACF_TITLE_ADMIN_DESC', "Specify the title that will be displayed in the administrative panel opposite the field.");
define('_JSHOP_BACF_TITLE_SITE', "Title on site");
define('_JSHOP_BACF_TITLE_SITE_DESC', "Indicate the title that will be displayed on the site opposite the field, or leave it blank so as not to display the title.");
define('_JSHOP_BACF_ORDER', "Order");
define('_JSHOP_BACF_ORDER_DESC', "Specify the serial number to sort the field.");
define('_JSHOP_BACF_VALUES', "Values");
define('_JSHOP_BACF_VALUES_DESC', "Specify list values. Specify each new value on a new line.");

define('_JSHOP_BACF_POSITION_PRODUCT', "Position in product");
define('_JSHOP_BACF_POSITION_PRODUCT_DESC', "Select the display position of the field in the product card or leave it blank so as not to display.");
define('_JSHOP_BACF_POSITION_PRODUCT_START', "start");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_IMAGE', "before image");
define('_JSHOP_BACF_POSITION_PRODUCT_BODY_IMAGE', "body image");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_IMAGE', "after image");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_IMAGE_THUMB', "before thumbnails");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_IMAGE_THUMB', "after thumbnails");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_VIDEO', "after video");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_ATRIBUTES', "before atributes");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_ATRIBUTES', "after atributes");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_FREEATRIBUTES', "after free atributes");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_PRICE', "before price");
define('_JSHOP_BACF_POSITION_PRODUCT_BOTTOM_PRICE', "after price");
define('_JSHOP_BACF_POSITION_PRODUCT_BOTTOM_ALLPRICES', "bottom all prices");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_EF', "after extra fields");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_BUTTONS', "before buttons");
define('_JSHOP_BACF_POSITION_PRODUCT_BUTTONS', "body buttons");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_BUTTONS', "after buttons");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_DEMOFILES', "befor demofiles");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_REVIEW', "before reviews");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_SUBMIT', "before button submit review");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_RELATED', "before related products");
define('_JSHOP_BACF_POSITION_PRODUCT_END', "end");

define('_JSHOP_BACF_POSITION_CATEGORY', "Position in list");
define('_JSHOP_BACF_POSITION_CATEGORY_DESC', "Select the display position of the field in the product list or leave it blank so as not to display.");
define('_JSHOP_BACF_POSITION_CATEGORY_VAR_START', "start");
define('_JSHOP_BACF_POSITION_CATEGORY_IMAGE_BLOCK', "beofre image");
define('_JSHOP_BACF_POSITION_CATEGORY_BOTTOM_FOTO', "after image");
define('_JSHOP_BACF_POSITION_CATEGORY_BOTTOM_OLD_PRICE', "after old price");
define('_JSHOP_BACF_POSITION_CATEGORY_BOTTOM_PRICE', "after price");
define('_JSHOP_BACF_POSITION_CATEGORY_TOP_BUTTONS', "before buttons");
define('_JSHOP_BACF_POSITION_CATEGORY_BUTTONS', "body buttons");
define('_JSHOP_BACF_POSITION_CATEGORY_BOTTOM_BUTTONS', "after buttons");
define('_JSHOP_BACF_POSITION_CATEGORY_END', "end");

define('_JSHOP_BACF_MULTILANG', "Multilang");
define('_JSHOP_BACF_MULTILANG_DESC', "Indicate whether this field will be unique for each individual language of the site or not.");

define('_JSHOP_BACF_NO_TYPE_FIELD', "Field type not specified.");
define('_JSHOP_BACF_NO_VALUES_FIELD', "No field values specified.");
define('_JSHOP_BACF_YOUTUBE_INCORRECT', "Invalid Youtube video link.");
define('_JSHOP_BACF_DATETIME_INCORRECT', "The value is not a date.");
define('_JSHOP_BACF_ERROR_UPLOADING_IMAGE', "Custom Fields - Error upload image. code: ");