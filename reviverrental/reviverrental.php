<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    Reviver <info@reviver.lt>
 *  @copyright 2015-2016 Reviver.lt
 *  @license   license.txt
 */

if (!defined('_CAN_LOAD_FILES_')) {
    exit;
}

include_once(dirname(__FILE__).'/MailAlert.php');

class ReviverRental extends Module
{
    public function __construct()
    {
        $this->name = 'reviverrental';
        $this->tab = 'administration';
        $this->version = '1.1.0';
        $this->author = 'Reviver.lt';
        $this->need_instance = 0;
        $this->bootstrap = false;
        $this->module_key = '3f4832668c1c07d3e545c0f4cd248ae0';

        parent::__construct();

        $this->displayName = $this->l('Reviver Rental Module');
        $this->description = $this->l('Allows users to rent products or services');
        $this->secure_key = Tools::encrypt($this->name);
    }

    public function install()
    {
        return (parent::install()
                and $this->createTables()
                and $this->registerHook('displayHeader')
                and $this->registerHook('displayFooter')
                and $this->registerHook('actionCartSave')
                and $this->registerHook('actionValidateOrder')
                and $this->registerHook('actionProductUpdate')
                and $this->registerHook('displayFooterProduct')
                and $this->registerHook('displayAdminProductsExtra'));
    }

    public function uninstall()
    {
        return (parent::uninstall()
                and $this->dropTables()
                and $this->unregisterHook('displayHeader')
                and $this->unregisterHook('displayFooter')
                and $this->unregisterHook('actionCartSave')
                and $this->unregisterHook('actionValidateOrder')
                and $this->unregisterHook('actionProductUpdate')
                and $this->unregisterHook('displayFooterProduct')
                and $this->unregisterHook('displayAdminProductsExtra'));
    }

