<?php
/**
 * @version 0.1.2
 * @author А.П.В.
 * @package ba_custom_fields for Jshopping
 * @copyright Copyright (C) 2010 blog-about.ru. All rights reserved.
 * @license GNU/GPL
 **/
defined('_JEXEC') or die('Restricted access');

define('_JSHOP_BACF_NAME', "Дополнительные поля товаров");
define('_JSHOP_BACF_NAME_ENABLE', "Включить");
define('_JSHOP_BACF_NAME_ENABLE_DESC', "Включить или нет вывод дополнительных полей товаров.");
define('_JSHOP_BACF_ACTIVATION_KEY', "Ключ активации");
define('_JSHOP_BACF_ACTIVATION_KEY_DESC', "Введите ключ активации, полученный при покупке аддона. В противном случа аддон работать не будет.");
define('_JSHOP_BACF_ACTIVATION_KEY_ERROR', "Custom Fields: Ключ активации не указан или не действителен. Убедитесь, что Вы указали верный ключ активации.");

define('_JSHOP_BACF_LIST_FIELDS', "Список полей");
define('_JSHOP_BACF_FIELD_ADD', "Добавить");
define('_JSHOP_BACF_FIELD_REMOVE', "Удалить");
define('_JSHOP_BACF_FIELD_REMOVE_ALERT', "Данное действие безвозвратно удалит как само поле, так и все данные, связанные с этим полем, добавленные к товарам ранее.");

define('_JSHOP_BACF_FIELD_TYPE', "Тип поля");
define('_JSHOP_BACF_FIELD_TYPE_DESC', "Выберите тип поля для отображения");
define('_JSHOP_BACF_FIELD_TYPE_INPUT', "Поле ввода");
define('_JSHOP_BACF_FIELD_TYPE_NUMBER', "Число");
define('_JSHOP_BACF_FIELD_TYPE_TEL', "Телефон");
define('_JSHOP_BACF_FIELD_TYPE_EMAIL', "E-mail");
define('_JSHOP_BACF_FIELD_TYPE_LINK', "Ссылка");
define('_JSHOP_BACF_FIELD_TYPE_AREA', "Область ввода");
define('_JSHOP_BACF_FIELD_TYPE_EDITOR', "Текстовый редактор");
define('_JSHOP_BACF_FIELD_TYPE_RADIO', "Кнопки выбора");
define('_JSHOP_BACF_FIELD_TYPE_CHECKBOX', "Флажки");
define('_JSHOP_BACF_FIELD_TYPE_SELECT', "Список выбора");
define('_JSHOP_BACF_FIELD_TYPE_COMBOBOX', "Множественный список выбора");
define('_JSHOP_BACF_FIELD_TYPE_IMAGE', "Изображение");
define('_JSHOP_BACF_FIELD_TYPE_GALLERY', "Галерея");
define('_JSHOP_BACF_FIELD_TYPE_YOUTUBE', "Видео Youtube");
define('_JSHOP_BACF_FIELD_TYPE_CALENDAR', "Календарь");

define('_JSHOP_BACF_TITLE_ADMIN', "Заголовок в админ панели");
define('_JSHOP_BACF_TITLE_ADMIN_DESC', "Укажите заголовок, который будет отображаться в административной панели напротив поля.");
define('_JSHOP_BACF_TITLE_SITE', "Заголовок на сайте");
define('_JSHOP_BACF_TITLE_SITE_DESC', "Укажите заголовок, который будет отображаться на сайте напротив поля, либо оставьте пустым, чтобы не отображать заголовок.");
define('_JSHOP_BACF_ORDER', "Порядок");
define('_JSHOP_BACF_ORDER_DESC', "Укажите порядковый номер для сортировки поля.");
define('_JSHOP_BACF_VALUES', "Значения");
define('_JSHOP_BACF_VALUES_DESC', "Укажите значения списка. Каждое новое значение укажите с новой строки.");

define('_JSHOP_BACF_POSITION_PRODUCT', "Позиция в карточке");
define('_JSHOP_BACF_POSITION_PRODUCT_DESC', "Выберите позицию отображения поля в карточке товара или оставьте пустым, чтобы не отображать.");
define('_JSHOP_BACF_POSITION_PRODUCT_START', "вначале");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_IMAGE', "перед блоком изображения");
define('_JSHOP_BACF_POSITION_PRODUCT_BODY_IMAGE', "внутри блока изображения");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_IMAGE', "после блока изображения");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_IMAGE_THUMB', "перед миниатюрами");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_IMAGE_THUMB', "после миниатюр");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_VIDEO', "после видео");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_ATRIBUTES', "перед атрибутами");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_ATRIBUTES', "после атрибутов");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_FREEATRIBUTES', "после свободных атрибутов");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_PRICE', "перед ценой");
define('_JSHOP_BACF_POSITION_PRODUCT_BOTTOM_PRICE', "после цены");
define('_JSHOP_BACF_POSITION_PRODUCT_BOTTOM_ALLPRICES', "после всех цен");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_EF', "после характеристик");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_BUTTONS', "перед блоком с кнопками");
define('_JSHOP_BACF_POSITION_PRODUCT_BUTTONS', "внутри блока с кнопками");
define('_JSHOP_BACF_POSITION_PRODUCT_AFTER_BUTTONS', "после блока с кнопками");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_DEMOFILES', "перед файлами демо");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_REVIEW', "перед отзывами");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_SUBMIT', "перед кнопкой отправки отзыва");
define('_JSHOP_BACF_POSITION_PRODUCT_BEFORE_RELATED', "перед сопутствующими товарами");
define('_JSHOP_BACF_POSITION_PRODUCT_END', "вконце");

define('_JSHOP_BACF_POSITION_CATEGORY', "Позиция в списке");
define('_JSHOP_BACF_POSITION_CATEGORY_DESC', "Выберите позицию отображения поля в списке товаров или оставьте пустым, чтобы не отображать.");
define('_JSHOP_BACF_POSITION_CATEGORY_VAR_START', "перед товаром");
define('_JSHOP_BACF_POSITION_CATEGORY_IMAGE_BLOCK', "перед изображением");
define('_JSHOP_BACF_POSITION_CATEGORY_BOTTOM_FOTO', "после изображения");
define('_JSHOP_BACF_POSITION_CATEGORY_BOTTOM_OLD_PRICE', "после старой цены");
define('_JSHOP_BACF_POSITION_CATEGORY_BOTTOM_PRICE', "после цены");
define('_JSHOP_BACF_POSITION_CATEGORY_TOP_BUTTONS', "над блоком с кнопками");
define('_JSHOP_BACF_POSITION_CATEGORY_BUTTONS', "внутри блока с кнопками");
define('_JSHOP_BACF_POSITION_CATEGORY_BOTTOM_BUTTONS', "под блоком с кнопками");
define('_JSHOP_BACF_POSITION_CATEGORY_END', "после товара");

define('_JSHOP_BACF_MULTILANG', "Мультиязычное");
define('_JSHOP_BACF_MULTILANG_DESC', "Укажите, будет ли данное поле уникальным для каждого отдельного языка сайта или нет.");

define('_JSHOP_BACF_NO_TYPE_FIELD', "Не указан тип поля.");
define('_JSHOP_BACF_NO_VALUES_FIELD', "Не указаны значения поля.");
define('_JSHOP_BACF_YOUTUBE_INCORRECT', "Не корректная ссылка на видео Youtube.");
define('_JSHOP_BACF_DATETIME_INCORRECT', "Значение не является датой.");
define('_JSHOP_BACF_ERROR_UPLOADING_IMAGE', "Custom Fields - Error upload image. code: ");