    private function createTables()
    {
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_templates` (
                                    `id_template` INT(10) AUTO_INCREMENT NOT NULL,
                                    `name` VARCHAR(500) NOT NULL,
                                    `include_same_day` INT(1) NOT NULL,
                                    `discount_method` INT(1) NOT NULL,
                                    PRIMARY KEY (id_template)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_template_assigned` (
                                    `id_template_assigned` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_product` INT(10) NOT NULL,
                                    `id_template` INT(10) NOT NULL,
                                    `order` INT(10) NOT NULL,
                                    PRIMARY KEY (id_template_assigned)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_template_cat_assigned` (
                                    `id_template_cat_assigned` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_category` INT(10) NOT NULL,
                                    `id_template` INT(10) NOT NULL,
                                    PRIMARY KEY (id_template_cat_assigned)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_template_fields` (
                                    `id_field` INT(10) AUTO_INCREMENT NOT NULL,
                                    `type` VARCHAR(10) NOT NULL,
                                    `name` VARCHAR(500) NOT NULL,
                                    `title` VARCHAR(500) NOT NULL,
                                    `value` VARCHAR(500) NOT NULL,
                                    `placeholder` VARCHAR(500) NOT NULL,
                                    PRIMARY KEY (id_field)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_template_field_select` (
                                    `id_field_select` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_field` INT(10) NOT NULL,
                                    `name` VARCHAR(500) NOT NULL,
                                    `title` VARCHAR(500) NOT NULL,
                                    `value` VARCHAR(500) NOT NULL,
                                    `order` INT(10) NOT NULL,
                                    PRIMARY KEY (id_field_select)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_template_fields_assigned` (
                                    `id_template_field_assigned` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_field` INT(10) NOT NULL,
                                    `id_template` INT(10) NOT NULL,
                                    `order` INT(10) NOT NULL,
                                    PRIMARY KEY (id_template_field_assigned)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_template_products` (
                                    `id_template_product` INT(10) AUTO_INCREMENT NOT NULL,
                                    `name` VARCHAR(500) NOT NULL,
                                    `title` VARCHAR(500) NOT NULL,
                                    `description` VARCHAR(2000) NOT NULL,
                                    `image_path` VARCHAR(2000) NOT NULL,
                                    `price` decimal(20,2) NULL,
                                    `price_discount` decimal(20,2) NULL,
                                    PRIMARY KEY (id_template_product)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_template_products_assigned` (
                                    `id_template_product_assigned` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_template_product` INT(10) NOT NULL,
                                    `id_template` INT(10) NOT NULL,
                                    `order` INT(10) NOT NULL,
                                    PRIMARY KEY (id_template_product_assigned)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_template_coefficients` (
                                    `id_template_coefficient` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_template` INT(10) NOT NULL,
                                    `days_from` INT(10) NOT NULL,
                                    `days_to` INT(10) NOT NULL,
                                    `coefficient` decimal(20,2) NOT NULL,
                                    PRIMARY KEY (id_template_coefficient)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_carts` (
                                    `id_rental_cart` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_user` INT(10) NOT NULL,
                                    `token` VARCHAR(1000) NOT NULL,
                                    `id_cart` INT(10) NOT NULL,
                                    `id_template` INT(10) NOT NULL,
                                    `id_product` INT(10) NOT NULL,
                                    `id_product_attribute` INT(10) NOT NULL,
                                    `date` DATETIME NOT NULL,
                                    PRIMARY KEY (id_rental_cart)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_cart_values` (
                                    `id_rental_cart_value` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_rental_cart` INT(10) NOT NULL,
                                    `value_id` INT(10) NOT NULL,
                                    `value` VARCHAR(500) NOT NULL,
                                    `type` VARCHAR(500) NOT NULL,
                                    PRIMARY KEY (id_rental_cart_value)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_orders` (
                                    `id_rental_order` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_user` INT(10) NOT NULL,
                                    `token` VARCHAR(1000) NOT NULL,
                                    `id_order` INT(10) NOT NULL,
                                    `id_template` INT(10) NOT NULL,
                                    `id_product` INT(10) NOT NULL,
                                    `id_product_attribute` INT(10) NOT NULL,
                                    `date` DATETIME NOT NULL,
                                    PRIMARY KEY (id_rental_order)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_order_values` (
                                    `id_rental_order_value` INT(10) AUTO_INCREMENT NOT NULL,
                                    `id_rental_order` INT(10) NOT NULL,
                                    `value_id` INT(10) NOT NULL,
                                    `value` VARCHAR(500) NOT NULL,
                                    `type` VARCHAR(500) NOT NULL,
                                    PRIMARY KEY (id_rental_order_value)
                                   ) CHARSET=UTF8;");
        DB::getInstance()->execute("CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."reviver_rental_options` (
                                    `id_rental_option` INT(10) AUTO_INCREMENT NOT NULL,
                                    `name` VARCHAR(500) NOT NULL,
                                    `value` VARCHAR(500) NOT NULL,
                                    PRIMARY KEY (id_rental_option)
                                   ) CHARSET=UTF8;");

        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_options` (`id_rental_option`, `name`, `value`) VALUES ("", "cms_page", "0")');
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_options` (`id_rental_option`, `name`, `value`) VALUES ("", "email", "")');
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_options` (`id_rental_option`, `name`, `value`) VALUES ("", "orderby", "1")');
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_options` (`id_rental_option`, `name`, `value`) VALUES ("", "columns", "0")');
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_options` (`id_rental_option`, `name`, `value`) VALUES ("", "taxes", "0")');
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute_group` (`id_attribute_group`, `is_color_group`, `group_type`, `position`) VALUES ("", "0", "select", "0")');
        $created_id_attribute_group = Db::getInstance()->Insert_ID();
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_options` (`id_rental_option`, `name`, `value`) VALUES ("", "id_attribute_group", "'.(int)$created_id_attribute_group.'")');
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'image_type` (`id_image_type`, `name`, `width`, `height`, `products`, `categories`, `manufacturers`, `suppliers`, `scenes`, `stores`) VALUES ("", "reviver_rental", "450", "100", "1", "0", "0", "0", "0", "0")');


        $alllang = 'SELECT * FROM `'._DB_PREFIX_.'lang` WHERE `active` = 1';
        foreach (Db::getInstance()->executeS($alllang) as $singlelang) {
            $id_lang = $singlelang['id_lang'];
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute_group_lang` (`id_attribute_group`, `id_lang`, `name`, `public_name`) VALUES ("'.(int)$created_id_attribute_group.'", "'.(int)$id_lang.'", "Rent", "Rent")');
        }

        $allshop = 'SELECT * FROM `'._DB_PREFIX_.'shop` WHERE `active` = 1';
        foreach (Db::getInstance()->executeS($allshop) as $singleshop) {
            $id_shop = $singleshop['id_shop'];
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute_group_shop` (`id_attribute_group`, `id_shop`) VALUES ("'.(int)$created_id_attribute_group.'", "'.(int)$id_shop.'")');
        }

        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute` (`id_attribute`, `id_attribute_group`, `color`, `position`) VALUES ("", "'.(int)$created_id_attribute_group.'", "", "0")');
        $created_id_attribute = Db::getInstance()->Insert_ID();
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_options` (`id_rental_option`, `name`, `value`) VALUES ("", "id_attribute", "'.(int)$created_id_attribute.'")');

        $alllang = 'SELECT * FROM `'._DB_PREFIX_.'lang` WHERE `active` = 1';
        foreach (Db::getInstance()->executeS($alllang) as $singlelang) {
            $id_lang = $singlelang['id_lang'];
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute_lang` (`id_attribute`, `id_lang`, `name`) VALUES ("'.(int)$created_id_attribute.'", "'.(int)$id_lang.'", "information")');
        }

        $allshop = 'SELECT * FROM `'._DB_PREFIX_.'shop` WHERE `active` = 1';
        foreach (Db::getInstance()->executeS($allshop) as $singleshop) {
            $id_shop = $singleshop['id_shop'];
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute_shop` (`id_attribute`, `id_shop`) VALUES ("'.(int)$created_id_attribute.'", "'.(int)$id_shop.'")');
        }

        // Preventing duplicate emails, removing mailalerts hook
        $sql='SELECT `id_module` FROM `'._DB_PREFIX_.'module` WHERE `name`="mailalerts"';
        $query_result = Db::getInstance()->ExecuteS($sql);
        foreach ($query_result as $row) {
            $mailalerts_module_id = $row['id_module'];
        }

        // Inserting mailalerts hook exception to all shops
        $sql2='SELECT `id_shop` FROM `'._DB_PREFIX_.'shop` WHERE `active`="1"';
        $query_result2 = Db::getInstance()->ExecuteS($sql2);
        foreach ($query_result2 as $row2) {
            $shop_module_id = $row2['id_shop'];
            Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'hook_module_exceptions` VALUES ("", "'.(int)$shop_module_id.'", "'.(int)$mailalerts_module_id.'", "2", "module-reviver-rental-validation")');
        }

        return true;
    }

    private function dropTables()
    {
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_templates`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_assigned`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_cat_assigned`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_fields`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_field_select`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_field_assigned`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_products`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_products_assigned`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_carts`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_cart_values`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_orders`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_order_values`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_coefficients`");
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_template_fields_assigned`");

        $allopts = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options`';
        foreach (Db::getInstance()->executeS($allopts) as $singleopt) {
            $name = $singleopt['name'];
            $value = $singleopt['value'];
            if ($name == "id_attribute_group") {
                $id_attribute_group = $value;
            }
            if ($name == "id_attribute") {
                $id_attribute = $value;
            }
        }

        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_group` WHERE `id_attribute_group`='.(int)$id_attribute_group);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_group_lang` WHERE `id_attribute_group`='.(int)$id_attribute_group);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_group_shop` WHERE `id_attribute_group`='.(int)$id_attribute_group);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute` WHERE `id_attribute`='.(int)$id_attribute);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_lang` WHERE `id_attribute`='.(int)$id_attribute);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_shop` WHERE `id_attribute`='.(int)$id_attribute);
        DB::getInstance()->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."reviver_rental_options`");

        return true;
    }

    public function hookactionValidateOrder($params)
    {
        $cart = $params['cart'];
        $cartid = $cart->id;

        $sql99='SELECT `id_order` FROM `'._DB_PREFIX_.'orders` WHERE id_cart='.(int)$cartid;
        $query_result99 = Db::getInstance()->ExecuteS($sql99);
        foreach ($query_result99 as $row99) {
            $id_order_new = $row99['id_order'];
        }

        //Coping rental cart to order fields
        $sql='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE id_cart='.(int)$cartid;
        $query_result = Db::getInstance()->ExecuteS($sql);
        foreach ($query_result as $row) {
            $id_rental_cart = $row['id_rental_cart'];
            $id_user = $row['id_user'];
            $token = $row['token'];
            $id_template = $row['id_template'];
            $id_product = $row['id_product'];
            $id_product_attribute = $row['id_product_attribute'];
            $dateorder = date("Y-m-d H:i:s");

            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_orders` (`id_rental_order`, `id_user`, `token`, `id_order`, `id_template`, `id_product`, `id_product_attribute`, `date`) VALUES ("", "'.(int)$id_user.'", "'.pSQL($token).'", "'.(int)$id_order_new.'", "'.(int)$id_template.'", "'.(int)$id_product.'", "'.(int)$id_product_attribute.'", "'.pSQL($dateorder).'")');
            $created_id_template_order = Db::getInstance()->Insert_ID();

            $sql2='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_cart_values` WHERE id_rental_cart='.(int)$id_rental_cart;
            $query_result2 = Db::getInstance()->ExecuteS($sql2);
            foreach ($query_result2 as $row2) {
                $value_id = $row2['value_id'];
                $value = $row2['value'];
                $type = $row2['type'];
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_order_values` (`id_rental_order_value`, `id_rental_order`, `value_id`, `value`, `type`) VALUES ("", "'.(int)$created_id_template_order.'", "'.(int)$value_id.'", "'.pSQL($value).'", "'.pSQL($type).'")');
            }
        }

        // Deleting stickers cart and created temp attributes
        $sql='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE id_cart='.(int)$cartid;
        $query_result = Db::getInstance()->ExecuteS($sql);
        foreach ($query_result as $row) {
            $id_rental_cart = $row['id_rental_cart'];
            $id_product_attribute = $row['id_product_attribute'];
        }

        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE `id_cart`='.(int)$cartid);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_cart_values` WHERE `id_rental_cart`='.(int)$id_rental_cart);

        $sql2='SELECT `id_attribute` FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_product_attribute` = '.(int)$id_product_attribute.' ORDER BY `id_product_attribute` DESC LIMIT 1 ';
        $query_result2 = Db::getInstance()->ExecuteS($sql2);
        foreach ($query_result2 as $row2) {
            $id_attribute_del = $row2['id_attribute'];
        }

        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute` WHERE `id_product_attribute`='.(int)$id_product_attribute);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_product_attribute`='.(int)$id_product_attribute);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_shop` WHERE `id_product_attribute`='.(int)$id_product_attribute);

        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute` WHERE `id_attribute`='.(int)$id_attribute_del);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_lang` WHERE `id_attribute`='.(int)$id_attribute_del);
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_shop` WHERE `id_attribute`='.(int)$id_attribute_del);

        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'stock_available` WHERE `id_product_attribute`='.(int)$id_product_attribute);

        // Email variables
        $id_lang = (int)Context::getContext()->language->id;
        $currency = $params['currency'];
        $configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_MAIL_METHOD', 'PS_MAIL_SERVER', 'PS_MAIL_USER', 'PS_MAIL_PASSWD', 'PS_SHOP_NAME'));
        $order = $params['order'];
        $customer = $params['customer'];
        $delivery = new Address((int)$order->id_address_delivery);
        $invoice = new Address((int)$order->id_address_invoice);
        $order_date_text = Tools::displayDate($order->date_add, (int)$id_lang);
        $carrier = new Carrier((int)$order->id_carrier);
        $message = $order->getFirstMessage();

        if (!$message || empty($message)) {
            $message = $this->l('No message');
        }

        $products = $params['order']->getProducts();
        $customized_datas = Product::getAllCustomizedDatas((int)$params['cart']->id);
        Product::addCustomizationPrice($products, $customized_datas);
        
        $rent_fields = "";
        $rent_prods = "";
        $rental_products = "";
        $idnumber = "";
        $allproducts = "";
        $rent_fid = "";
        $rent_pid = "";

        foreach ($products as $product) {
            
            $idnumber = $product['product_id'];
            
            $allproducts[$idnumber]['product_id'] = $product['product_id'];
            $allproducts[$idnumber]['product_reference'] = $product['product_reference'];
            $allproducts[$idnumber]['product_name'] = $product['product_name'];
            $allproducts[$idnumber]['price'] = Tools::displayPrice($product['product_price_wt'], $currency, false);
            $allproducts[$idnumber]['product_quantity'] = $product['product_quantity'];
            $sum = $product['product_price_wt'] * $product['product_quantity'];
            $allproducts[$idnumber]['sum'] = Tools::displayPrice($sum, $currency, false);
            
            $rental_product = "no";
            $id_rental_order = 0;

            //Checking if product is for rent
            $sql_rent='SELECT COUNT(*) FROM `'._DB_PREFIX_.'reviver_rental_orders` WHERE `id_order`="'.(int)$order->id.'" AND `id_product`="'.(int)$product['product_id'].'" AND `id_product_attribute`="'.(int)$product['product_attribute_id'].'"';
            $found_rent = Db::getInstance()->getValue($sql_rent);
            if ($found_rent > 0) {
                $rental_product = "yes";
                $sql_rent2='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_orders` WHERE `id_order`="'.(int)$order->id.'" AND `id_product`="'.(int)$product['product_id'].'" AND `id_product_attribute`="'.(int)$product['product_attribute_id'].'"';
                $query_result_rent2 = Db::getInstance()->ExecuteS($sql_rent2);
                foreach ($query_result_rent2 as $row_rent2) {
                    $id_rental_order = $row_rent2['id_rental_order'];
                }
            }

            if ($rental_product == "yes") {
                
                $rental_products[$idnumber] = $product['product_id'];

                $sql_rent2='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_order_values` WHERE id_rental_order='.(int)$id_rental_order.' ORDER BY id_rental_order_value ASC';
                $query_result_rent2 = Db::getInstance()->ExecuteS($sql_rent2);
                foreach ($query_result_rent2 as $row_rent2) {
                    $value_id = $row_rent2['value_id'];
                    $value = $row_rent2['value'];
                    $type = $row_rent2['type'];

                    if ($type == "field") {
                        $sql_rent_fld='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields` WHERE id_field='.(int)$value_id;
                        $query_result_rent_fld = Db::getInstance()->ExecuteS($sql_rent_fld);
                        foreach ($query_result_rent_fld as $row_rent_fld) {
                            $title = $row_rent_fld['title'];
                        }
                        $rent_fields[$rent_fid]['product_id'] = $idnumber;
                        $rent_fields[$rent_fid]['title'] = $title;
                        $rent_fields[$rent_fid]['value'] = $value;
                        $rent_fid++;
                    }

                    if ($type == "product") {
                        $sql_rent_fld='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` WHERE id_template_product='.(int)$value_id;
                        $query_result_rent_fld = Db::getInstance()->ExecuteS($sql_rent_fld);
                        foreach ($query_result_rent_fld as $row_rent_fld) {
                            $title2 = $row_rent_fld['title'];
                            $price2 = $row_rent_fld['price'];
                        }
                        
                        $rent_prods[$rent_pid]['product_id'] = $idnumber;
                        $rent_prods[$rent_pid]['title'] = $title2;
                        $rent_prods[$rent_pid]['value'] = '('.Tools::displayPrice($price2, $currency, false).')';
                        $rent_pid++;
                    }
                }
            }
        }
        
        $vouchers = '';
        $idnumber = '';
        
        foreach ($params['order']->getCartRules() as $discount) {
            
            $vouchers[$idnumber]['name'] = $discount['name'];
            $vouchers[$idnumber]['value'] = Tools::displayPrice($discount['value'], $currency, false);
        }

        if ($delivery->id_state) {
            $delivery_state = new State((int)$delivery->id_state);
        }

        if ($invoice->id_state) {
            $invoice_state = new State((int)$invoice->id_state);
        }

        // Filling-in vars for email
        $ps_16_f_min = (int)(version_compare(_PS_VERSION_, '1.6.0.1'));

        if ($ps_16_f_min >= 0) {
            $template_mail = "new_order";
        } else {
            $template_mail = "new_order_15";
        }
        
        $this->context->smarty->assign('rental_products', $rental_products);
        $this->context->smarty->assign('vouchers', $vouchers);
        $this->context->smarty->assign('rent_fields', $rent_fields);
        $this->context->smarty->assign('rent_prods', $rent_prods);
        $this->context->smarty->assign('allproducts', $allproducts);
            
        $items = $this->context->smarty->fetch($this->local_path.'views/templates/admin/email_products.tpl');

        $template_vars = array('{firstname}' => $customer->firstname,
                               '{lastname}' => $customer->lastname,
                               '{email}' => $customer->email,
                               '{delivery_block_txt}' => MailAlertRental::getFormatedAddress($delivery, "\n"),
                               '{invoice_block_txt}' => MailAlertRental::getFormatedAddress($invoice, "\n"),
                               '{delivery_block_html}' => MailAlertRental::getFormatedAddress($delivery, "\n", array(
                                   'firstname' => '%s',
                                   'lastname' => '%s')),
                               '{invoice_block_html}' => MailAlertRental::getFormatedAddress($invoice, "\n", array(
                                   'firstname' => '%s',
                                   'lastname' => '%s')),
                               '{delivery_company}' => $delivery->company,
                               '{delivery_firstname}' => $delivery->firstname,
                               '{delivery_lastname}' => $delivery->lastname,
                               '{delivery_address1}' => $delivery->address1,
                               '{delivery_address2}' => $delivery->address2,
                               '{delivery_city}' => $delivery->city,
                               '{delivery_postal_code}' => $delivery->postcode,
                               '{delivery_country}' => $delivery->country,
                               '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                               '{delivery_phone}' => $delivery->phone,
                               '{delivery_other}' => $delivery->other,
                               '{invoice_company}' => $invoice->company,
                               '{invoice_firstname}' => $invoice->firstname,
                               '{invoice_lastname}' => $invoice->lastname,
                               '{invoice_address2}' => $invoice->address2,
                               '{invoice_address1}' => $invoice->address1,
                               '{invoice_city}' => $invoice->city,
                               '{invoice_postal_code}' => $invoice->postcode,
                               '{invoice_country}' => $invoice->country,
                               '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                               '{invoice_phone}' => $invoice->phone,
                               '{invoice_other}' => $invoice->other,
                               '{order_name}' => sprintf('%06d', $order->id),
                               '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                               '{date}' => $order_date_text,
                               '{carrier}' => (($carrier->name == '0') ? Configuration::get('PS_SHOP_NAME') : $carrier->name),
                               '{payment}' => Tools::substr($order->payment, 0, 32),
                               '{total_paid}' => Tools::displayPrice($order->total_paid, $currency),
                               '{total_products}' => Tools::displayPrice($order->getTotalProductsWithTaxes(), $currency),
                               '{total_discounts}' => Tools::displayPrice($order->total_discounts, $currency),
                               '{total_shipping}' => Tools::displayPrice($order->total_shipping, $currency),
                               '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $currency),
                               '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $currency),
                               '{currency}' => $currency->sign,
                               '{items}' => $items,
                               '{message}' => $message
                              );

        $iso = Language::getIsoById($id_lang);

        $allsett2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="email"';
        foreach (Db::getInstance()->executeS($allsett2) as $singlesett2) {
            $email_rec = $singlesett2['value'];
        }

        $merchant_mails = explode(",", $email_rec);

        foreach ($merchant_mails as $merchant_mail) {
            if (file_exists(dirname(__FILE__).'/mails/'.$iso.'/'.$template_mail.'.txt') &&
                file_exists(dirname(__FILE__).'/mails/'.$iso.'/'.$template_mail.'.html')) {
                Mail::Send($id_lang, $template_mail, sprintf(Mail::l('New order - #%06d', $id_lang), $order->id), $template_vars, $merchant_mail, null, $configuration['PS_SHOP_EMAIL'], $configuration['PS_SHOP_NAME'], null, null, dirname(__FILE__).'/mails/');
            }
        }
    }

    public function hookactionCartSave($params)
    {
        $cart = $params['cart'];
        if ($cart != "") {
            $cartid = $cart->id;
        } else {
            $cartid = "";
            $id_rental_cart = "";
            $id_product_attribute = "";
            $id_attribute_del = "";
        }
        
        $added = Tools::getIsset('add');
        $prod_type = pSQL(trim(Tools::getValue('type')));
        $added_product = (int)Tools::getValue('id_product');
        $added_token = pSQL(trim(Tools::getValue('token')));
        $added_id_template = (int)Tools::getValue('id_template');
        $added_id_user = (int)$this->context->cookie->id_customer;

        $id_shop = (int)Context::getContext()->shop->id;
        $id_group = (int)$this->context->cookie->id_group;
        if ($cart != "") {
            $id_currency = (int)$this->context->currency->id;
            $address = new Address($cart->id_address_delivery);
            $id_country = $address->id_country;
        } else {
            $id_currency = "";
            $address = "";
            $id_country = "";
        }
        $specific_price = "";

        $specific_price = SpecificPrice::getSpecificPrice((int)$added_product, $id_shop, $id_currency, $id_country, $id_group, 1, 0, $added_id_user, $cartid, 1);

        if ($specific_price["reduction"] != "") {
            $prod_discount_type = $specific_price["reduction_type"];
            $prod_discount = $specific_price["reduction"];
        }

        if (($added_product > 0) && ($added == 1) && ($cartid > 0) && ($prod_type == "rental")) {
            // Saving added product to database

            $added_date = date("Y-m-d H:i:s");

            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_carts` (`id_rental_cart`, `id_user`, `token`, `id_cart`, `id_template`, `id_product`, `id_product_attribute`, `date`) VALUES ("", "'.(int)$added_id_user.'", "'.pSQL($added_token).'", "'.(int)$cartid.'", "'.(int)$added_id_template.'", "'.(int)$added_product.'", "0", "'.pSQL($added_date).'")');
            $created_cart_id = Db::getInstance()->Insert_ID();

            $date1 = "";
            $date2 = "";

            // Saving every field and additional product
            foreach ($_POST as $key2 => $value2) {
                $key = addslashes(strip_tags(trim($key2)));
                $value = addslashes(strip_tags(trim($value2)));

                if (($key != "id_product") && ($key != "add") && ($key != "id_product_attribute") && ($key != "Submit")  && ($key != "token") && ($key != "type")) {

                    // Empty field found, stop
                    if ($value == "") {
                        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE `id_rental_cart`='.(int)$created_cart_id);
                        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_cart_values` WHERE `id_rental_cart`='.(int)$created_cart_id);
                        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'cart_product` WHERE `id_cart`='.(int)$cartid);
                        
                        $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="cms_page"';
                        foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                            $id_current_cms = $singlesett['value'];
                        }
                        
                        $this->context->cookie->reviver_rental_error = "1";
                        $link = $this->context->link->getCMSLink($id_current_cms);
                        Tools::redirectAdmin($link);
                        exit();
                    }

                    if (strpos($key, 'date1-') !== false) {
                        $getid = explode("-", $key);
                        $valuid = $getid[1];
                        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_cart_values` (`id_rental_cart_value`, `id_rental_cart`, `value_id`, `value`, `type`) VALUES ("", "'.(int)$created_cart_id.'", "'.(int)$valuid.'", "'.pSQL($value).'", "field")');
                        $date1 = strtotime($value);
                    }

                    if (strpos($key, 'date2-') !== false) {
                        $getid = explode("-", $key);
                        $valuid = $getid[1];
                        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_cart_values` (`id_rental_cart_value`, `id_rental_cart`, `value_id`, `value`, `type`) VALUES ("", "'.(int)$created_cart_id.'", "'.(int)$valuid.'", "'.pSQL($value).'", "field")');
                        $date2 = strtotime($value);
                    }

                    if (strpos($key, 'fieldid-') !== false) {
                        $getid = explode("-", $key);
                        $valuid = $getid[1];
                        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_cart_values` (`id_rental_cart_value`, `id_rental_cart`, `value_id`, `value`, `type`) VALUES ("", "'.(int)$created_cart_id.'", "'.(int)$valuid.'", "'.pSQL($value).'", "field")');
                    }

                    if (strpos($key, 'productid-') !== false) {
                        $getid = explode("-", $key);
                        $valuid = $getid[1];
                        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_cart_values` (`id_rental_cart_value`, `id_rental_cart`, `value_id`, `value`, `type`) VALUES ("", "'.(int)$created_cart_id.'", "'.(int)$valuid.'", "'.pSQL($value).'", "product")');
                    }
                }
            }

            $templatesettings = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_templates` WHERE `id_template`='.(int)$added_id_template;
            foreach (Db::getInstance()->executeS($templatesettings) as $singletemplatesettings) {
                $include_same_day = $singletemplatesettings['include_same_day'];
                $discount_method = $singletemplatesettings['discount_method'];
            }

            $datediff = ceil(($date2 - $date1) / 86400);
            $datediff = $datediff + $include_same_day;

            $now = date('Y-m-d');
            $now = strtotime($now);

            // Date error found, stop
            if ($now > $date1) {
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE `id_rental_cart`='.(int)$created_cart_id);
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_cart_values` WHERE `id_rental_cart`='.(int)$created_cart_id);
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'cart_product` WHERE `id_cart`='.(int)$cartid);
                
                $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="cms_page"';
                foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                    $id_current_cms = $singlesett['value'];
                }

                $this->context->cookie->reviver_rental_error = "1";
                $link = $this->context->link->getCMSLink($id_current_cms);
                Tools::redirect($link);
                exit();
            }

            // Making new attribute name
            $allfields = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_cart_values` WHERE `id_rental_cart` = '.(int)$created_cart_id.' AND `type` = "field" ORDER BY `id_rental_cart_value` ASC';
            $attribute_value = "";
            foreach (Db::getInstance()->executeS($allfields) as $singlefield) {
                $value = $singlefield['value'];
                $value_id = $singlefield['value_id'];

                $allfields2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields` WHERE `id_field` = '.(int)$value_id;
                foreach (Db::getInstance()->executeS($allfields2) as $singlefield2) {
                    $field_name = $singlefield2['name'];
                }
                $attribute_value .= "$field_name-$value,";
            }

            $attribute_value .= ''.$this->l('additional').'-';
            $sum_add = 0;

            $allprods = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_cart_values` WHERE `id_rental_cart` = '.(int)$created_cart_id.' AND `type` = "product" ORDER BY `id_rental_cart_value` ASC';
            foreach (Db::getInstance()->executeS($allprods) as $singleprod) {
                $value = $singleprod['value'];
                $value_id = $singleprod['value_id'];

                $allprods2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` WHERE `id_template_product` = '.(int)$value_id;
                foreach (Db::getInstance()->executeS($allprods2) as $singleprod2) {
                    $price_add = $singleprod2['price'];
                    $price_add_real = (float)($price_add);

                    if ($discount_method == 0) {
                        if ($prod_discount_type == "percentage") {
                        } else {
                            $price_add_real = $price_add_real - $prod_discount;
                        }
                    }
                }

                $sum_add = $sum_add + $price_add_real;
                $attribute_value .= "$value,";
            }

            $alltaxgr0 = 'SELECT * FROM `'._DB_PREFIX_.'product` WHERE `id_product`="'.(int)$added_product.'"';
            foreach (Db::getInstance()->executeS($alltaxgr0) as $singletaxgr0) {
                $prod_id_tax_rules_group = $singletaxgr0['id_tax_rules_group'];
                $prod_price_wt = $singletaxgr0['price'];
            }
            $alltaxgr1 = 'SELECT * FROM `'._DB_PREFIX_.'tax_rule` WHERE `id_tax_rules_group`="'.(int)$prod_id_tax_rules_group.'"';
            foreach (Db::getInstance()->executeS($alltaxgr1) as $singletaxgr1) {
                $prod_tax = $singletaxgr1['id_tax'];
            }
            $alltaxgr2 = 'SELECT * FROM `'._DB_PREFIX_.'tax` WHERE `id_tax`="'.(int)$prod_tax.'"';
            foreach (Db::getInstance()->executeS($alltaxgr2) as $singletaxgr2) {
                $prod_tax_rate = $singletaxgr2['rate'];
            }
            
            $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="taxes"';
            foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                $taxesset = $singlesett['value'];
            }
            
            if ($taxesset == 1) {
                $sum_add = $sum_add / ((100 + $prod_tax_rate) / 100);
            } else {
                $sum_add = $sum_add;
            }

            $attribute_value = trim($attribute_value, ",");

            $allsettings = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options`';
            foreach (Db::getInstance()->executeS($allsettings) as $singlesetting) {
                if ($singlesetting['name'] == "id_attribute_group") {
                    $attribute_group_add = $singlesetting['value'];
                }
            }

            $sql_coe='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_coefficients` WHERE id_template = '.(int)$added_id_template.'';
            $query_result_coe = Db::getInstance()->ExecuteS($sql_coe);
            $days_from = 0;
            $days_to = 0;
            $coef_temp = 0;
            foreach ($query_result_coe as $row_coe) {
                $days_from = $row_coe['days_from'];
                $days_to = $row_coe['days_to'];
                $coef_temp = $row_coe['coefficient'];
                if (($datediff >= $days_from) && ($datediff <= $days_to)) {
                    $coef = $coef_temp;
                }
            }

            if ($coef == 0) {
                $coef = 1;
            }

            if ($datediff == 0) {
                $datediff = 1 * $coef;
            } else {
                $datediff = $datediff * $coef;
            }

            $sum_add_prd = $sum_add * $datediff;
            $sum_price = $prod_price_wt * $datediff;

            $sum_price = $sum_price - $prod_price_wt;

            if ($specific_price["reduction"] != "") {
                $prod_discount_type = $specific_price["reduction_type"];
                $prod_discount = $specific_price["reduction"];

            }

            if (($prod_discount_type == "percentage") && ($discount_method == 1)) {

                // formula for percentage taxed product but not for additional products
                $percent = 1 - $specific_price["reduction"];
                $sum_add = ($sum_price * $percent + $sum_add_prd) / $percent;
            } else {
                $sum_add = $sum_add_prd + $sum_price;
            }

            if ($specific_price["reduction"] != "") {
                $prod_discount_type = $specific_price["reduction_type"];
                $prod_discount = $specific_price["reduction"];

                if ($prod_discount_type == "amount") {
                    $minus_discount = $prod_discount * ($datediff - 1);
                    if ($taxesset == 1) {
                        $minus_discount = $minus_discount / ((100 + $prod_tax_rate) / 100);
                    } else {
                        $minus_discount = $minus_discount;
                    }
                    $sum_add = $sum_add - $minus_discount;
                }
            }

            // Creating new attribute, updating product
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute` (`id_attribute`, `id_attribute_group`, `color`, `position`) VALUES ("", "'.(int)$attribute_group_add.'", "", "0")');
            $created_id_attribute = Db::getInstance()->Insert_ID();

            $alllang = 'SELECT * FROM `'._DB_PREFIX_.'lang` WHERE `active` = 1';
            foreach (Db::getInstance()->executeS($alllang) as $singlelang) {
                $id_lang = $singlelang['id_lang'];
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute_lang` (`id_attribute`, `id_lang`, `name`) VALUES ("'.(int)$created_id_attribute.'", "'.(int)$id_lang.'", "'.pSQL($attribute_value).'")');
            }

            $allshop = 'SELECT * FROM `'._DB_PREFIX_.'shop` WHERE `active` = 1';
            foreach (Db::getInstance()->executeS($allshop) as $singleshop) {
                $id_shop = $singleshop['id_shop'];
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'attribute_shop` (`id_attribute`, `id_shop`) VALUES ("'.(int)$created_id_attribute.'", "'.(int)$id_shop.'")');
            }

            // PS 1.6.0.6 db change
            $ps_16_att_change = (int)(version_compare(_PS_VERSION_, '1.6.0.5'));
            if ($ps_16_att_change > 0) {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute` (`id_product_attribute`, `id_product`, `reference`, `supplier_reference`, `location`, `ean13`, `upc`, `wholesale_price`, `price`, `ecotax`, `quantity`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("", "'.(int)$added_product.'", "", "", "", "", "", "0.000000", "'.(float)$sum_add.'", "0.000000", "1000", "0.000000", "'.(float)$sum_add.'", NULL, "1", "0.00")');
            } else {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute` (`id_product_attribute`, `id_product`, `reference`, `supplier_reference`, `location`, `ean13`, `upc`, `wholesale_price`, `price`, `ecotax`, `quantity`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("", "'.(int)$added_product.'", "", "", "", "", "", "0.000000", "'.(float)$sum_add.'", "0.000000", "1000", "0.000000", "'.(float)$sum_add.'", "0", "1", "0.00")');
            }
            $created_id_product_attribute = Db::getInstance()->Insert_ID();

            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_combination` (`id_attribute`, `id_product_attribute`) VALUES ("'.(int)$created_id_attribute.'", "'.(int)$created_id_product_attribute.'")');

            $allshop = 'SELECT * FROM `'._DB_PREFIX_.'shop` WHERE `active` = 1';
            foreach (Db::getInstance()->executeS($allshop) as $singleshop) {
                $id_shop = $singleshop['id_shop'];

                // PS 1.6.0.6 db change
                $ps_16_att_change = (int)(version_compare(_PS_VERSION_, '1.6.0.5'));
                if ($ps_16_att_change > 0) {
                    Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_shop` (`id_product`, `id_product_attribute`, `id_shop`, `wholesale_price`, `price`, `ecotax`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("'.(int)$added_product.'", "'.(int)$created_id_product_attribute.'", "'.(int)$id_shop.'", "0.000000", "'.(float)$sum_add.'", "0.000000", "0.000000", "'.(float)$sum_add.'", NULL, "1", "0000-00-00")');
                } else {
                    Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_shop` (`id_product_attribute`, `id_shop`, `wholesale_price`, `price`, `ecotax`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("'.(int)$created_id_product_attribute.'", "'.(int)$id_shop.'", "0.000000", "'.(float)$sum_add.'", "0.000000", "0.000000", "'.(float)$sum_add.'", "0", "1", "0000-00-00")');
                }
            }

            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'stock_available` (`id_stock_available`, `id_product`, `id_product_attribute`, `id_shop`, `id_shop_group`, `quantity`, `depends_on_stock`, `out_of_stock`) VALUES ("", "'.(int)$added_product.'", "'.(int)$created_id_product_attribute.'", "1", "0", "1000", "0", "1")');

            $allwarehouse = 'SELECT * FROM `'._DB_PREFIX_.'warehouse` WHERE `deleted` = 0';
            foreach (Db::getInstance()->executeS($allwarehouse) as $singlewarehouse) {
                $id_warehouse = $singlewarehouse['id_warehouse'];
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'warehouse_product_location` (`id_warehouse_product_location`, `id_product`, `id_product_attribute`, `id_warehouse`, `location`) VALUES ("", "'.(int)$added_product.'", "'.(int)$created_id_product_attribute.'", "'.(int)$id_warehouse.'", "")');
            }

            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'cart_product` SET `id_product_attribute`="'.(int)$created_id_product_attribute.'" WHERE `id_cart`='.(int)$cartid.' AND `id_product`='.(int)$added_product.' ORDER BY `date_add` DESC LIMIT 1');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_carts` SET `id_product_attribute`="'.(int)$created_id_product_attribute.'" WHERE `id_cart`='.(int)$cartid.' AND `id_product`='.(int)$added_product.' AND `id_product_attribute`="0"');
        } else {

            // Removing deleted products from database
            $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'cart_product WHERE `id_cart`="'.(int)$cartid.'"';
            $found = Db::getInstance()->getValue($foundsql);
            if ($found > 0) {
                $prod_found = "no";

                $sql3='SELECT `id_product`, `id_product_attribute` FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE `id_cart`='.(int)$cartid;
                $query_result3 = Db::getInstance()->ExecuteS($sql3);
                foreach ($query_result3 as $row3) {
                    $id_product_real = $row3['id_product'];
                    $id_product_att_real = $row3['id_product_attribute'];

                    $sql4='SELECT `id_product`, `id_product_attribute` FROM `'._DB_PREFIX_.'cart_product` WHERE `id_cart`='.(int)$cartid;
                    $query_result4 = Db::getInstance()->ExecuteS($sql4);
                    foreach ($query_result4 as $row4) {
                        $id_product_cart = $row4['id_product'];
                        $id_product_att_cart = $row4['id_product_attribute'];
                        if (($id_product_real == $id_product_cart) && ($id_product_att_real == $id_product_att_cart)) {
                            $prod_found = "yes";
                        }
                    }

                    if ($prod_found == "no") {
                        if (($id_product_real > 0) && ($id_product_att_real > 0)) {
                            $sql2='SELECT `id_rental_cart` FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE `id_cart` = '.(int)$cartid.' AND `id_product`='.(int)$id_product_real.' AND `id_product_attribute`='.(int)$id_product_att_real;
                            $query_result2 = Db::getInstance()->ExecuteS($sql2);
                            foreach ($query_result2 as $row2) {
                                $id_rental_cart = $row2['id_rental_cart'];
                            }

                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE `id_cart`='.(int)$cartid.' AND `id_product`='.(int)$id_product_real.' AND `id_product_attribute`='.(int)$id_product_att_real);
                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_cart_values` WHERE `id_rental_cart`='.(int)$id_rental_cart);

                            $sql2='SELECT `id_attribute` FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_product_attribute` = '.(int)$id_product_att_real.' ORDER BY `id_product_attribute` DESC LIMIT 1 ';
                            $query_result2 = Db::getInstance()->ExecuteS($sql2);
                            foreach ($query_result2 as $row2) {
                                $id_attribute_del = $row2['id_attribute'];
                            }

                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute` WHERE `id_product_attribute`='.(int)$id_product_att_real);
                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_product_attribute`='.(int)$id_product_att_real);
                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_shop` WHERE `id_product_attribute`='.(int)$id_product_att_real);

                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute` WHERE `id_attribute`='.(int)$id_attribute_del);
                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_lang` WHERE `id_attribute`='.(int)$id_attribute_del);
                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_shop` WHERE `id_attribute`='.(int)$id_attribute_del);

                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'stock_available` WHERE `id_product_attribute`='.(int)$id_product_att_real);
                            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'warehouse_product_location` WHERE `id_product_attribute`='.(int)$id_product_att_real);
                        }
                    }
                }
            } else {
                $sql='SELECT `id_rental_cart`, `id_product_attribute` FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE id_cart='.(int)$cartid;
                $query_result = Db::getInstance()->ExecuteS($sql);
                foreach ($query_result as $row) {
                    $id_rental_cart = $row['id_rental_cart'];
                    $id_product_attribute = $row['id_product_attribute'];
                }

                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_carts` WHERE `id_cart`='.(int)$cartid);
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_cart_values` WHERE `id_rental_cart`='.(int)$id_rental_cart);

                $sql2='SELECT `id_attribute` FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_product_attribute` = '.(int)$id_product_attribute.' ORDER BY `id_product_attribute` DESC LIMIT 1 ';
                $query_result2 = Db::getInstance()->ExecuteS($sql2);
                foreach ($query_result2 as $row2) {
                    $id_attribute_del = $row2['id_attribute'];
                }

                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute` WHERE `id_product_attribute`='.(int)$id_product_attribute);
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_product_attribute`='.(int)$id_product_attribute);
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_shop` WHERE `id_product_attribute`='.(int)$id_product_attribute);

                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute` WHERE `id_attribute`='.(int)$id_attribute_del);
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_lang` WHERE `id_attribute`='.(int)$id_attribute_del);
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'attribute_shop` WHERE `id_attribute`='.(int)$id_attribute_del);

                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'stock_available` WHERE `id_product_attribute`='.(int)$id_product_attribute);
            }
        }
    }

    public function hookdisplayFooterProduct()
    {
        $id_product = (int)Tools::getValue('id_product');
        $found = "no";

        $sql='SELECT `id_product` FROM `'._DB_PREFIX_.'reviver_rental_template_assigned` WHERE `id_product`='.(int)$id_product;
        $query_result = Db::getInstance()->ExecuteS($sql);
        foreach ($query_result as $row) {
            $id_product_found = $row['id_product'];
            if ($id_product_found == $id_product) {
                $found = "yes";
            }
        }

        // Redirect rental product page to list
        if ($found == "yes") {
            $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="cms_page"';
            foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                $id_current_cms = $singlesett['value'];
            }

            $link = $this->context->link->getCMSLink($id_current_cms);
            Tools::redirectAdmin($link);
        }
    }

    public function hookdisplayFooter($params)
    {

        $products_arr = "";
        $product_info = "";
        $product_flds_sel = "";
        $product_flds = "";
        $product_prods = "";
        $product_coe = "";
        $alltemplates = "";
        $number = "";
        
        $id_cms_page = (int)Tools::getValue('id_cms');
        $controller_name = pSQL(trim(Tools::getValue('controller')));

        $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="cms_page"';
        foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
            $id_current_cms = $singlesett['value'];
        }
        $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="orderby"';
        foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
            $orderbysett = $singlesett['value'];
        }
        $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="columns"';
        foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
            $columnsset = $singlesett['value'];
        }
        $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="taxes"';
        foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
            $taxesset = $singlesett['value'];
        }

        if (($controller_name == "cms") && ($id_cms_page == $id_current_cms)) {
            $lang_id = (int)$this->context->language->id;

            $sql='SELECT '._DB_PREFIX_.'reviver_rental_template_assigned.id_product, '._DB_PREFIX_.'reviver_rental_template_assigned.id_template, '._DB_PREFIX_.'reviver_rental_template_assigned.order, '._DB_PREFIX_.'reviver_rental_template_assigned.id_template_assigned, '._DB_PREFIX_.'product_lang.name, '._DB_PREFIX_.'product_lang.description_short, '._DB_PREFIX_.'product_shop.price, '._DB_PREFIX_.'product_shop.id_tax_rules_group FROM `'._DB_PREFIX_.'reviver_rental_template_assigned`
        LEFT JOIN '._DB_PREFIX_.'product_lang 
        ON '._DB_PREFIX_.'reviver_rental_template_assigned.id_product = '._DB_PREFIX_.'product_lang.id_product 
        LEFT JOIN '._DB_PREFIX_.'product_shop 
        ON '._DB_PREFIX_.'reviver_rental_template_assigned.id_product = '._DB_PREFIX_.'product_shop.id_product 
        WHERE '._DB_PREFIX_.'product_lang.id_lang = '.(int)$lang_id.'';

            if ($orderbysett == 1) {
                $sql .= ' ORDER BY '._DB_PREFIX_.'product_lang.name ASC';
            }
            if ($orderbysett == 2) {
                $sql .= ' ORDER BY '._DB_PREFIX_.'product_lang.name DESC';
            }
            if ($orderbysett == 3) {
                $sql .= ' ORDER BY '._DB_PREFIX_.'reviver_rental_template_assigned.id_template_assigned ASC';
            }
            if ($orderbysett == 4) {
                $sql .= ' ORDER BY '._DB_PREFIX_.'reviver_rental_template_assigned.id_template_assigned DESC';
            }
            if ($orderbysett == 5) {
                $sql .= ' ORDER BY '._DB_PREFIX_.'reviver_rental_template_assigned.order ASC';
            }
            if ($orderbysett == 6) {
                $sql .= ' ORDER BY '._DB_PREFIX_.'reviver_rental_template_assigned.order DESC';
            }

            $query_result = Db::getInstance()->ExecuteS($sql);
            foreach ($query_result as $row) {
                $prod_id = $row['id_product'];
                $prod_name = $row['name'];
                $prod_desc = $row['description_short'];
                $prod_price_wt = $row['price'];
                $prod_id_template = $row['id_template'];
                $prod_id_tax_rules_group = $row['id_tax_rules_group'];

                $id_shop = (int)Context::getContext()->shop->id;
                $id_customer = (int)$this->context->cookie->id_customer;
                $id_group = (int)$this->context->cookie->id_group;
                $id_currency = (int)$this->context->currency->id;
                $cart = $params['cart'];
                $id_cart = $cart->id;
                $address = new Address($cart->id_address_delivery);
                $id_country = $address->id_country;
                
                $specific_price = "";
                $price_sale = "";

                $specific_price = SpecificPrice::getSpecificPrice((int)$prod_id, $id_shop, $id_currency, $id_country, $id_group, 1, 0, $id_customer, $id_cart, 1);

                $alltaxgr = 'SELECT * FROM `'._DB_PREFIX_.'tax_rule` WHERE `id_tax_rules_group`="'.(int)$prod_id_tax_rules_group.'"';
                foreach (Db::getInstance()->executeS($alltaxgr) as $singletaxgr) {
                    $prod_tax = $singletaxgr['id_tax'];
                }
                $alltaxgr1 = 'SELECT * FROM `'._DB_PREFIX_.'tax` WHERE `id_tax`="'.(int)$prod_tax.'"';
                foreach (Db::getInstance()->executeS($alltaxgr1) as $singletaxgr1) {
                    $prod_tax_rate = $singletaxgr1['rate'];
                }

                if ($taxesset == 1) {
                    $prod_price = $row['price'] * (100 + $prod_tax_rate) / 100;
                } else {
                    $prod_price = $row['price'];
                }

                if ($specific_price["reduction"] != "") {
                    if ($specific_price["reduction_type"] == "percentage") {
                        $price_sale = $prod_price - $prod_price * $specific_price["reduction"];
                    }
                    if ($specific_price["reduction_type"] == "amount") {
                        $price_sale = $prod_price - $specific_price["reduction"];
                    }
                }

                $id_image = Product::getCover($prod_id);
                if (sizeof($id_image) > 0) {
                    $image = new Image($id_image['id_image']);
                    $image_url = _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath()."-reviver_rental.jpg";
                }


                $alltemplates = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_templates` WHERE `id_template` = '.(int)$prod_id_template.'';
                foreach (Db::getInstance()->executeS($alltemplates) as $singletemplate) {
                    $include_same_day = $singletemplate['include_same_day'];
                    $discount_method = $singletemplate['discount_method'];
                }

                $products_arr[] = $prod_id;
                $product_info[$prod_id]["name"] = $prod_name;
                $product_info[$prod_id]["id_template"] = $prod_id_template;
                $product_info[$prod_id]["include_same_day"] = $include_same_day;
                $product_info[$prod_id]["discount_method"] = $discount_method;
                $product_info[$prod_id]["description"] = $prod_desc;
                $product_info[$prod_id]["price_wt"] = $prod_price_wt;
                $product_info[$prod_id]["price"] = $prod_price;
                $product_info[$prod_id]["image"] = $image_url;
                $product_info[$prod_id]["reduction"] = $specific_price["reduction"];
                $product_info[$prod_id]["reduction_type"] = $specific_price["reduction_type"];
                $product_info[$prod_id]["price_sale"] = $price_sale;

                $sql_fld='SELECT '._DB_PREFIX_.'reviver_rental_template_fields_assigned.id_field, '._DB_PREFIX_.'reviver_rental_template_fields.* FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned`
        LEFT JOIN '._DB_PREFIX_.'reviver_rental_template_fields 
        ON '._DB_PREFIX_.'reviver_rental_template_fields_assigned.id_field = '._DB_PREFIX_.'reviver_rental_template_fields.id_field 
        WHERE '._DB_PREFIX_.'reviver_rental_template_fields_assigned.id_template = '.(int)$prod_id_template.'
        ORDER BY '._DB_PREFIX_.'reviver_rental_template_fields_assigned.order ASC';
                $query_result_fld = Db::getInstance()->ExecuteS($sql_fld);
                foreach ($query_result_fld as $row_fld) {
                    $id_field = $row_fld['id_field'];
                    $id_field_type = $row_fld['type'];

                    if (($id_field_type == "select") or ($id_field_type == "checkbox") or ($id_field_type == "radio")) {
                        $allselect = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_field_select` WHERE `id_field`="'.(int)$id_field.'" ORDER BY `order` ASC';
                        foreach (Db::getInstance()->executeS($allselect) as $singleselect) {
                            $product_flds_sel[$prod_id][$id_field][$number]["name"] = $singleselect['name'];
                            $product_flds_sel[$prod_id][$id_field][$number]["title"] = $singleselect['title'];
                            $product_flds_sel[$prod_id][$id_field][$number]["value"] = $singleselect['value'];
                            $number++;
                        }
                    }

                    $product_flds[$prod_id][$id_field]["id_field"] = $row_fld['id_field'];
                    $product_flds[$prod_id][$id_field]["type"] = $row_fld['type'];
                    $product_flds[$prod_id][$id_field]["name"] = $row_fld['name'];
                    $product_flds[$prod_id][$id_field]["title"] = $row_fld['title'];
                    $product_flds[$prod_id][$id_field]["value"] = $row_fld['value'];
                    $product_flds[$prod_id][$id_field]["placeholder"] = $row_fld['placeholder'];
                }

                $sql_prd='SELECT '._DB_PREFIX_.'reviver_rental_template_products_assigned.id_template_product, '._DB_PREFIX_.'reviver_rental_template_products.* FROM `'._DB_PREFIX_.'reviver_rental_template_products_assigned`
        LEFT JOIN '._DB_PREFIX_.'reviver_rental_template_products 
        ON '._DB_PREFIX_.'reviver_rental_template_products_assigned.id_template_product = '._DB_PREFIX_.'reviver_rental_template_products.id_template_product 
        WHERE '._DB_PREFIX_.'reviver_rental_template_products_assigned.id_template = '.(int)$prod_id_template.'
        ORDER BY '._DB_PREFIX_.'reviver_rental_template_products_assigned.order ASC';
                $query_result_prd = Db::getInstance()->ExecuteS($sql_prd);
                foreach ($query_result_prd as $row_prd) {
                    $id_template_product = $row_prd['id_template_product'];
                    $product_prods[$prod_id][$id_template_product]["id_template_product"] = $row_prd['id_template_product'];
                    $product_prods[$prod_id][$id_template_product]["name"] = $row_prd['name'];
                    $product_prods[$prod_id][$id_template_product]["title"] = $row_prd['title'];
                    $product_prods[$prod_id][$id_template_product]["description"] = $row_prd['description'];
                    $product_prods[$prod_id][$id_template_product]["image_path"] = $row_prd['image_path'];
                    $product_prods[$prod_id][$id_template_product]["price"] = $row_prd['price'];
                    $product_prods[$prod_id][$id_template_product]["price_discount"] = $row_prd['price_discount'];
                }

                $sql_coe='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_coefficients` WHERE id_template = '.(int)$prod_id_template.'';
                $query_result_coe = Db::getInstance()->ExecuteS($sql_coe);
                $n = 0;
                foreach ($query_result_coe as $row_coe) {
                    $product_coe[$prod_id][$n]["date_from"] = $row_coe['days_from'];
                    $product_coe[$prod_id][$n]["date_to"] = $row_coe['days_to'];
                    $product_coe[$prod_id][$n]["coef"] = $row_coe['coefficient'];
                    $n++;
                }
            }

            if ($this->context->cookie->reviver_rental_error == "1") {
                $this->context->cookie->reviver_rental_error = "0";
                $show_error = "yes";
            } else {
                $show_error = "no";
            }

            $this->context->smarty->assign(array(
                'show_error' => $show_error,
                'columnsset' => $columnsset,
                'currency_spacing' => $this->context->currency->blank,
                'currency_sign' => $this->context->currency->sign,
                'products_arr' => $products_arr,
                'product_info' => $product_info,
                'product_flds' => $product_flds,
                'product_flds_sel' => $product_flds_sel,
                'product_prods' => $product_prods,
                'product_coe' => $product_coe,
                'include_same_day' => $include_same_day,
                'discount_method' => $discount_method,
                'token' => Tools::getToken(false),
                'current_lang' => $lang_id
            ));

            return $this->display(__FILE__, 'views/templates/front/rental_page.tpl');
        }
    }

    public function hookdisplayAdminProductsExtra($params)
    {
        
        $id_product = "";
        $id_template = "";
        $alltemplates = "";
        
        $id_product = (int)Tools::getValue('id_product');
        $sql='SELECT `id_template` FROM `'._DB_PREFIX_.'reviver_rental_template_assigned` WHERE `id_product`='.(int)$id_product;
        $query_result = Db::getInstance()->ExecuteS($sql);
        foreach ($query_result as $row) {
            $id_template = $row['id_template'];
        }

        $sql2='SELECT `id_template`,`name`  FROM `'._DB_PREFIX_.'reviver_rental_templates`';
        $query_result2 = Db::getInstance()->ExecuteS($sql2);
        $alltemplates = "";
        $i = "";
        foreach ($query_result2 as $row2) {
            $alltemplates[$i]['id'] = $row2['id_template'];
            $alltemplates[$i]['name'] = $row2['name'];
            $i++;
        }
        
        $id_employee = (int)$this->context->cookie->id_employee;
        $token=Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).$id_employee);

        $ps_15_f_min = (int)(version_compare(_PS_VERSION_, '1.5.0.1'));
        $ps_15_f_max = (int)(version_compare(_PS_VERSION_, '1.5.1.0'));
        $ps_15_l_min = (int)(version_compare(_PS_VERSION_, '1.5.2.0'));
        $ps_15_l_max = (int)(version_compare(_PS_VERSION_, '1.5.6.3'));
        $ps_16_f_min = (int)(version_compare(_PS_VERSION_, '1.6.0.1'));
        $ps_16_f_max = (int)(version_compare(_PS_VERSION_, '1.6.0.4'));
        $ps_16_l_min = (int)(version_compare(_PS_VERSION_, '1.6.0.5'));
        $ps_16_l_max = (int)(version_compare(_PS_VERSION_, '1.6.1.6'));

        if (($ps_15_f_min >= 0) && ($ps_15_f_max <= 0)) {
            $template_var = "tab_15_early";
        } elseif (($ps_15_l_min >= 0) && ($ps_15_l_max <= 0)) {
            $template_var = "tab_15_late";
        } elseif (($ps_16_f_min >= 0) && ($ps_16_f_max <= 0)) {
            $template_var = "tab_16_early";
        } elseif (($ps_16_l_min >= 0) && ($ps_16_l_max <= 0)) {
            $template_var = "tab_16_late";
        } else {
            $template_var = "tab_16_late";
        }

        $this->context->smarty->assign(array(
            'id_product' => $id_product,
            'id_template' => $id_template,
            'alltemplates' => $alltemplates,
            'token' => $token,
            'current_lang' => (int)$this->context->language->id
        ));

        return $this->display(__FILE__, 'views/templates/admin/'.$template_var.'.tpl');
    }

    public function hookactionProductUpdate()
    {
        include("update_product.php");
    }

    public function hookdisplayHeader()
    {
        $id_cms_page = (int)Tools::getValue('id_cms');
        $controller_name = pSQL(trim(Tools::getValue('controller')));

        $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="cms_page"';
        foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
            $id_current_cms = $singlesett['value'];
        }

        if (($controller_name == "cms") && ($id_cms_page == $id_current_cms)) {
            Tools::addCSS(($this->_path).'views/css/style.css', 'all');
            $this->context->controller->addJqueryUI('ui.datepicker');
            Tools::addJS(($this->_path).'views/js/jQuery.Validate.min.js', 'all');
        }
    }

    public function getContent()
    {

        $cookie = new Cookie('psAdmin');
        $id_lang = (int)$cookie->id_lang;
        $token=Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee));
        $page = pSQL(trim(Tools::getValue('page')));
        $id = (int)Tools::getValue('id');

        //--------------------
        // ACTIONS
        //--------------------

        if (Tools::isSubmit('cancel')) {
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token");
        }

        if (Tools::isSubmit('editFieldAssignPrd')) {
            $order = (int)Tools::getValue('position_prd');
            $edit_id = (int)Tools::getValue('edit_id_prd');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_assigned` SET `order`="'.(int)$order.'" WHERE `id_template_assigned`='.(int)$edit_id.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token");
        }

        // Template actions
        if (Tools::isSubmit('createTemplate')) {
            $name = pSQL(trim(Tools::getValue('name')));
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_templates` (`id_template`, `name`) VALUES ("", "'.pSQL($name).'")');
            $lasttemplates = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_templates` ORDER BY `id_template` DESC LIMIT 1';
            foreach (Db::getInstance()->executeS($lasttemplates) as $lasttemp) {
                $lasttempid = $lasttemp['id_template'];
            }
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$lasttempid&conf=3");
        }
        if (Tools::isSubmit('editTemplate')) {
            $name = pSQL(trim(Tools::getValue('name')));
            $edit_id = (int)Tools::getValue('edit_id');
            $include_same_day = (int)Tools::getValue('include_same_day');
            $discount_method = (int)Tools::getValue('discount_method');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_templates` SET `name`="'.pSQL($name).'", `include_same_day`="'.(int)$include_same_day.'", `discount_method`="'.(int)$discount_method.'" WHERE `id_template`='.(int)$edit_id.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$edit_id&conf=4");
        }
        if (Tools::isSubmit('createNewTemplate')) {
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate");
        }
        $deleteTemplate = (int)Tools::getValue('deleteTemplate');
        if ($deleteTemplate > 0) {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_templates` WHERE `id_template`='.(int)$deleteTemplate.'');
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_assigned` WHERE `id_template`='.(int)$deleteTemplate.'');
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` WHERE `id_template`='.(int)$deleteTemplate.'');
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_products_assigned` WHERE `id_template`='.(int)$deleteTemplate.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&conf=1");
        }

        // Fields assign actions
        if (Tools::isSubmit('createFieldAssign')) {
            $field_id = (int)Tools::getValue('field_id_sel');
            $order = (int)Tools::getValue('position_sel');
            $id_template = (int)Tools::getValue('id_template');
            if ($field_id > 0) {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` (`id_template_field_assigned`, `id_field`, `id_template`, `order`) VALUES ("", "'.(int)$field_id.'", "'.(int)$id_template.'", "'.(int)$order.'")');
            }
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$id_template&conf=3");
        }
        if (Tools::isSubmit('editFieldAssign')) {
            $field_id = (int)Tools::getValue('field_id');
            $order = (int)Tools::getValue('position_sel');
            $edit_id = (int)Tools::getValue('edit_id');
            $template_id = (int)Tools::getValue('template_id');
            if ($field_id > 0) {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` SET `id_field`="'.(int)$field_id.'", `order`="'.(int)$order.'" WHERE `id_template_field_assigned`='.(int)$edit_id.'');
            }
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$template_id&conf=3");
        }
        $deleteFieldAssign = (int)Tools::getValue('deleteFieldAssign');
        if ($deleteFieldAssign > 0) {
            $backtemps = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` WHERE `id_template_field_assigned`='.(int)$deleteFieldAssign.'';
            foreach (Db::getInstance()->executeS($backtemps) as $backtemp) {
                $backid = $backtemp['id_template'];
            }
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` WHERE `id_template_field_assigned`='.(int)$deleteFieldAssign.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$backid&conf=1");
        }

        // Products / services assign actions
        if (Tools::isSubmit('createProductAssign')) {
            $prod_id_sel = (int)Tools::getValue('prod_id_sel');
            $order = (int)Tools::getValue('prod_position_sel');
            $id_template = (int)Tools::getValue('id_template');
            if ($prod_id_sel > 0) {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_template_products_assigned` (`id_template_product_assigned`, `id_template_product`, `id_template`, `order`) VALUES ("", "'.(int)$prod_id_sel.'", "'.(int)$id_template.'", "'.(int)$order.'")');
            }
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$id_template&conf=3");
        }
        if (Tools::isSubmit('editProductAssign')) {
            $prod_id_sel = (int)Tools::getValue('prod_id_sel');
            $order = (int)Tools::getValue('prod_position_sel');
            $edit_id = (int)Tools::getValue('edit_id');
            $template_id = (int)Tools::getValue('template_id');
            if ($prod_id_sel > 0) {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_products_assigned` SET `id_template_product`="'.(int)$prod_id_sel.'", `order`="'.(int)$order.'" WHERE `id_template_product_assigned`='.(int)$edit_id.'');
            }
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$template_id&conf=3");
        }
        $deleteProductAssign = (int)Tools::getValue('deleteProductAssign');
        if ($deleteProductAssign > 0) {
            $backtemps = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products_assigned` WHERE `id_template_product_assigned`='.(int)$deleteProductAssign.'';
            foreach (Db::getInstance()->executeS($backtemps) as $backtemp) {
                $backid = $backtemp['id_template'];
            }
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_products_assigned` WHERE `id_template_product_assigned`='.(int)$deleteProductAssign.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$backid&conf=1");
        }

        // Coefficients actions
        if (Tools::isSubmit('createCoe')) {
            $date_from_coe = (int)Tools::getValue('date_from_coe');
            $date_to_coe = (int)Tools::getValue('date_to_coe');
            $date_coe = pSQL(trim(Tools::getValue('date_coe')));
            $id_template = (int)Tools::getValue('id_template');
            $date_coe = (float)str_replace(",", ".", $date_coe);
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_template_coefficients` (`id_template_coefficient`, `id_template`, `days_from`, `days_to`, `coefficient`) VALUES ("", "'.(int)$id_template.'", "'.pSQL($date_from_coe).'", "'.pSQL($date_to_coe).'", "'.(float)$date_coe.'")');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$id_template&conf=3");
        }
        if (Tools::isSubmit('editCoe')) {
            $date_from_coe = (int)Tools::getValue('date_from_coe');
            $date_to_coe = (int)Tools::getValue('date_to_coe');
            $date_coe = pSQL(trim(Tools::getValue('date_coe')));
            $edit_id = (int)Tools::getValue('edit_id');
            $template_id = (int)Tools::getValue('template_id');
            $date_coe = (float)str_replace(",", ".", $date_coe);
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_coefficients` SET `days_from`="'.pSQL($date_from_coe).'", `days_to`="'.pSQL($date_to_coe).'", `coefficient`="'.(float)$date_coe.'" WHERE `id_template_coefficient`='.(int)$edit_id.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$template_id&conf=3");
        }
        $deleteCoe = (int)Tools::getValue('deleteCoe');
        if ($deleteCoe > 0) {
            $backtemps = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_coefficients` WHERE `id_template_coefficient`='.(int)$deleteCoe.'';
            foreach (Db::getInstance()->executeS($backtemps) as $backtemp) {
                $backid = $backtemp['id_template'];
            }
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_coefficients` WHERE `id_template_coefficient`='.(int)$deleteCoe.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createTemplate&id=$backid&conf=1");
        }

        // Fields actions
        if (Tools::isSubmit('createField')) {
            $type = pSQL(trim(Tools::getValue('type')));
            $name = pSQL(trim(Tools::getValue('name')));
            $title = pSQL(trim(Tools::getValue('title')));
            $placeholder = pSQL(trim(Tools::getValue('placeholder')));
            $value = pSQL(trim(Tools::getValue('value')));
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_template_fields` (`id_field`, `type`, `name`, `title`, `placeholder`, `value`) VALUES ("", "'.pSQL($type).'", "'.pSQL($name).'", "'.pSQL($title).'", "'.pSQL($value).'", "'.pSQL($placeholder).'")');
            $lastfields = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields` ORDER BY `id_field` DESC LIMIT 1';
            foreach (Db::getInstance()->executeS($lastfields) as $lastfield) {
                $lastfieldid = $lastfield['id_field'];
            }
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createField&id=$lastfieldid&conf=3");
        }
        if (Tools::isSubmit('editField')) {
            $type = pSQL(trim(Tools::getValue('type')));
            $name = pSQL(trim(Tools::getValue('name')));
            $title = pSQL(trim(Tools::getValue('title')));
            $placeholder = pSQL(trim(Tools::getValue('placeholder')));
            $value = pSQL(trim(Tools::getValue('value')));
            $edit_id = (int)Tools::getValue('edit_id');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_fields` SET `type`="'.pSQL($type).'", `name`="'.pSQL($name).'", `title`="'.pSQL($title).'", `placeholder`="'.pSQL($placeholder).'", `value`="'.pSQL($value).'" WHERE `id_field`='.(int)$edit_id.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createField&id=$edit_id&conf=4");
        }
        if (Tools::isSubmit('createNewField')) {
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createField");
        }
        $deleteField = (int)Tools::getValue('deleteField');
        if ($deleteField > 0) {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_fields` WHERE `id_field`='.(int)$deleteField.'');
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'rental_template_field_select` WHERE `id_field`='.(int)$deleteField.'');
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` WHERE `id_field`='.(int)$deleteField.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&conf=1");
        }

        // Fields selections actions
        if (Tools::isSubmit('createSelection')) {
            $id_field = (int)Tools::getValue('id_field_sel');
            $name = pSQL(trim(Tools::getValue('name_sel')));
            $title = pSQL(trim(Tools::getValue('title_sel')));
            $value = pSQL(trim(Tools::getValue('value_sel')));
            $position = (int)Tools::getValue('position_sel');
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_template_field_select` (`id_field_select`, `id_field`, `name`, `title`, `value`, `order`) VALUES ("", "'.(int)$id_field.'", "'.pSQL($name).'", "'.pSQL($title).'", "'.pSQL($value).'", "'.(int)$position.'")');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createField&id=$id_field&conf=3");
        }
        if (Tools::isSubmit('editSelection')) {
            $id_field = (int)Tools::getValue('id_field_sel');
            $name = pSQL(trim(Tools::getValue('name_sel')));
            $title = pSQL(trim(Tools::getValue('title_sel')));
            $value = pSQL(trim(Tools::getValue('value_sel')));
            $position = (int)Tools::getValue('position_sel');
            $edit_id = (int)Tools::getValue('edit_id');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_field_select` SET `name`="'.pSQL($name).'", `title`="'.pSQL($title).'", `order`="'.(int)$position.'", `value`="'.pSQL($value).'" WHERE `id_field_select`='.(int)$edit_id.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createField&id=$id_field&conf=4");
        }
        $deleteSelection = (int)Tools::getValue('deleteSelection');
        if ($deleteSelection > 0) {
            $allsels = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_field_select` WHERE `id_field_select` = "'.(int)$deleteSelection.'"';
            foreach (Db::getInstance()->executeS($allsels) as $singlesel) {
                $id_field = $singlesel['id_field'];
            }
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_field_select` WHERE `id_field_select`='.(int)$deleteSelection.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&conf=1&page=createField&id=$id_field");
        }

        // Additional products / services actions
        if (Tools::isSubmit('createProduct')) {
            $name = pSQL(trim(Tools::getValue('name')));
            $title = pSQL(trim(Tools::getValue('title')));
            $description = pSQL(trim(Tools::getValue('description')));
            $price = pSQL(trim(Tools::getValue('price')));
            $price_discount = pSQL(trim(Tools::getValue('price_discount')));
            $price = (float)str_replace(",", ".", $price);
            $price_discount = (float)str_replace(",", ".", $price_discount);
            if ($price_discount == "") {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_template_products` (`id_template_product`, `name`, `title`, `description`, `price`, `price_discount`, `image_path`) VALUES ("", "'.pSQL($name).'", "'.pSQL($title).'", "'.pSQL($description).'","'.(float)$price.'", NULL, "")');
            } else {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'reviver_rental_template_products` (`id_template_product`, `name`, `title`, `description`, `price`, `price_discount`, `image_path`) VALUES ("", "'.pSQL($name).'", "'.pSQL($title).'", "'.pSQL($description).'", "'.(float)$price.'", "'.(float)$price_discount.'", "")');
            }

            $lastprod = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` ORDER BY `id_template_product` DESC LIMIT 1';
            foreach (Db::getInstance()->executeS($lastprod) as $lastproduct) {
                $lastprodid = $lastproduct['id_template_product'];
            }
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createProduct&id=$lastprodid&conf=3");
        }
        if (Tools::isSubmit('editProduct')) {
            $name = pSQL(trim(Tools::getValue('name')));
            $title = pSQL(trim(Tools::getValue('title')));
            $description = pSQL(trim(Tools::getValue('description')));
            $price = pSQL(trim(Tools::getValue('price')));
            $price_discount = pSQL(trim(Tools::getValue('price_discount')));
            $image_path = pSQL(Tools::getValue('image_path'));
            $edit_id = (int)Tools::getValue('edit_id');
            $price = (float)str_replace(",", ".", $price);
            $price_discount = (float)str_replace(",", ".", $price_discount);
            if ($price_discount == "") {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_products` SET `name`="'.pSQL($name).'", `title`="'.pSQL($title).'", `description`="'.pSQL($description).'", `price`="'.(float)$price.'", `price_discount`= NULL WHERE `id_template_product`='.(int)$edit_id.'');
            } else {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_products` SET `name`="'.pSQL($name).'", `title`="'.pSQL($title).'", `description`="'.pSQL($description).'", `price`="'.(float)$price.'", `price_discount`="'.(float)$price_discount.'" WHERE `id_template_product`='.(int)$edit_id.'');
            }

            $uploadDir = dirname(__FILE__) . '/products/';
            if ($_FILES['file']['name'] != "") {
                $ext = Tools::substr(strrchr($_FILES['file']['name'], "."), 1);

                // generate a random new file name to avoid name conflict
                $fPath = md5(rand() * time()) . ".$ext";
                move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fPath);
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_products` SET `image_path`="'.pSQL($fPath).'" WHERE `id_template_product`='.(int)$edit_id.'');
            }

            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createProduct&id=$edit_id&conf=4");
        }
        if (Tools::isSubmit('createNewProduct')) {
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&page=createProduct");
        }
        $deleteProductImage = (int)Tools::getValue('deleteProductImage');
        if ($deleteProductImage > 0) {
            $allimages = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` WHERE `id_template_product` = "'.(int)$deleteProductImage.'"';
            foreach (Db::getInstance()->executeS($allimages) as $singleimage) {
                $image_path = $singleimage['image_path'];
            }
            unlink(dirname(__FILE__) .'/products/'.$image_path);
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_template_products` SET `image_path`="" WHERE `id_template_product`='.(int)$deleteProductImage.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&conf=1&page=createProduct&id=$deleteProductImage");
        }
        $deleteProduct = (int)Tools::getValue('deleteProduct');
        if ($deleteProduct > 0) {
            $allimages = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` WHERE `id_template_product` = "'.(int)$deleteProduct.'"';
            foreach (Db::getInstance()->executeS($allimages) as $singleimage) {
                $image_path = $singleimage['image_path'];
            }
            unlink(dirname(__FILE__) .'/products/'.$image_path);
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_products` WHERE `id_template_product`='.(int)$deleteProduct.'');
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'rental_template_products_assigned` WHERE `id_template_product`='.(int)$deleteProduct.'');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&conf=1");
        }

        // Settings actions
        if (Tools::isSubmit('updateSettingsButton')) {
            $id_cms = (int)Tools::getValue('id_cms');
            $email_rec = pSQL(trim(Tools::getValue('email_rec')));
            $orderby = (int)Tools::getValue('orderby');
            $columns = (int)Tools::getValue('columns');
            $taxesset = (int)Tools::getValue('taxesset');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_options` SET `value`="'.pSQL($id_cms).'" WHERE `name`="cms_page"');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_options` SET `value`="'.pSQL($email_rec).'" WHERE `name`="email"');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_options` SET `value`="'.pSQL($orderby).'" WHERE `name`="orderby"');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_options` SET `value`="'.pSQL($columns).'" WHERE `name`="columns"');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'reviver_rental_options` SET `value`="'.pSQL($taxesset).'" WHERE `name`="taxes"');
            Tools::redirectAdmin("index.php?controller=AdminModules&configure=reviverrental&token=$token&conf=4");
        }

      //--------------------
        // PAGES
        //--------------------

        // Template pages

        if ($page == "createTemplate") {
        
            $name_tmp = "";
            $include_same_day = "";
            $discount_method = "";
            $editFld = "";
            $id_field_find = "";
            $fields = "";
            $id_field_check = "";
            $position_fld = "";
            $maxpos_fld = "";
            $found_tmp = "";
            $tmp_fields = "";
            $tmp_fields2 = "";
            $position_tmp = "";
            $maxpos_tmp = "";
            $id_field_check2 = "";
            $found_tmp2 = "";
            $tmp_prods = "";
            $editCoe = "";
            $found = "";
            $days_from = "";
            $days_to = "";
            $coefficient = "";
            $editPrd = "";
            $tmp_prods_list = "";
            $maxpos = "";
            $maxpos2 = "";
        
            if ($id > 0) {
                $alltemplates = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_templates` WHERE `id_template` = "'.(int)$id.'"';
                foreach (Db::getInstance()->executeS($alltemplates) as $singletemplate) {
                    $name_tmp = $singletemplate['name'];
                    $include_same_day = $singletemplate['include_same_day'];
                    $discount_method = $singletemplate['discount_method'];
                }

                $editFld = (int)Tools::getValue('editFld');
                if ($editFld > 0) {
                    $allfields0 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` WHERE `id_template_field_assigned` = "'.(int)$editFld.'"';
                    foreach (Db::getInstance()->executeS($allfields0) as $singlefld) {
                        $id_field_find = $singlefld['id_field'];
                        $position_fld = $singlefld['order'];
                    }

                    $allfields2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields` ORDER BY `name`';
                    $fields = '';
                    
                    foreach (Db::getInstance()->executeS($allfields2) as $singlefield0) {
                            $idnumber = 0;
                            $idnumber = $singlefield0['id_field'];
                            $fields[$idnumber]['id_field'] = $idnumber;
                            $fields[$idnumber]['name'] = $singlefield0['name'];
                    }
                } else {
                    $allfields = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` WHERE `id_template` = "'.(int)$id.'" ORDER BY `order` DESC LIMIT 1';
                    foreach (Db::getInstance()->executeS($allfields) as $singlefield) {
                        $maxpos = $singlefield['order'];
                    }
                    $maxpos_fld = $maxpos + 1;

                    $allfields2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields` ORDER BY `name`';
                    $fields = '';
                    
                    foreach (Db::getInstance()->executeS($allfields2) as $singlefield0) {
                            $idnumber = 0;
                            $idnumber = $singlefield0['id_field'];
                            $fields[$idnumber]['id_field'] = $idnumber;
                            $fields[$idnumber]['name'] = $singlefield0['name'];
                    }
                }

                $allfieldssel = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` WHERE `id_template`="'.(int)$id.'" ORDER BY `order` ASC';
                $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'reviver_rental_template_fields_assigned WHERE `id_template`="'.(int)$id.'"';
                $found_tmp = Db::getInstance()->getValue($foundsql);
                $tmp_fields = '';
                
                if ($found_tmp > 0) {
                    foreach (Db::getInstance()->executeS($allfieldssel) as $singlefieldsel) {
                        $allfieldssel2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields` WHERE `id_field`="'.(int)$singlefieldsel['id_field'].'"';
                        foreach (Db::getInstance()->executeS($allfieldssel2) as $singlefieldsel2) {
                            $fieldname = $singlefieldsel2['name'];
                        }
                            $idnumber = 0;
                            $idnumber = $singlefieldsel['id_template_field_assigned'];
                            $tmp_fields[$idnumber]['id_template_field_assigned'] = $idnumber;
                            $tmp_fields[$idnumber]['fieldname'] = $fieldname;
                            $tmp_fields[$idnumber]['order'] = $singlefieldsel['order'];
                    }
                }

                $editPrd = (int)Tools::getValue('editPrd');
                if ($editPrd > 0) {
                    $allprods0 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products_assigned` WHERE `id_template_product_assigned` = "'.(int)$editPrd.'"';
                    foreach (Db::getInstance()->executeS($allprods0) as $singleprod) {
                        $id_field_check2 = $singleprod['id_template_product'];
                        $position_tmp = $singleprod['order'];
                    }

                    $allprods2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` ORDER BY `name`';
                    $tmp_fields2 = '';
                    
                    foreach (Db::getInstance()->executeS($allprods2) as $singleprod0) {
                        $id_field_check = $singleprod0['id_template_product'];
                        $name = $singleprod0['name'];
                            $idnumber = 0;
                            $idnumber = $singleprod0['id_template_product'];
                            $tmp_fields2[$idnumber]['id_template_product'] = $idnumber;
                            $tmp_fields2[$idnumber]['name'] = $name;
                    }
                } else {
                    $allprods = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products_assigned` WHERE `id_template` = "'.(int)$id.'" ORDER BY `order` DESC LIMIT 1';
                    foreach (Db::getInstance()->executeS($allprods) as $singleprod) {
                        $maxpos2 = $singleprod['order'];
                    }
                    $maxpos_tmp = $maxpos2 + 1;

                    $allprods2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` ORDER BY `name`';
                    $tmp_fields2 = '';

                    foreach (Db::getInstance()->executeS($allprods2) as $singleprod2) {
                        $name = $singleprod2['name'];
                            $idnumber = 0;
                            $idnumber = $singleprod2['id_template_product'];
                            $tmp_fields2[$idnumber]['id_template_product'] = $idnumber;
                            $tmp_fields2[$idnumber]['name'] = $name;
                    }
                }

                $allprodsel = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products_assigned` WHERE `id_template`="'.(int)$id.'" ORDER BY `order` ASC';
                $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'reviver_rental_template_products_assigned WHERE `id_template`="'.(int)$id.'"';
                $found_tmp2 = Db::getInstance()->getValue($foundsql);
                $tmp_prods = '';

                if ($found_tmp2 > 0) {
                    foreach (Db::getInstance()->executeS($allprodsel) as $singleprodsel) {
                        $allprodsel2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` WHERE `id_template_product`="'.(int)$singleprodsel['id_template_product'].'"';
                        foreach (Db::getInstance()->executeS($allprodsel2) as $singleprodsel2) {
                            $fieldname2 = $singleprodsel2['name'];
                        }
                            $idnumber = 0;
                            $idnumber = $singleprodsel['id_template_product_assigned'];
                            $tmp_prods[$idnumber]['id_template_product_assigned'] = $idnumber;
                            $tmp_prods[$idnumber]['fieldname'] = $fieldname2;
                            $tmp_prods[$idnumber]['order'] = $singleprodsel['order'];
                    }
                }

                $editCoe = (int)Tools::getValue('editCoeId');
                if ($editCoe > 0) {
                    $allprods0 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_coefficients` WHERE `id_template_coefficient` = "'.(int)$editCoe.'"';
                    foreach (Db::getInstance()->executeS($allprods0) as $singleprod) {
                        $days_from = $singleprod['days_from'];
                        $days_to = $singleprod['days_to'];
                        $coefficient = $singleprod['coefficient'];
                    }
                }

                $allprodsel = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_coefficients` WHERE `id_template`="'.(int)$id.'" ORDER BY `days_from` ASC';
                $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'reviver_rental_template_coefficients WHERE `id_template`="'.(int)$id.'"';
                $found = Db::getInstance()->getValue($foundsql);
                $tmp_prods_list = '';

                if ($found > 0) {
                    foreach (Db::getInstance()->executeS($allprodsel) as $singleprodsel0) {
                            $idnumber = 0;
                            $idnumber = $singleprodsel0['id_template_coefficient'];
                            $tmp_prods_list[$idnumber]['id_template_coefficient'] = $idnumber;
                            $tmp_prods_list[$idnumber]['days_from'] = $singleprodsel0['days_from'];
                            $tmp_prods_list[$idnumber]['days_to'] = $singleprodsel0['days_to'];
                            $tmp_prods_list[$idnumber]['coefficient'] = $singleprodsel0['coefficient'];
                    }
                }
            }
            
            $this->context->smarty->assign(array(
                'token' => $token,
                'id' => $id,
                'name_tmp' => $name_tmp,
                'include_same_day' => $include_same_day,
                'discount_method' => $discount_method,
                'editFld' => $editFld,
                'id_field_find' => $id_field_find,
                'fields' => $fields,
                'id_field_check' => $id_field_check,
                'position_fld' => $position_fld,
                'maxpos_fld' => $maxpos_fld,
                'found_tmp' => $found_tmp,
                'tmp_fields' => $tmp_fields,
                'tmp_fields2' => $tmp_fields2,
                'position_tmp' => $position_tmp,
                'maxpos_tmp' => $maxpos_tmp,
                'id_field_check2' => $id_field_check2,
                'found_tmp2' => $found_tmp2,
                'tmp_prods' => $tmp_prods,
                'editCoe' => $editCoe,
                'found' => $found,
                'days_from' => $days_from,
                'days_to' => $days_to,
                'coefficient' => $coefficient,
                'editPrd' => $editPrd,
                'tmp_prods_list' => $tmp_prods_list,
            ));

            return $this->display(__FILE__, 'views/templates/admin/templates.tpl');
            
        }

        // Fields pages
        if ($page == "createField") {
            
            $name = "";
            $title = "";
            $type = "";
            $value = "";
            $placeholder = "";
            $editSel = "";
            $name_fld = "";
            $title_fld = "";
            $value_fld = "";
            $position_fld = "";
            $maxpos = "";
            $found = "";
            $fields = "";

            if ($id > 0) {
                $allfields = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields` WHERE `id_field` = "'.(int)$id.'"';
                foreach (Db::getInstance()->executeS($allfields) as $singlefield) {
                    $name = $singlefield['name'];
                    $title = $singlefield['title'];
                    $type = $singlefield['type'];
                    $value = $singlefield['value'];
                    $placeholder = $singlefield['placeholder'];
                }

                if ($type == "select" || $type == "checkbox" || $type == "radio") {
                    $editSel = (int)Tools::getValue('editSel');
                    if ($editSel > 0) {
                        $allselections = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_field_select` WHERE `id_field_select` = "'.(int)$editSel.'"';
                        foreach (Db::getInstance()->executeS($allselections) as $singlesel) {
                            $name_fld = $singlesel['name'];
                            $title_fld = $singlesel['title'];
                            $value_fld = $singlesel['value'];
                            $position_fld = $singlesel['order'];
                        }
                        
                    } else {
                        $allselections = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_field_select` WHERE `id_field` = "'.(int)$id.'" ORDER BY `order` DESC LIMIT 1';
                        foreach (Db::getInstance()->executeS($allselections) as $singlesel) {
                            $maxpos = $singlesel['order'];
                        }
                        $maxpos = $maxpos + 1;
                    }

                    $allselects = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_field_select` WHERE `id_field`="'.(int)$id.'" ORDER BY `order` ASC';
                    $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'reviver_rental_template_field_select WHERE `id_field`="'.(int)$id.'"';
                    $found = Db::getInstance()->getValue($foundsql);
                    $fields = '';
                    
                    if ($found > 0) {
                        foreach (Db::getInstance()->executeS($allselects) as $singleselect) {
                            
                                $idnumber = 0;
                                $idnumber = $singleselect['id_field_select'];
                                $fields[$idnumber]['id_field_select'] = $idnumber;
                                $fields[$idnumber]['name'] = $singleselect['name'];
                                $fields[$idnumber]['title'] = $singleselect['title'];
                                $fields[$idnumber]['value'] = $singleselect['value'];
                                $fields[$idnumber]['order'] = $singleselect['order'];
                        }
                    }
                }
            }
            
            $this->context->smarty->assign(array(
                'token' => $token,
                'id' => $id,
                'name' => $name,
                'title' => $title,
                'type' => $type,
                'value' => $value,
                'placeholder' => $placeholder,
                'editSel' => $editSel,
                'name_fld' => $name_fld,
                'title_fld' => $title_fld,
                'value_fld' => $value_fld,
                'position_fld' => $position_fld,
                'maxpos' => $maxpos,
                'found' => $found,
                'fields' => $fields,
            ));

            return $this->display(__FILE__, 'views/templates/admin/fields.tpl');
        
        }

        // Additional products / services pages
        if ($page == "createProduct") {
            
            $name = "";
            $title = "";
            $description = "";
            $price = "";
            $price_discount = "";
            $image_path = "";

            if ($id > 0) {
                $allprods = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` WHERE `id_template_product` = "'.(int)$id.'"';
                foreach (Db::getInstance()->executeS($allprods) as $singleprod) {
                    $name = $singleprod['name'];
                    $title = $singleprod['title'];
                    $description = $singleprod['description'];
                    $price = $singleprod['price'];
                    $price_discount = $singleprod['price_discount'];
                    $image_path = $singleprod['image_path'];
                }
            }
            
            $this->context->smarty->assign(array(
                'token' => $token,
                'id' => $id,
                'name' => $name,
                'title' => $title,
                'description' => $description,
                'price_discount' => $price_discount,
                'price' => $price,
                'image_path' => $image_path,
            ));

            return $this->display(__FILE__, 'views/templates/admin/additionalProducts.tpl');
        
        }
        
        //--------------------
        // FORMS
        //--------------------

        // Templates form

            $editAssignedProd = "";
            $editAssignedProd_position = "";
            $found_assigned = "";
            $assigned_prods = "";
            $found_templates = "";
            $templates = "";
            $found_fields = "";
            $fields = "";
            $found_prods = "";
            $add_products = "";
            $cms_pages = "";
            $id_current_cms = "";
            $email_rec = "";
            $orderbysett = "";
            $columnsset = "";
            $taxesset = "";
        
        if ($page == "") {
            $tokenProducts = Tools::getAdminToken('AdminProducts'.(int)(Tab::getIdFromClassName('AdminProducts')).(int)($cookie->id_employee));
            $lang_id = $cookie->id_lang;

            // Currently assigned products table
            $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="orderby"';
            foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                $orderbysett = $singlesett['value'];
            }

            $editAssignedProd = (int)Tools::getValue('editAssignedProd');

            if ($editAssignedProd > 0) {
                $allfields0 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_assigned` WHERE `id_template_assigned` = "'.(int)$editAssignedProd.'"';
                foreach (Db::getInstance()->executeS($allfields0) as $singlefld) {
                    $editAssignedProd_position = $singlefld['order'];
                }
            }

            $alltemplates = 'SELECT '._DB_PREFIX_.'reviver_rental_template_assigned.id_template_assigned, '._DB_PREFIX_.'reviver_rental_template_assigned.id_product, '._DB_PREFIX_.'reviver_rental_template_assigned.id_template, '._DB_PREFIX_.'reviver_rental_template_assigned.order, '._DB_PREFIX_.'product_lang.name FROM `'._DB_PREFIX_.'reviver_rental_template_assigned`
                        LEFT JOIN '._DB_PREFIX_.'product_lang 
                        ON '._DB_PREFIX_.'reviver_rental_template_assigned.id_product = '._DB_PREFIX_.'product_lang.id_product 
                        WHERE '._DB_PREFIX_.'product_lang.id_lang = '.(int)$lang_id.'';

            if ($orderbysett == 1) {
                $alltemplates .= ' ORDER BY '._DB_PREFIX_.'product_lang.name ASC';
            }
            if ($orderbysett == 2) {
                $alltemplates .= ' ORDER BY '._DB_PREFIX_.'product_lang.name DESC';
            }
            if ($orderbysett == 3) {
                $alltemplates .= ' ORDER BY '._DB_PREFIX_.'reviver_rental_template_assigned.id_template_assigned ASC';
            }
            if ($orderbysett == 4) {
                $alltemplates .= ' ORDER BY '._DB_PREFIX_.'reviver_rental_template_assigned.id_template_assigned DESC';
            }
            if ($orderbysett == 5) {
                $alltemplates .= ' ORDER BY '._DB_PREFIX_.'reviver_rental_template_assigned.order ASC';
            }
            if ($orderbysett == 6) {
                $alltemplates .= ' ORDER BY '._DB_PREFIX_.'reviver_rental_template_assigned.order DESC';
            }

            $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'reviver_rental_template_assigned';
            $found_assigned = Db::getInstance()->getValue($foundsql);
            $assigned_prods = '';

            if ($found_assigned > 0) {
                foreach (Db::getInstance()->executeS($alltemplates) as $singletemplate) {
                    $alltemplates2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_templates` WHERE `id_template`="'.(int)$singletemplate['id_template'].'"';
                    foreach (Db::getInstance()->executeS($alltemplates2) as $singletemplate2) {
                        $template_name = $singletemplate2['name'];
                    }
                            $idnumber = 0;
                            $idnumber = $singletemplate['id_template_assigned'];
                            $assigned_prods[$idnumber]['id_template_assigned'] = $singletemplate['id_template_assigned'];
                            $assigned_prods[$idnumber]['name'] = $singletemplate['name'];
                            $assigned_prods[$idnumber]['template_name'] = $template_name;
                            $assigned_prods[$idnumber]['order'] = $singletemplate['order'];
                            $assigned_prods[$idnumber]['id_product'] = $singletemplate['id_product'];
                }
            }
            
            // Rental templates table
            $alltemplates = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_templates` ORDER BY `name` ASC';
            $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'reviver_rental_templates';
            $found_templates = Db::getInstance()->getValue($foundsql);
            $templates = '';
            
            if ($found_templates > 0) {
                foreach (Db::getInstance()->executeS($alltemplates) as $singletemplate) {
                    $foundsql2 = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'reviver_rental_template_fields_assigned` WHERE `id_template`="'.(int)$singletemplate['id_template'].'"';
                    $found2 = Db::getInstance()->getValue($foundsql2);
                    $foundsql3 = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'reviver_rental_template_products_assigned` WHERE `id_template`="'.(int)$singletemplate['id_template'].'"';
                    $found3 = Db::getInstance()->getValue($foundsql3);
                            
                            $idnumber = 0;
                            $idnumber = $singletemplate['id_template'];
                            $templates[$idnumber]['id_template'] = $singletemplate['id_template'];
                            $templates[$idnumber]['name'] = $singletemplate['name'];
                            $templates[$idnumber]['found2'] = $found2;
                            $templates[$idnumber]['found3'] = $found3;
                }
            }
            
            // Fields table
            $allfields = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_fields` ORDER BY `id_field` ASC';
            $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'reviver_rental_template_fields';
            $found_fields = Db::getInstance()->getValue($foundsql);
            $fields = '';
            
            if ($found_fields > 0) {
                foreach (Db::getInstance()->executeS($allfields) as $singlefield) {
                    
                            $idnumber = 0;
                            $idnumber = $singlefield['id_field'];
                            $fields[$idnumber]['id_field'] = $singlefield['id_field'];
                            $fields[$idnumber]['name'] = $singlefield['name'];
                            $fields[$idnumber]['title'] = $singlefield['title'];
                            $fields[$idnumber]['type'] = $singlefield['type'];
                }
            }
            
            // Additional products table
            $allprods = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_template_products` ORDER BY `name` ASC';
            $foundsql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'reviver_rental_template_products';
            $found_prods = Db::getInstance()->getValue($foundsql);
            $add_products = '';
            
            if ($found_prods > 0) {
                foreach (Db::getInstance()->executeS($allprods) as $singleprod) {
                    
                            $idnumber = 0;
                            $idnumber = $singleprod['id_template_product'];
                            $add_products[$idnumber]['id_template_product'] = $singleprod['id_template_product'];
                            $add_products[$idnumber]['name'] = $singleprod['name'];
                            $add_products[$idnumber]['title'] = $singleprod['title'];
                            $add_products[$idnumber]['price'] = $singleprod['price'];
                }
            }
            
            // Settings form
            $id_lang = $cookie->id_lang;

            $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="cms_page"';
            foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                $id_current_cms = $singlesett['value'];
            }
            $allsett2 = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="email"';
            foreach (Db::getInstance()->executeS($allsett2) as $singlesett2) {
                $email_rec = $singlesett2['value'];
            }
            $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="orderby"';
            foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                $orderbysett = $singlesett['value'];
            }
            $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="columns"';
            foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                $columnsset = $singlesett['value'];
            }
            $allsett = 'SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name`="taxes"';
            foreach (Db::getInstance()->executeS($allsett) as $singlesett) {
                $taxesset = $singlesett['value'];
            }

            $allcms = 'SELECT * FROM `'._DB_PREFIX_.'cms` ORDER BY `id_cms`';
            $cms_pages = '';
            
            foreach (Db::getInstance()->executeS($allcms) as $singlecms) {
                $id_cms_check = $singlecms['id_cms'];
                $allcms2 = 'SELECT * FROM `'._DB_PREFIX_.'cms_lang` WHERE `id_cms`='.(int)$id_cms_check.' AND `id_lang` = '.(int)$id_lang.'';
                foreach (Db::getInstance()->executeS($allcms2) as $singlecms2) {
                    $name = $singlecms2['meta_title'];
                }

                    $idnumber = 0;
                    $idnumber = $id_cms_check;
                    $cms_pages[$idnumber]['id_cms_check'] = $id_cms_check;
                    $cms_pages[$idnumber]['name'] = $name;
            }
        }
        
        $this->context->smarty->assign(array(
                'tokenProducts' => $tokenProducts,
                'token' => $token,
                'lang_id' => $lang_id,
                'editAssignedProd' => $editAssignedProd,
                'editAssignedProd_position' => $editAssignedProd_position,
                'found_assigned' => $found_assigned,
                'assigned_prods' => $assigned_prods,
                'found_templates' => $found_templates,
                'templates' => $templates,
                'found_fields' => $found_fields,
                'fields' => $fields,
                'found_prods' => $found_prods,
                'add_products' => $add_products,
                'cms_pages' => $cms_pages,
                'id_current_cms' => $id_current_cms,
                'email_rec' => $email_rec,
                'orderbysett' => $orderbysett,
                'columnsset' => $columnsset,
                'taxesset' => $taxesset,
                
            ));

        return $this->display(__FILE__, 'views/templates/admin/main.tpl');
    }
}
