<?php
/*------------------------------------------------------------------------
# helper.php - Ossolution Property
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// No direct access.
defined('_JEXEC') or die;

class OSPHelper
{
    /**
     * This function is used to load Config and return the Configuration Variable
     *
     */
    public static function loadConfig()
    {
        $db = Jfactory::getDbo();
        $db->setQuery("Select * from #__osrs_configuration");
        $configs = $db->loadObjectList();
        $configClass = array();
        foreach ($configs as $config) {
            $configClass[$config->fieldname] = $config->fieldvalue;
        }

        $curr = $configClass['general_currency_default'];
        $arrCode = array();
        $arrSymbol = array();

        $db->setQuery("Select * from #__osrs_currencies where id = '$curr'");
        $currency = $db->loadObject();
        $symbol = $currency->currency_symbol;
        $index = -1;
        if ($symbol == "") {
            $symbol = '$';
        }
        $configClass['curr_symbol'] = $symbol;
        return $configClass;
    }

    /**
     * This function is used to check to see whether we need to update the database to support multilingual or not
     *
     * @return boolean
     */
    public static function isSyncronized()
    {
        $db = JFactory::getDbo();
        //#__osrs_tags
        $fields = array_keys($db->getTableColumns('#__osrs_tags'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('keyword_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_emails
        $fields = array_keys($db->getTableColumns('#__osrs_emails'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('email_title_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_categories
        $fields = array_keys($db->getTableColumns('#__osrs_categories'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('category_name_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_amenities
        $fields = array_keys($db->getTableColumns('#__osrs_amenities'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('amenities_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_fieldgroups
        $fields = array_keys($db->getTableColumns('#__osrs_fieldgroups'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('group_name_' . $prefix, $fields)) {
                    return false;
                }
            }
        }


        //osrs_osrs_extra_fields
        $fields = array_keys($db->getTableColumns('#__osrs_extra_fields'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('field_label_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_extra_field_options
        $fields = array_keys($db->getTableColumns('#__osrs_extra_field_options'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('field_option_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_property_field_value
        $fields = array_keys($db->getTableColumns('#__osrs_property_field_value'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('value_' . $prefix, $fields)) {
                    return false;
                }
            }
        }


        //osrs_types
        $fields = array_keys($db->getTableColumns('#__osrs_types'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('type_name_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_properties
        $fields = array_keys($db->getTableColumns('#__osrs_properties'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('pro_name_' . $prefix, $fields)) {
                    return false;
                }
                if (!in_array('metadesc_' . $prefix, $fields)) {
                    return false;
                }
                if (!in_array('metakey_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_agents
        $fields = array_keys($db->getTableColumns('#__osrs_agents'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('bio_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_companies
        $fields = array_keys($db->getTableColumns('#__osrs_companies'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('company_description_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        //osrs_states
        $fields = array_keys($db->getTableColumns('#__osrs_states'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('state_name_' . $prefix, $fields)) {
                    return false;
                }
            }
        }


        //osrs_cities
        $fields = array_keys($db->getTableColumns('#__osrs_cities'));
        $extraLanguages = self::getLanguages();
        if (count($extraLanguages)) {
            foreach ($extraLanguages as $extraLanguage) {
                $prefix = $extraLanguage->sef;
                if (!in_array('city_' . $prefix, $fields)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get field suffix used in sql query
     *
     * @return string
     */
    public static function getFieldSuffix($activeLanguage = null)
    {
        $prefix = '';
        if (JLanguageMultilang::isEnabled()) {
            if (!$activeLanguage)
                $activeLanguage = JFactory::getLanguage()->getTag();
            if ($activeLanguage != self::getDefaultLanguage()) {
                $prefix = '_' . substr($activeLanguage, 0, 2);
            }
        }
        return $prefix;
    }


    /**
     *
     * Function to get all available languages except the default language
     * @return languages object list
     */
    public static function getAllLanguages()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $default = self::getDefaultLanguage();
        $query->select('lang_id, lang_code, title, `sef`')
            ->from('#__languages')
            ->where('published = 1')
            ->order('ordering');
        $db->setQuery($query);
        $languages = $db->loadObjectList();
        return $languages;
    }

    /**
     *
     * Function to get all available languages except the default language
     * @return languages object list
     */
    public static function getLanguages()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $default = self::getDefaultLanguage();
        $query->select('lang_id, lang_code, title, `sef`')
            ->from('#__languages')
            ->where('published = 1')
            ->where('lang_code != "' . $default . '"')
            ->order('ordering');
        $db->setQuery($query);
        $languages = $db->loadObjectList();
        return $languages;
    }

    /**
     * Get front-end default language
     * @return string
     */
    public static function getDefaultLanguage()
    {
        $params = JComponentHelper::getParams('com_languages');
        return $params->get('site', 'en-GB');
    }

    /**
     * Get default language of user
     *
     */
    public static function getUserLanguage($user_id)
    {
        $default_language = self::getDefaultLanguage();
        if ($user_id > 0) {
            $user = JFactory::getUser($user_id);
            $default_language = $user->getParam('language', $default_language);
        } else {
            return $default_language;
        }
        return $default_language;
    }

    public static function getLanguageFieldValue($obj, $fieldname)
    {
        global $languages;
        $lgs = self::getLanguages();
        $translatable = JLanguageMultilang::isEnabled() && count($lgs);
        if ($translatable) {
            $suffix = self::getFieldSuffix();
            $returnValue = $obj->{$fieldname . $suffix};
            if ($returnValue == "") {
                $returnValue = $obj->{$fieldname};
            }
        } else {
            $returnValue = $obj->{$fieldname};
        }
        return $returnValue;
    }

    public static function getLanguageFieldValueBackend($obj, $fieldname, $suffix)
    {
        global $languages;
        $lgs = self::getLanguages();
        $translatable = JLanguageMultilang::isEnabled() && count($lgs);
        if ($translatable) {
            $returnValue = $obj->{$fieldname . $suffix};
            if ($returnValue == "") {
                $returnValue = $obj->{$fieldname};
            }
        } else {
            $returnValue = $obj->{$fieldname};
        }
        return $returnValue;
    }

    /**
     * Syncronize Membership Pro database to support multilingual
     */
    public static function setupMultilingual()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $languages = self::getLanguages();
        if (count($languages)) {
            //states table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_states");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_states table
                $prefix = $language->sef;
                if (!in_array('state_name_' . $prefix, $fieldArr)) {
                    $fieldName = 'state_name_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_states` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

            //cities table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_cities");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_cities table
                $prefix = $language->sef;
                if (!in_array('city_' . $prefix, $fieldArr)) {
                    $fieldName = 'city_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_cities` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

            //tags table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_tags");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_emails table
                $prefix = $language->sef;
                //$fields = array_keys($db->getTableColumns('#__osrs_emails'));
                if (!in_array('keyword_' . $prefix, $fieldArr)) {
                    $fieldName = 'keyword_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_tags` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
            }


            //emails table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_emails");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_emails table
                $prefix = $language->sef;
                //$fields = array_keys($db->getTableColumns('#__osrs_emails'));
                if (!in_array('email_title_' . $prefix, $fieldArr)) {
                    $fieldName = 'email_title_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_emails` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();

                    $fieldName = 'email_content_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_emails` ADD  `$fieldName` TEXT NULL;";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

            //categories table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_categories");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_categories table
                $prefix = $language->sef;
                if (!in_array('category_name_' . $prefix, $fieldArr)) {
                    $fieldName = 'category_name_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_categories` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();

                    $fieldName = 'category_alias_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_categories` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();

                    $fieldName = 'category_description_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_categories` ADD  `$fieldName` TEXT NULL;";
                    $db->setQuery($sql);
                    $db->query();
                }
            }


            //amenities table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_amenities");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_amenities table
                $prefix = $language->sef;
                if (!in_array('amenities_' . $prefix, $fieldArr)) {
                    $fieldName = 'amenities_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_amenities` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

            //field group table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_fieldgroups");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_amenities table
                $prefix = $language->sef;
                if (!in_array('group_name_' . $prefix, $fieldArr)) {
                    $fieldName = 'group_name_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_fieldgroups` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

            //extra field table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_extra_fields");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_amenities table
                $prefix = $language->sef;
                if (!in_array('field_label_' . $prefix, $fieldArr)) {
                    $fieldName = 'field_label_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_extra_fields` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();

                    $fieldName = 'field_description_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_extra_fields` ADD  `$fieldName` TEXT NULL;";
                    $db->setQuery($sql);
                    $db->query();
                }
            }


            //field group table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_extra_field_options");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_amenities table
                $prefix = $language->sef;
                if (!in_array('field_option_' . $prefix, $fieldArr)) {
                    $fieldName = 'field_option_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_extra_field_options` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

            //osrs_property_field_value table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_property_field_value");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_amenities table
                $prefix = $language->sef;
                if (!in_array('value_' . $prefix, $fieldArr)) {
                    $fieldName = 'value_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_property_field_value` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

            //types table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_types");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_amenities table
                $prefix = $language->sef;
                if (!in_array('type_name_' . $prefix, $fieldArr)) {
                    $fieldName = 'type_name_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_types` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();

                    $fieldName = 'type_alias_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_types` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
            }


            //properties table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_properties");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_properties table
                $prefix = $language->sef;
                if (!in_array('pro_name_' . $prefix, $fieldArr)) {
                    $fieldName = 'pro_name_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_properties` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();

                    $fieldName = 'pro_alias_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_properties` ADD  `$fieldName` VARCHAR( 255 );";
                    $db->setQuery($sql);
                    $db->query();
                }
                if (!in_array('pro_small_desc_' . $prefix, $fieldArr)) {
                    $fieldName = 'pro_small_desc_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_properties` ADD  `$fieldName` TEXT NULL;";
                    $db->setQuery($sql);
                    $db->query();

                    $fieldName = 'pro_full_desc_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_properties` ADD  `$fieldName` TEXT NULL;";
                    $db->setQuery($sql);
                    $db->query();
                }
                if (!in_array('metadesc_' . $prefix, $fieldArr)) {
                    $fieldName = 'metadesc_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_properties` ADD  `$fieldName` VARCHAR (255) DEFAULT '' NOT NULL;";
                    $db->setQuery($sql);
                    $db->query();

                    $fieldName = 'metakey_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_properties` ADD  `$fieldName` VARCHAR (255) DEFAULT '' NOT NULL;";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

            //types table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_agents");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_amenities table
                $prefix = $language->sef;
                if (!in_array('bio_' . $prefix, $fieldArr)) {
                    $fieldName = 'bio_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_agents` ADD  `$fieldName` TEXT NULL;";
                    $db->setQuery($sql);
                    $db->query();
                }
            }


            //companies table
            $db->setQuery("SHOW COLUMNS FROM #__osrs_companies");
            $fields = $db->loadObjectList();
            if (count($fields) > 0) {
                $fieldArr = array();
                for ($i = 0; $i < count($fields); $i++) {
                    $field = $fields[$i];
                    $fieldname = $field->Field;
                    $fieldArr[$i] = $fieldname;
                }
            }
            foreach ($languages as $language) {
                #Process for #__osrs_amenities table
                $prefix = $language->sef;
                if (!in_array('company_description_' . $prefix, $fieldArr)) {
                    $fieldName = 'company_description_' . $prefix;
                    $sql = "ALTER TABLE  `#__osrs_companies` ADD  `$fieldName` TEXT NULL;";
                    $db->setQuery($sql);
                    $db->query();
                }
            }

        }
    }

    /**
     * Check the email message
     *
     */
    public static function isEmptyMailContent($subject, $content)
    {
        if (($subject == "") or (strlen(strip_tags($content)) == 0)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Load language from main component
     *
     */
    public static function loadLanguage()
    {
        static $loaded;
        if (!$loaded) {
            $lang = JFactory::getLanguage();
            $tag = $lang->getTag();
            if (!$tag)
                $tag = 'en-GB';
            $lang->load('com_osproperty', JPATH_ROOT, $tag);
            $loaded = true;
        }
    }

    /**
     * Load current language
     *
     */
    public static function getCurrentLanguage()
    {
        $lang = JFactory::getLanguage();
        $tag = $lang->getTag();
        if (!$tag) {
            $tag = 'en-GB';
        }
        $prefix_language = substr($tag, 0, 2);
        return $prefix_language;
    }

    /**
     * Init data
     *
     */
    public static function initSetup()
    {
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__osrs_init where `name` like 'import_city'");
        $count = $db->loadResult();
        if ($count == 0) {
            $db->setQuery("Select count(id) froM #__osrs_cities where country_id = '194'");
            $count = $db->loadResult();
            if ($count == 0) {
                $configSql = JPATH_ADMINISTRATOR . '/components/com_osproperty/sql/cities.osproperty.sql';
                $sql = JFile::read($configSql);
                $queries = $db->splitSql($sql);
                if (count($queries)) {
                    foreach ($queries as $query) {
                        $query = trim($query);
                        if ($query != '' && $query{0} != '#') {
                            $db->setQuery($query);
                            $db->query();
                        }
                    }
                }
                $db->setQuery("Insert into #__osrs_init (id,`name`,`value`) values (NULL,'import_city','1')");
                $db->query();
            } else {
                $db->setQuery("Insert into #__osrs_init (id,`name`,`value`) values (NULL,'import_city','1')");
                $db->query();
            }
        }
    }

    public static function checkBrowers()
    {
        $browser = new OsBrowser();
        $checkismobile = $browser->returnisMobile();
        if (!$checkismobile) {
            $checkismobile = $browser->isMobile();
        }
        return $checkismobile;
    }

    public static function loadBootstrap($loadJs = true)
    {
        global $configClass;
        $configClass = self::loadConfig();
        $document = JFactory::getDocument();
        if (($configClass['load_bootstrap'] == 1) or (version_compare(JVERSION, '3.0', 'lt'))) {
            if ($loadJs) {
                $document->addScript(JUri::root() . 'components/com_osproperty/js/bootstrap/js/jquery.min.js');
                $document->addScript(JUri::root() . 'components/com_osproperty/js/bootstrap/js/jquery-noconflict.js');
                $document->addScript(JUri::root() . 'components/com_osproperty/js/bootstrap/js/bootstrap.min.js');
            }
        }
        $document->addStyleSheet(JURI::root() . 'components/com_osproperty/js/bootstrap/css/bootstrap.css');
        $document->addStyleSheet(JURI::root() . 'components/com_osproperty/js/bootstrap/css/bootstrap-responsive.css');
        if ($configClass['load_bootstrap_adv'] == 1) {
            $document->addStyleSheet(JURI::root() . 'components/com_osproperty/js/bootstrap/css/bootstrap_adv.css');
        }
    }

    public static function loadBootstrapStylesheet()
    {
        global $configClass;
        $configClass = self::loadConfig();
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'components/com_osproperty/js/bootstrap/css/bootstrap.css');
        $document->addStyleSheet(JURI::root() . 'components/com_osproperty/js/bootstrap/css/bootstrap-responsive.css');
        if ($configClass['load_bootstrap_adv'] == 1) {
            $document->addStyleSheet(JURI::root() . 'components/com_osproperty/js/bootstrap/css/bootstrap_adv.css');
        }
    }

    /**
     *
     * Function to load jQuery chosen plugin
     */

    public static function chosen()
    {
        $configClass = self::loadConfig();
        if ($configClass['load_chosen'] == 1) {
            $document = JFactory::getDocument();
            if (version_compare(JVERSION, '3.0', 'ge')) {
                JHtml::_('formbehavior.chosen', '.chosen');
            } else {
                $document->addStyleSheet(JURI::root() . 'components/com_osproperty/js/chosen/chosen.css');
                ?>
                <script src="<?php echo JURI::root() . "components/com_osproperty/js/chosen/chosen.jquery.js"; ?>"
                        type="text/javascript"></script>
            <?php
            }
            $document->addScriptDeclaration(
                "jQuery(document).ready(function(){
	                    jQuery(\".chosen\").chosen();
	                });");
            $chosenLoaded = true;
        }
    }


    public static function generateWaterMark($id)
    {
        global $mainframe, $configClass;
        $db = JFactory::getDbo();
        $use_watermark = $configClass['images_use_image_watermarks'];
        $watermark_all = $configClass['watermark_all'];
        if ($use_watermark == 1) {
            //get the first image
            $db->setQuery("Select * from #__osrs_photos where pro_id = '$id' order by ordering");
            $rows = $db->loadObjectList();
            if (count($rows) > 0) {
                if ($watermark_all == 1) {
                    for ($i = 0; $i < count($rows); $i++) {
                        $row = $rows[$i];
                        $db->setQuery("Select count(id) from #__osrs_watermark where pid = '$id' and image like '$row->image'");
                        $count = $db->loadResult();
                        if ($count == 0) {
                            //do watermark
                            self::generateWaterMarkForPhoto($id, $row->id);
                        }
                    }
                } else {
                    $row = $rows[0];
                    $db->setQuery("Select count(id) from #__osrs_watermark where pid = '$id' and image like '$row->image'");
                    $count = $db->loadResult();
                    if ($count == 0) {
                        //do watermark
                        self::generateWaterMarkForPhoto($id, $row->id);
                    }
                }
            }
        }//end checking
    }//end function 

    public static function generateWaterMarkForPhoto($pid, $photoid)
    {
        global $mainframe, $configClass;
        $db = JFactory::getDbo();
        $db->setQuery("Select * from #__osrs_properties where id = '$pid'");
        $property = $db->loadObject();
        $wtype = $configClass['watermark_type'];
        switch ($wtype) {
            case "1":
                $watermark_text = $configClass['watermark_text'];
                switch ($watermark_text) {
                    case "1":
                        $db->setQuery("Select category_name from #__osrs_categories where id = '$property->category_id'");
                        $text = $db->loadResult();
                        break;
                    case "2":
                        $db->setQuery("Select type_name from #__osrs_types where id = '$property->pro_type'");
                        $text = $db->loadResult();
                        break;
                    case "3":
                        $text = $configClass['general_bussiness_name'];
                        break;
                    case "4":
                        $text = $configClass['custom_text'];
                        break;
                }
                self::waterMarkText($pid, $photoid, $text);
                break;
            case "2":
                $watermark_photo = $configClass['watermark_photo'];
                if ($watermark_photo == "") {
                    self::waterMarkText($pid, $photoid, $configClass['general_bussiness_name']);
                } elseif (!file_exists(JPATH_ROOT . DS . "images" . DS . $watermark_photo)) {
                    self::waterMarkText($pid, $photoid, $configClass['general_bussiness_name']);
                } else {
                    self::waterMarkPhoto($pid, $photoid, $watermark_photo);
                }
                break;
        }
        //update into watermark table
        $db->setQuery("SELECT image FROM #__osrs_photos WHERE id = '$photoid'");
        $photo = $db->loadResult();
        $db->setQuery("INSERT INTO #__osrs_watermark (id,pid,image) VALUES (NULL,'$pid','$photo')");
        $db->query();
    }

    public static function waterMarkText($pid, $photoid, $text)
    {
        $db = JFactory::getDbo();
        $db->setQuery("SELECT image FROM #__osrs_photos WHERE id = '$photoid'");
        $photo = $db->loadResult();
        $image_path = JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "medium" . DS . $photo;
        self::processTextWatermark($image_path, $text, $image_path);
    }


    public static function waterMarkPhoto($pid, $photoid, $watermarkPhoto)
    {
        $db = JFactory::getDbo();
        $db->setQuery("SELECT image FROM #__osrs_photos WHERE id = '$photoid'");
        $photo = $db->loadResult();
        $image_path = JPATH_ROOT . DS . "images" . DS . "osproperty" . DS . "properties" . DS . $pid . DS . "medium" . DS . $photo;
        self::processPhotoWatermark($image_path, $watermarkPhoto, $image_path);
    }

    function processPhotoWatermark($SourceFile, $tempPhoto, $DestinationFile)
    {
        global $mainframe, $configClass;
        //check the extension of the photo
        list($sw, $sh) = getimagesize(JPATH_ROOT . DS . "images" . DS . $tempPhoto);
        $tempPhotoArr = explode(".", $tempPhoto);
        $ext = strtolower($tempPhotoArr[count($tempPhotoArr) - 1]);
        switch ($ext) {
            case "jpg":
                $p = imagecreatefromjpeg(JPATH_ROOT . DS . "images" . DS . $tempPhoto);
                break;
            case "png":
                $p = imagecreatefrompng(JPATH_ROOT . DS . "images" . DS . $tempPhoto);
                break;
            case "gif":
                $p = imagecreatefromgif(JPATH_ROOT . DS . "images" . DS . $tempPhoto);
                break;
        }
        $image = imagecreatetruecolor($sw, $sh);
        imagealphablending($image, false);

        list($width, $height) = getimagesize($SourceFile);
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($SourceFile);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);

        $watermark_position = $configClass['watermark_position'];

        $matrix_width3 = round($width / 3);
        $matrix_height3 = round($height / 3);

        $matrix_width2 = round($width / 2);
        $matrix_height2 = round($height / 2);
        switch ($watermark_position) {
            case "1":
                $w = 20;
                $h = 20;
                break;
            case "2":
                $w = $matrix_width2 - $sw / 2;
                $h = 20;
                break;
            case "3":
                $w = $matrix_width3 * 3 - 20 - $sw;
                $h = 20;
                break;
            case "4":
                $w = $matrix_width3 * 3 - 20 - $sw;
                $h = $matrix_height2 - $sh / 2;
                break;
            case "5":
                $w = $matrix_width2 - $sw / 2;
                $h = $matrix_height2 - $sh / 2;
                break;
            case "6":
                $w = 20;
                $h = $matrix_height2 - $sh / 2;
                break;
            case "7":
                $w = $matrix_width3 * 3 - 20 - $sw;
                $h = $matrix_height3 * 3 - 20 - $sh;
                break;
            case "8":
                $w = $matrix_width2 - $sw / 2;
                $h = $matrix_height3 * 3 - 20 - $sh;
                break;
            case "9":
                $w = 20;
                $h = $matrix_height3 * 3 - 20 - $sh;
                break;
        }
        imagecopy($image_p, $p, $w, $h, 0, 0, $sw, $sh);
        imagesavealpha($image_p, true);
        if ($DestinationFile != "") {
            imagejpeg($image_p, $DestinationFile, 100);
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($image_p, null, 100);
        };
        imagedestroy($image);
        imagedestroy($image_p);
    }

    /**
     * Watermaking
     *
     * @param unknown_type $SourceFile
     * @param unknown_type $WaterMarkText
     * @param unknown_type $DestinationFile
     */
    public static function processTextWatermark($SourceFile, $WaterMarkText, $DestinationFile)
    {
        global $mainframe, $configClass;
        list($width, $height) = getimagesize($SourceFile);
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($SourceFile);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
        $watermark_color = $configClass['watermark_color'];
        $watermarkArr = explode(",", $watermark_color);
        $text_color = imagecolorallocate($image_p, $watermarkArr[0], $watermarkArr[1], $watermarkArr[2]);
        $font_family = $configClass['watermark_font'];
        if ($font_family == "") {
            $font_family = "arial.ttf";
        }
        $font = JPATH_ROOT . DS . 'components' . DS . 'com_osproperty' . DS . 'helpers' . DS . 'pdf' . DS . 'font' . DS . $font_family;
        $font_size = $configClass['watermark_fontsize'];

        $matrix_width3 = round($width / 3);
        $matrix_height3 = round($height / 3);

        $matrix_width2 = round($width / 2);
        $matrix_height2 = round($height / 2);

        $watermark_position = $configClass['watermark_position'];

        switch ($watermark_position) {
            case "1":
                $w = 20;
                $h = 20 + $font_size;
                break;
            case "2":
                $w = $matrix_width2;
                $h = 20 + $font_size;
                break;
            case "3":
                $w = $matrix_width3 * 2 - 20;
                $h = 20 + $font_size;
                break;
            case "4":
                $w = $matrix_width3 * 2 - 20;
                $h = $matrix_height2;
                break;
            case "5":
                //$lenText = imagefontwidth($font_size)*STRLEN($WaterMarkText);
                $p = imagettfbbox($font_size, 0, $font, $WaterMarkText);

                $txt_width = $p[2] - $p[0];
                $w = $matrix_width2;
                $w = $matrix_width2 - round($txt_width / 2);
                $h = $matrix_height2;
                break;
            case "6":
                $w = 20;
                $h = $matrix_height2;
                break;
            case "7":
                $w = $matrix_width3 * 2 - 20;
                $h = $matrix_height3 * 3 - 10 - $font_size;
                break;
            case "8":
                $w = $matrix_width2;
                $h = $matrix_height3 * 3 - 10 - $font_size;
                break;
            case "9":
                $w = 20;
                $h = $matrix_height3 * 3 - 10 - $font_size;
                break;
        }
        imagettftext($image_p, $font_size, 0, $w, $h, $text_color, $font, $WaterMarkText);
        if ($DestinationFile != "") {
            imagejpeg($image_p, $DestinationFile, $configClass['images_quality']);
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($image_p, null, $configClass['images_quality']);
        };
        imagedestroy($image);
        imagedestroy($image_p);
    }

    /**
     * Load address in format
     *
     * @param unknown_type $property
     * @return unknown
     */
    public static function generateAddress($property)
    {
        global $mainframe, $configClass;

        $configClass = OSPHelper::loadConfig();

        $db = JFactory::getDbo();
        $address = array();

        if ((trim($property->address) != "") and ($property->address != "&nbsp;")) {
            $address[0] = trim($property->address);
        } else {
            $address[0] = "N/A";
        }
        $address[1] = HelperOspropertyCommon::loadCityName($property->city);
        $address[2] = self::loadSateName($property->state);
        $address[3] = $property->region;
        $address[4] = $property->postcode;

        $address_format = $configClass['address_format'];
        if ($address_format == "") { //default value
            $address_format = "0,1,2,3,4";
        }
        //echo $address_format;
        //echo $address_format;
        $returnAddress = array();
        $address_formatArr = explode(",", $address_format);
        for ($i = 0; $i < count($address_formatArr); $i++) {
            $item = $address_formatArr[$i];
            if ($address[$item] != "") {
                $returnAddress[] = $address[$item];
            }
        }
        if (HelperOspropertyCommon::checkCountry()) {
            $returnAddress[] = self::loadCountryName($property->country);
        }
        if (count($returnAddress) > 0) {
            return implode(", ", $returnAddress);
        } else {
            return "";
        }
    }

    public static function loadSateName($state_id)
    {
        global $languages;
        $db = JFactory::getDBO();
        $lgs = self::getLanguages();
        $translatable = JLanguageMultilang::isEnabled() && count($lgs);
        if ($translatable) {
            $suffix = self::getFieldSuffix();
            $db->setQuery("Select state_name" . $suffix . " from #__osrs_states where id = '$state_id'");
        } else {
            $db->setQuery("Select state_name from #__osrs_states where id = '$state_id'");
        }
        return $db->loadResult();
    }

    public static function loadCountryName($country_id)
    {
        $db = JFactory::getDbo();
        $db->setQuery("Select country_name from #__osrs_countries where id = '$country_id'");
        return $db->loadResult();
    }

    public static function returnDateformat($date)
    {
        return date("D, jS M Y H:i", $date);
    }

    public static function resizePhoto($dest, $width, $height)
    {
        global $configClass;
        list($width_orig, $height_orig) = getimagesize($dest);
        if ($width_orig != $width || $height_orig != $height) {
            /*
            $thumbimage = new Image($dest);
            $thumbimage->resize($width, $height);
            $thumbimage->save($dest, $configClass['images_quality']);
            */
            OsImageHelper::createImage($dest, $dest, $width, $height, true);
        }
    }

    public static function useBootstrapSlide()
    {
        global $configClass;
        $configClass = self::loadConfig();
        $load_bootstrap = $configClass['load_bootstrap'];
        if ((version_compare(JVERSION, '3.0', 'ge')) and (intval($load_bootstrap) == 0)) {
            return true;
        } else if ((version_compare(JVERSION, '3.0', 'ge')) and (intval($load_bootstrap) == 1)) {
            return false;
        } else if (version_compare(JVERSION, '3.0', 'lt')) {
            return false;
        } else {
            return false;
        }
    }

    public static function generateHeading($type, $title)
    {
		$org_title = $title;
        $document = JFactory::getDocument();
        $app = JFactory::getApplication();
        $menus = $app->getMenu('site');
        $menu = $menus->getActive();
        if (is_object($menu)) {
            $params = new JRegistry();
            $params->loadString($menu->params);

            if ($params->get('menu-meta_description')) {
                $document->setDescription($params->get('menu-meta_description'));
            }

            if ($params->get('menu-meta_keywords')) {
                $document->setMetadata('keywords', $params->get('menu-meta_keywords'));
            }

            if ($params->get('robots')) {
                $document->setMetadata('robots', $params->get('robots'));
            }
			
            if ($type == 1) {
                $page_title = $params->get('page_title', '');
                if ($page_title != "") {
					$title = $page_title;
                } elseif ($menu->title != "") {
					$title = $menu->title;
                }

				$task = JRequest::getVar('task','');
				if($task == "property_details"){
					$title = $org_title;
				}
			
				if ($app->getCfg('sitename_pagetitles', 0) == 1)
				{
					$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
				}
				elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
				{
					$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
				}
				
				$document->setTitle($title);
            } else {
                $show_page_heading = $params->get('show_page_heading', 1);
                $page_heading = $params->get('page_heading', '');
                if ($show_page_heading == 1) {
                    if ($page_heading != "") {
                        ?>
                        <div class="componentheading">
                            <?php
                            echo $page_heading;
                            ?>
                        </div>
                    <?php
                    } elseif ($menu->title != "") {
                        ?>
                        <div class="componentheading">
                            <?php
                            echo $menu->title;
                            ?>
                        </div>
                    <?php
                    } else {
                        ?>
                        <div class="componentheading">
                            <?php
                            echo $title;
                            ?>
                        </div>
                    <?php
                    }
                }
            }
        } else {
            if ($type == 1) {
				if ($app->getCfg('sitename_pagetitles', 0) == 1)
				{
					$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
				}
				elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
				{
					$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
				}
				$document->setTitle($title);
            } else {
                ?>
                <div class="componentheading">
                    <?php
                    echo $title;
                    ?>
                </div>
            <?php
            }
        }
    }

    /**
     * This function is used to create the folder to save property's photo
     *
     * @param unknown_type $pid
     */
    public static function createPhotoDirectory($pid)
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        if (!JFolder::exists(JPATH_ROOT . '/images/osproperty/properties/' . $pid)) {
            JFolder::create(JPATH_ROOT . '/images/osproperty/properties/' . $pid);
            //copy index.html to this folder
            JFile::copy(JPATH_COMPONENT . DS . 'index.html', JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/index.html');
            if (!JFolder::exists(JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/medium')) {
                JFolder::create(JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/medium');
                JFile::copy(JPATH_COMPONENT . DS . 'index.html', JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/medium/index.html');
            }
            if (!JFolder::exists(JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/thumb')) {
                JFolder::create(JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/thumb');
                JFile::copy(JPATH_COMPONENT . DS . 'index.html', JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/thumb/index.html');
            }
        }
    }

    /**
     * Moving photo from general directory to sub directory
     *
     * @param unknown_type $pid
     */
    public static function movingPhoto($pid)
    {
        jimport('joomla.filesystem.file');
        $db = JFactory::getDbo();
        $db->setQuery("Select image from #__osrs_photos where pro_id = '$pid'");
        $rows = $db->loadObjectList();
        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                if ((JFile::exists(JPATH_ROOT . '/images/osproperty/properties/' . $row->image)) and (!JFile::exists(JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/' . $row->image))) {
                    JFile::copy(JPATH_ROOT . '/images/osproperty/properties/' . $row->image, JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/' . $row->image);
                }
                if ((JFile::exists(JPATH_ROOT . '/images/osproperty/properties/medium/' . $row->image)) and (!JFile::exists(JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/medium/' . $row->image))) {
                    JFile::copy(JPATH_ROOT . '/images/osproperty/properties/medium/' . $row->image, JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/medium/' . $row->image);
                }
                if ((JFile::exists(JPATH_ROOT . '/images/osproperty/properties/thumb/' . $row->image)) and (!JFile::exists(JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/thumb/' . $row->image))) {
                    JFile::copy(JPATH_ROOT . '/images/osproperty/properties/thumb/' . $row->image, JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/thumb/' . $row->image);
                }
            }
        }
    }

    /**
     * Moving photo from general directory to sub directory in Sample Data installation
     *
     * @param unknown_type $pid
     */
    public static function movingPhotoSampleData($pid)
    {
        jimport('joomla.filesystem.file');
        $db = JFactory::getDbo();
        $db->setQuery("Select image from #__osrs_photos where pro_id = '$pid'");
        $rows = $db->loadObjectList();
        if (count($rows) > 0) {
            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (JFile::exists(JPATH_ROOT . '/images/osproperty/properties/' . $row->image)) {
                    JFile::copy(JPATH_ROOT . '/images/osproperty/properties/' . $row->image, JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/' . $row->image);
                }
                if (JFile::exists(JPATH_ROOT . '/images/osproperty/properties/medium/' . $row->image)) {
                    JFile::copy(JPATH_ROOT . '/images/osproperty/properties/medium/' . $row->image, JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/medium/' . $row->image);
                }
                if (JFile::exists(JPATH_ROOT . '/images/osproperty/properties/thumb/' . $row->image)) {
                    JFile::copy(JPATH_ROOT . '/images/osproperty/properties/thumb/' . $row->image, JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/thumb/' . $row->image);
                }
            }
        }
    }

    /**
     * Show Property photo
     *
     * @param unknown_type $image
     * @param unknown_type $image_folder
     * @param unknown_type $pid
     * @param unknown_type $style
     * @param unknown_type $class
     * @param unknown_type $js
     */
    public static function showPropertyPhoto($image, $image_folder, $pid, $style, $class, $js)
    {
        if ($image_folder != "") {
            $image_folder = $image_folder . '/';
        }
        if ($image != "") {
            if (file_exists(JPATH_ROOT . '/images/osproperty/properties/' . $pid . '/' . $image_folder . $image)) {
                ?>
                <img
                    src="<?php echo JURI::root() ?>/images/osproperty/properties/<?php echo $pid . '/' . $image_folder . $image; ?>"
                    class="<?php echo $class ?>" style="<?php echo $style ?>" <?php echo $js ?> />
            <?php
            } else {
                ?>
                <img src="<?php echo JURI::root() ?>components/com_osproperty/images/assets/nopropertyphoto.png"
                     class="<?php echo $class ?>" style="<?php echo $style ?>"/>
            <?php
            }
        } else {
            ?>
            <img src="<?php echo JURI::root() ?>components/com_osproperty/images/assets/nopropertyphoto.png"
                 class="<?php echo $class ?>" style="<?php echo $style ?>"/>
        <?php
        }
    }

    public static function checkImage($image)
    {
        //checks if the file is a browser compatible image
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        $mimes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
        //get mime type
        $mime = getimagesize($image);
        $mime = $mime ['mime'];

        $extensions = array('jpg');
        $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (in_array($extension, $extensions) and in_array($mime, $mimes))
            return TRUE;
        else
            JFile::delete($image);
        return 'application/octet-stream';
    }


    public static function getImages($folder)
    {
        $files = array();
        $images = array();

        // check if directory exists
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..' && $file != 'CVS' && $file != 'index.html') {
                        $files [] = $file;
                    }
                }
            }
            closedir($handle);
            $i = 0;
            foreach ($files as $img) {
                if (!is_dir($folder . DS . $img)) {
//					self::checkImage($folder . DS . $img);
                    $images [$i]->name = $img;
                    $images [$i]->folder = $folder;
                    ++$i;
                }
            }
        }
        return $images;
    }

    /**
     * Generate alias
     *
     * @param unknown_type $type
     * @param unknown_type $id
     * @param unknown_type $alias
     */
    static function generateAlias($type, $id, $alias)
    {
        global $mainframe;
        $db = JFactory::getDbo();
        if ($alias != "") {
            //$alias = JString::increment($alias, 'dash');
            $alias   = JApplication::stringURLSafe($alias);
        }
        switch ($type) {
            case "property":
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_properties where pro_alias like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $pro_alias = $alias . " " . $id;
                    } else {
                        $pro_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select pro_name from #__osrs_properties where id = '$id'");
                    $pro_name = $db->loadResult();
                    //$pro_alias   = JApplication::stringURLSafe($pro_name);
                    $pro_alias = JApplication::stringURLSafe($pro_name);
                    //$pro_alias = JString::increment($pro_name, 'dash');
                    if($pro_alias == ""){
                        $pro_alias = JText::_('OS_PROPERTY')."-".date("Y-m-d H:i:s",time());
                        $pro_alias = JApplication::stringURLSafe($pro_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_properties where pro_alias like '$pro_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $pro_alias = $pro_alias . " " . $id;
                    }
                }
                $pro_alias = JApplication::stringURLSafe($pro_alias);
                return $pro_alias;
                break;
            case "agent":
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_agents where alias like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $agent_alias = $alias . " " . $id;
                    } else {
                        $agent_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select name from #__osrs_agents where id = '$id'");
                    $agent_name = $db->loadResult();
                    $agent_alias = JApplication::stringURLSafe($agent_name);
                    if($agent_alias == ""){
                        $agent_alias = JText::_('OS_AGENT')."-".date("Y-m-d H:i:s",time());
                        $agent_alias = JApplication::stringURLSafe($agent_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_agents where alias like '$agent_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $agent_alias = $agent_alias . " " . $id;
                    }
                }
                //$agent_alias = mb_strtolower(str_replace(" ", "-", $agent_alias));
                $agent_alias = JApplication::stringURLSafe($agent_alias);
                return $agent_alias;
                break;
            case "company":
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_companies where company_alias like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $company_alias = $alias . " " . $id;
                    } else {
                        $company_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select company_name from #__osrs_companies where id = '$id'");
                    $company_name = $db->loadResult();
                    $company_alias = JApplication::stringURLSafe($company_name);
                    if($company_alias == ""){
                        $company_alias = JText::_('OS_COMPANY')."-".date("Y-m-d H:i:s",time());
                        $company_alias = JApplication::stringURLSafe($company_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_companies where company_alias like '$company_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $company_alias = $company_alias . " " . $id;
                    }
                }
               // $company_alias = mb_strtolower(str_replace(" ", "-", $company_alias));
                $company_alias = JApplication::stringURLSafe($company_alias);
                return $company_alias;
                break;
            case "category":
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_categories where category_alias like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $category_alias = $alias . " " . $id;
                    } else {
                        $category_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select category_name from #__osrs_categories where id = '$id'");
                    $category_name = $db->loadResult();
                    $category_alias = JApplication::stringURLSafe($category_name);
                    if($category_alias == ""){
                        $category_alias = JText::_('OS_CATEGORY')."-".date("Y-m-d H:i:s",time());
                        $category_alias = JApplication::stringURLSafe($category_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_categories where category_alias like '$category_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $category_alias = $category_alias . " " . $id;
                    }
                }
                //$category_alias = mb_strtolower(str_replace(" ", "-", $category_alias));
                $category_alias = JApplication::stringURLSafe($category_alias);
                return $category_alias;
                break;
            case "type":
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_types where type_alias like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $type_alias = $alias . " " . $id;
                    } else {
                        $type_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select type_name from #__osrs_types where id = '$id'");
                    $type_name = $db->loadResult();
                    $type_alias = JApplication::stringURLSafe($type_name);
                    if($type_alias == ""){
                        $type_alias = JText::_('OS_TYPE')."-".date("Y-m-d H:i:s",time());
                        $type_alias = JApplication::stringURLSafe($type_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_types where type_alias like '$type_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $type_alias = $type_alias . " " . $id;
                    }
                }
                $type_alias = JApplication::stringURLSafe($type_alias);
                return $type_alias;
                break;
        }
    }

    /**
     * Generate alias
     *
     * @param unknown_type $type
     * @param unknown_type $id
     * @param unknown_type $alias
     */
    static function generateAliasMultipleLanguages($type, $id, $alias, $langCode)
    {
        global $mainframe;
        $db = JFactory::getDbo();
        if ($alias != "") {
            $alias = JApplication::stringURLSafe($alias);
        }
        switch ($type) {
            case "property":
                $alias_field_name = "pro_alias_" . $langCode;
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_properties where `$alias_field_name` like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $pro_alias = $alias . " " . $id;
                    } else {
                        $pro_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select pro_name_$langCode from #__osrs_properties where id = '$id'");
                    $pro_name = $db->loadResult();
                    $pro_alias = JApplication::stringURLSafe($pro_name);
                    if($pro_alias == ""){
                        $pro_alias = JText::_('OS_PROPERTY')."-".date("Y-m-d H:i:s",time());
                        $pro_alias = JApplication::stringURLSafe($pro_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_properties where `$alias_field_name` like '$pro_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $pro_alias = $pro_alias . " " . $id;
                    }
                }
                $pro_alias = JApplication::stringURLSafe($pro_alias);
                return $pro_alias;
                break;
            case "agent":
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_agents where alias like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $agent_alias = $alias . " " . $id;
                    } else {
                        $agent_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select name from #__osrs_agents where id = '$id'");
                    $agent_name = $db->loadResult();
                    $agent_alias = JApplication::stringURLSafe($agent_name);
                    if($agent_alias == ""){
                        $agent_alias = JText::_('OS_AGENT')."-".date("Y-m-d H:i:s",time());
                        $agent_alias = JApplication::stringURLSafe($agent_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_agents where alias like '$agent_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $agent_alias = $agent_alias . " " . $id;
                    }
                }
                $agent_alias = JApplication::stringURLSafe($agent_alias);
                return $agent_alias;
                break;
            case "company":
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_companies where company_alias like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $company_alias = $alias . " " . $id;
                    } else {
                        $company_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select company_name from #__osrs_companies where id = '$id'");
                    $company_name = $db->loadResult();
                    $company_alias = JApplication::stringURLSafe($company_name);
                    if($company_alias == ""){
                        $company_alias = JText::_('OS_COMPANY')."-".date("Y-m-d H:i:s",time());
                        $company_alias = JApplication::stringURLSafe($company_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_companies where company_alias like '$company_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $company_alias = $company_alias . " " . $id;
                    }
                }
                $company_alias = JApplication::stringURLSafe($company_alias);
                return $company_alias;
                break;
            case "category":
                $alias_field_name = "category_alias_" . $langCode;
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_categories where `$alias_field_name` like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $category_alias = $alias . " " . $id;
                    } else {
                        $category_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select category_name_" . $langCode . " from #__osrs_categories where id = '$id'");
                    $category_name = $db->loadResult();
                    $category_alias = JApplication::stringURLSafe($category_name);
                    if($category_alias == ""){
                        $category_alias = JText::_('OS_CATEGORY')."-".date("Y-m-d H:i:s",time());
                        $category_alias = JApplication::stringURLSafe($category_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_categories where `$alias_field_name` like '$category_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $category_alias = $category_alias . " " . $id;
                    }
                }
                $category_alias = JApplication::stringURLSafe($category_alias);
                return $category_alias;
                break;
            case "type":
                $alias_field_name = "type_alias_" . $langCode;
                if ($alias != "") {
                    $db->setQuery("Select count(id) from #__osrs_types where `$alias_field_name` like '$alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $type_alias = $alias . " " . $id;
                    } else {
                        $type_alias = $alias;
                    }
                } else {
                    $db->setQuery("Select type_name_" . $langCode . " from #__osrs_types where id = '$id'");
                    $type_name = $db->loadResult();
                    $type_alias = JApplication::stringURLSafe($type_name);
                    if($type_alias == ""){
                        $type_alias = JText::_('OS_TYPE')."-".date("Y-m-d H:i:s",time());
                        $type_alias = JApplication::stringURLSafe($type_alias);
                    }
                    $db->setQuery("Select count(id) from #__osrs_types where `$alias_field_name` like '$type_alias' and id <> '$id'");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $type_alias = $type_alias . " " . $id;
                    }
                }
                $type_alias = JApplication::stringURLSafe($type_alias);
                return $type_alias;
                break;
        }
    }

    /**
     * Get IP address of customers
     *
     * @return unknown
     */
    public static function get_ip_address()
    {
        foreach (array(
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    /**
     * Get data by using curl
     *
     * @param unknown_type $path
     * @return unknown
     */
    public static function get_data($path)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $retValue = curl_exec($ch);
        curl_close($ch);
        return $retValue;
    }

    /**
     * Spam deteach
     */
    public static function spamChecking()
    {
        global $mainframe;
        $botscoutUrl = 'http://www.stopforumspam.com/api?ip=';
        $accFrequency = 0;
        $access = 'yes';
        $option = JRequest::getVar('option');
        // Check we are manipulating a valid form and if we are in admin.
        $ip = self::get_ip_address();
        $url = $botscoutUrl . $ip;
        $xmlDatas = simplexml_load_string(self::get_data($url));
        if ($xmlDatas->appears == $access && $xmlDatas->frequency >= $accFrequency) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Build the select list for parent menu item
     */
    public static function listCategoriesInMultiple($category_ids, $onChangeScript)
    {
        global $mainframe;
        $parentArr = array();
        $parentArr = self::loadCategoryOptions($category_ids, $onChangeScript, 0);
        $output = JHTML::_('select.genericlist', $parentArr, 'category_ids[]', 'style="min-height:100px;" multiple class="input-large chosen" ' . $onChangeScript, 'value', 'text', $category_ids);
        return $output;
    }

    /**
     * Build the select list for parent menu item
     */
    public static function listCategories($category_id, $onChangeScript)
    {
        global $mainframe;
        $parentArr = array();
        $parentArr = self::loadCategoryOptions($category_id, $onChangeScript, 1);
        $output = JHTML::_('select.genericlist', $parentArr, 'category_id', 'class="input-medium" ' . $onChangeScript, 'value', 'text', $category_id);
        return $output;
    }

    public static function loadCategoryOptions($category_id, $onChangeScript, $hasFirstOption = 0)
    {
        global $mainframe, $lang_suffix;
        $user = JFactory::getUser();
        $lang_suffix = self::getFieldSuffix();
        $db = JFactory::getDBO();

        $query = 'SELECT *,id as value,category_name' . $lang_suffix . ' AS text,category_name' . $lang_suffix . ' AS treename,category_name' . $lang_suffix . ' as category_name,category_name' . $lang_suffix . ' as title,parent_id as parent ' .
            ' FROM #__osrs_categories ' .
            ' WHERE published = 1';
        if (intval($user->id) > 0) {
            $special = HelperOspropertyCommon::checkSpecial();
            if ($special) {
                $query .= " and `access` in (0,1,2) ";
            } else {
                $query .= " and `access` in (0,1) ";
            }
        } else {
            $query .= " and `access` = '0' ";
        }
        $query .= ' ORDER BY parent_id, ordering';
        $db->setQuery($query);
        $mitems = $db->loadObjectList();
        // establish the hierarchy of the menu
        $children = array();
        if ($mitems) {
            // first pass - collect children
            foreach ($mitems as $v) {
                $pt = $v->parent_id;
                if ($v->treename == "") {
                    $v->treename = $v->category_name;
                }
                if ($v->title == "") {
                    $v->title = $v->category_name;
                }
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }

        // second pass - get an indent list of the items
        $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
        // assemble menu items to the array
        $parentArr = array();
        if ($hasFirstOption == 1) {
            $parentArr[] = JHTML::_('select.option', '', JText::_('OS_ALL_CATEGORIES'));
        }

        foreach ($list as $item) {
            //if($item->treename != ""){
            //$item->treename = str_replace("&nbsp;","*",$item->treename);
            //}
            $var = explode("*", $item->treename);

            if (count($var) > 0) {
                $treename = "";
                for ($i = 0; $i < count($var) - 1; $i++) {
                    $treename .= " _ ";
                }
            }
            $text = $item->treename;
            $parentArr[] = JHTML::_('select.option', $item->id, $text);
        }
        return $parentArr;
    }


    /**
     * Build the multiple select list for parent menu item
     */
    public static function listCategoriesCheckboxes($categoryArr)
    {
        global $mainframe;
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__osrs_categories where published = '1'");
        $count_categories = $db->loadResult();
        $parentArr = self::loadCategoryBoxes($categoryArr);
        ob_start();
        ?>
        <input type="checkbox" name="check_all_cats" id="check_all_cats" value="1" checked
               onclick="javascript:checkCats()"/>&nbsp;&nbsp;<strong><?php echo JText::_('OS_CATEGORIES')?></strong>
        <input type="hidden" name="count_categories" id="count_categories" value="<?php echo $count_categories?>"/>
        <BR/>
        <?php
        for ($i = 0; $i < count($parentArr); $i++) {
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $parentArr[$i];
            echo "<BR />";
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public static function loadCategoryBoxes($categoryArr)
    {
        global $mainframe, $lang_suffix;
        $db = JFactory::getDBO();
        $lang_suffix = OSPHelper::getFieldSuffix();
        // get a list of the menu items
        // excluding the current cat item and its child elements
//		$query = 'SELECT *' .
        $query = 'SELECT *, id as value,category_name' . $lang_suffix . ' AS title,category_name' . $lang_suffix . ' AS category_name,parent_id as parent ' .
            ' FROM #__osrs_categories ' .
            ' WHERE published = 1';
        $user = JFactory::getUser();
        if (intval($user->id) > 0) {
            $special = HelperOspropertyCommon::checkSpecial();
            if ($special) {
                $query .= " and `access` in (0,1,2) ";
            } else {
                $query .= " and `access` in (0,1) ";
            }
        } else {
            $query .= " and `access` = '0' ";
        }
        $query .= ' ORDER BY parent_id, ordering';
        $db->setQuery($query);
        $mitems = $db->loadObjectList();

        // establish the hierarchy of the menu
        $children = array();

        if ($mitems) {
            // first pass - collect children
            foreach ($mitems as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }

        // second pass - get an indent list of the items
        $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
        // assemble menu items to the array
        $parentArr = array();

        foreach ($list as $item) {
            $checked = "";
            if ($item->treename != "") {
                $item->treename = str_replace("&nbsp;", "", $item->treename);
            }
            $var = explode("-", $item->treename);
            $treename = "";
            for ($i = 0; $i < count($var) - 1; $i++) {
                $treename .= "- -";
            }
            $text = $treename . $item->category_name;
            if (isset($categoryArr)) {
                if (in_array($item->value, $categoryArr)) {
                    $checked = "checked";
                } elseif (count($categoryArr) == 0) {
                    $checked = "checked";
                }
            }
            $parentArr[] = '<input type="checkbox" id="all_categories' . $item->value . '" name="categoryArr[]" ' . $checked . ' value="' . $item->value . '" />&nbsp;&nbsp;' . $text . '';
        }
        return $parentArr;
    }

    public static function loadAgentType($agent_id)
    {
        global $mainframe;
        $db = JFactory::getDbo();
        $db->setQuery("Select agent_type from #__osrs_agents where id = '$agent_id'");
        $agent_type = $db->loadResult();
        switch ($agent_type) {
            case "0":
            default:
                return JText::_('OS_AGENT');
                break;
            case "1":
                return JText::_('OS_OWNER');
                break;
        }
    }

    public static function loadAgentTypeDropdown($agent_type)
    {
        global $mainframe;
        $optionArr = array();
        $optionArr[] = JHTML::_('select.option', '0', JText::_('OS_AGENT'));
        $optionArr[] = JHTML::_('select.option', '1', JText::_('OS_OWNER'));
        echo JHTML::_('select.genericlist', $optionArr, 'agent_type', 'class="input-small" onChange="javascript:updateCompanyDropdown()"', 'value', 'text', $agent_type);
        ?>
        <script language="javascript">
            function updateCompanyDropdown() {
                var agent_type = document.getElementById('agent_type');
                var company_id = document.getElementById('company_id');
                if (agent_type.value == 1) {
                    company_id.disabled = true;
                } else {
                    company_id.disabled = false;
                }
            }
        </script>
    <?php
    }

    public static function getStringRequest($name, $defaultvalue = '', $method = 'post')
    {
        $temp = JRequest::getVar($name, $defaultvalue, $method, 'string');
        $badchars = array('#', '>', '<', '\\');
        $temp = trim(str_replace($badchars, '', $temp));
        $temp = htmlspecialchars($temp);
        return $temp;
    }

    static function showSquareLabels()
    {
        global $mainframe, $configClass;
        $configClass = self::loadConfig();
        if ($configClass['use_square'] == 0) {
            return JText::_('OS_SQUARE_FEET');
        } else {
            return JText::_('OS_SQUARE_METER');
        }
    }

    static function showSquareSymbol()
    {
        global $mainframe, $configClass;
        $configClass = self::loadConfig();
        if ($configClass['use_square'] == 0) {
            return JText::_('OS_SQFT');
        } else {
            return JText::_('OS_SQMT');
        }
    }

    /**
     * Converts a given size with units e.g. read from php.ini to bytes.
     *
     * @param   string $val Value with units (e.g. 8M)
     * @return  int     Value in bytes
     * @since   3.0
     */
    public static function iniToBytes($val)
    {
        $val = trim($val);

        switch (strtolower(substr($val, -1))) {
            case 'm':
                $val = (int)substr($val, 0, -1) * 1048576;
                break;
            case 'k':
                $val = (int)substr($val, 0, -1) * 1024;
                break;
            case 'g':
                $val = (int)substr($val, 0, -1) * 1073741824;
                break;
            case 'b':
                switch (strtolower(substr($val, -2, 1))) {
                    case 'm':
                        $val = (int)substr($val, 0, -2) * 1048576;
                        break;
                    case 'k':
                        $val = (int)substr($val, 0, -2) * 1024;
                        break;
                    case 'g':
                        $val = (int)substr($val, 0, -2) * 1073741824;
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }

        return $val;
    }


    /**
     * Generate price value
     *
     * @param unknown_type $curr
     * @param unknown_type $price
     */
    public static function generatePrice($curr, $price)
    {
        global $configClass;
        $configClass = self::loadConfig();
        if ($configClass['currency_position'] == 0) {
            return HelperOspropertyCommon::loadCurrency($curr) . " " . HelperOspropertyCommon::showPrice($price);
        } else {
            return HelperOspropertyCommon::showPrice($price) . " " . HelperOspropertyCommon::loadCurrency($curr);
        }
    }

    /**
     * Show Price Filter
     *
     * @param unknown_type $option_id
     * @param unknown_type $max_price
     * @param unknown_type $min_price
     * @param unknown_type $property_type
     * @param unknown_type $style
     */
    public static function showPriceFilter($option_id, $min_price, $max_price, $property_type, $style, $prefix)
    {
        global $configClass;
        $configClass = self::loadConfig();
        $document = JFactory::getDocument();
        $db = JFactory::getDbo();
        $min_price_slider = $configClass['min_price_slider'];
        $max_price_slider = $configClass['max_price_slider'];
        $price_step_amount = $configClass['price_step_amount'];
        if($price_step_amount == ""){
            $price_step_amount = 1000;
        }

        if($max_price_slider != ""){
            $max_price_value = $max_price_slider;
        }else {
            $db->setQuery("Select price from #__osrs_properties order by price desc limit 1");
            $max_price_value = $db->loadResult();
        }
        if($min_price_slider != ""){
            $min_price_value = $min_price_slider;
        }else{
            $db->setQuery("Select price from #__osrs_properties where price_call = 0 order by price limit 1");
            $min_price_value = $db->loadResult();
        }


        if (intval($max_price) == 0) {
            $max_price = $max_price_value;
        }
        if ($min_price_value == $max_price_value) {
            if($min_price_slider != ""){
                $max_price = $min_price_slider;
            }else{
                $max_price = 0;
            }
        }
        if ($configClass['price_filter_type'] == 1) {
            $document->addStyleSheet("//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css");
            ?>
            <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js" type="text/javascript"></script>
            <script src="<?php echo JUri::root() ?>components/com_osproperty/js/jquery.ui.touch-punch.js"
                    type="text/javascript"></script>
            <?php
            $document->addScript(JURI::root() . "components/com_osproperty/js/autoNumeric.js");
            ?>
            <script>
                jQuery.ui.slider.prototype.widgetEventPrefix = 'slider';
                jQuery(function () {
                    jQuery("#<?php echo $prefix;?>sliderange").slider({
                        range: true,
                        min: <?php echo intval($min_price_value);?>,
                        step: <?php echo $price_step_amount;?>,
                        max: <?php echo intval($max_price_value);?>,
                        values: [<?php echo intval($min_price);?>, <?php echo intval($max_price);?>],
                        slide: function (event, ui) {
                            var price_from = ui.values[0];
                            var price_to = ui.values[1];
                            jQuery("#<?php echo $prefix;?>price_from_input1").val(price_from);
                            jQuery("#<?php echo $prefix;?>price_to_input1").val(price_to);

                            price_from = price_from.formatMoney(0, ',', '.');
                            price_to = price_to.formatMoney(0, ',', '.');

                            jQuery("#<?php echo $prefix;?>price_from_input").text(price_from);
                            jQuery("#<?php echo $prefix;?>price_to_input").text(price_to);
                        }
                    });
                });
                Number.prototype.formatMoney = function (decPlaces, thouSeparator, decSeparator) {
                    var n = this,
                        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
                        decSeparator = decSeparator == undefined ? "." : decSeparator,
                        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
                        sign = n < 0 ? "-" : "",
                        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
                        j = (j = i.length) > 3 ? j % 3 : 0;
                    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
                };
            </script>
            <div id="<?php echo $prefix; ?>sliderange"></div>
            <div class="clearfix"></div>
            <?php
            if ((strpos($prefix, "adv") === FALSE) and (strpos($prefix, "list") === FALSE)) {
                $span = "span6";
                $style = "margin-top:10px;margin-left:0px;";
                $style1 = "font-size:11px;text-align:left; width: 48.93617021276595%;*width: 48.88297872340425%;float:left;";
                $style2 = "font-size:11px;text-align:right; width: 48.93617021276595%;*width: 48.88297872340425%;float:left;";
                $input_class_name = "input-mini";
            } else {
                $span = "span6";
                $style = "";
                $style1 = "margin-top:10px;margin-left:0px;text-align:left;width: 48.93617021276595%; *width: 48.88297872340425%;float:left;";
                $style2 = "margin-top:10px;margin-left:0px;text-align:right;width: 48.93617021276595%; *width: 48.88297872340425%;float:left;";
                $input_class_name = "input-small";
            }
            ?>
            <div class="row-fluid">
                <div class="<?php echo $span ?>" style="<?php echo $style; ?><?php echo $style1 ?>">
                    <?php if ((strpos($prefix, "adv") !== FALSE) or (strpos($prefix, "list") !== FALSE)) { ?>
                        <?php echo JText::_('OS_MIN') ?>
                    <?php } ?>
                    (<?php echo HelperOspropertyCommon::loadCurrency(); ?>).
                    <span
                        id="<?php echo $prefix; ?>price_from_input"><?php echo number_format($min_price, 0, '', ','); ?></span>
                    <input type="hidden" name="min_price" id="<?php echo $prefix; ?>price_from_input1"
                           value="<?php echo $min_price; ?>"/>
                </div>
                <div class="<?php echo $span ?>" style="<?php echo $style; ?><?php echo $style2 ?>">
                    <?php if ((strpos($prefix, "adv") !== FALSE) or (strpos($prefix, "list") !== FALSE)) { ?>
                        <?php echo JText::_('OS_MAX') ?>
                    <?php } ?>
                    (<?php echo HelperOspropertyCommon::loadCurrency(); ?>).
                    <span
                        id="<?php echo $prefix; ?>price_to_input"><?php echo number_format($max_price, 0, '', ','); ?></span>
                    <input type="hidden" name="max_price" id="<?php echo $prefix; ?>price_to_input1"
                           value="<?php echo $max_price; ?>"/>
                </div>
            </div>
        <?php
        } else {
            echo HelperOspropertyCommon::generatePriceList($property_type, $option_id, $style);
        }
    }

    /**
     * check Owner is existing or not
     *
     */
    public static function checkOwnerExisting()
    {
        global $mainframe;
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__osrs_agents where agent_type = '1' and published = '1'");
        $count = $db->loadResult();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if user use one state in the system
     */
    public static function userOneState()
    {
        $configClass = self::getConfig();
        if (!HelperOspropertyCommon::checkCountry()) {
            $defaultcounty = $configClass['show_country_id'];
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('count(id)')->from('#__osrs_states')->where('country_id = "' . $defaultcounty . '" and published = "1"');
            $db->setQuery($query);
            $count_state = $db->loadResult();
            if ($count_state == 1) {
                return true;
            }
        }
        return false;
    }

    public static function returnDefaultState()
    {
        $configClass = self::getConfig();
        if (self::userOneState()) {
            $defaultcounty = $configClass['show_country_id'];
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id')->from('#__osrs_states')->where('country_id = "' . $defaultcounty . '" and published = "1"');
            $db->setQuery($query);
            return $db->loadResult();
        }
        return 0;
    }

    public static function returnDefaultStateName()
    {
        $db = JFactory::getDbo();
        $lgs = self::getLanguages();
        $translatable = JLanguageMultilang::isEnabled() && count($lgs);
        if ($translatable) {
            $suffix = OSPHelper::getFieldSuffix();
        }
        if (self::returnDefaultState() > 0) {
            $query = $db->getQuery(true);
            $query->select('state_name' . $suffix . ' as state_name')->from('#__osrs_states')->where('id="' . self::returnDefaultState() . '"');
            $db->setQuery($query);
            return $db->loadResult();
        }
        return '';
    }

    public static function getConfig()
    {
        $db = JFactory::getDbo();
        $db->setQuery("Select * from #__osrs_configuration");
        $configs = $db->loadObjectList();
        $configClass = array();
        foreach ($configs as $config) {
            $configClass[$config->fieldname] = $config->fieldvalue;
        }

        $curr = $configClass['general_currency_default'];
        $arrCode = array();
        $arrSymbol = array();

        $db->setQuery("Select * from #__osrs_currencies where id = '$curr'");
        $currency = $db->loadObject();
        $symbol = $currency->currency_symbol;
        $index = -1;
        if ($symbol == "") {
            $symbol = '$';
        }

        $configClass['curr_symbol'] = $symbol;
        return $configClass;
    }

    public static function dropdropBath($name, $bath, $class, $jsScript, $firstOption)
    {
        $configClass = self::loadConfig();
        $bathArr = array();
        $bathArr[] = JHTML::_('select.option', '', JText::_($firstOption));
        for ($i = 1; $i <= 10; $i++) {
            $bathArr[] = JHTML::_('select.option', $i, $i);
            if ($configClass['fractional_bath'] == 1) {
                $bathArr[] = JHTML::_('select.option', $i . '.25', $i . '.25');
                $bathArr[] = JHTML::_('select.option', $i . '.50', $i . '.50');
                $bathArr[] = JHTML::_('select.option', $i . '.75', $i . '.75');
            }
        }
        return JHTML::_('select.genericlist', $bathArr, $name, 'class="' . $class . '" ' . $jsScript, 'value', 'text', $bath);
    }

    public static function dropdropBed($name, $bed, $class, $jsScript, $firstOption)
    {
        $bedArr = array();
        $bedArr[] = JHTML::_('select.option', '', JText::_($firstOption));
        for ($i = 1; $i <= 20; $i++) {
            $bedArr[] = JHTML::_('select.option', $i, $i);
        }
        return JHTML::_('select.genericlist', $bedArr, $name, 'class="' . $class . '" ' . $jsScript, 'value', 'text', $bed);
    }

    public static function dropdropRoom($name, $room, $class, $jsScript, $firstOption)
    {
        $roomArr = array();
        $roomArr[] = JHTML::_('select.option', '', JText::_($firstOption));
        for ($i = 1; $i <= 20; $i++) {
            $roomArr[] = JHTML::_('select.option', $i, $i);
        }
        return JHTML::_('select.genericlist', $roomArr, $name, 'class="' . $class . '" ' . $jsScript, 'value', 'text', $room);
    }

    public static function dropdropFloor($name, $room, $class, $jsScript, $firstOption)
    {
        $roomArr = array();
        $roomArr[] = JHTML::_('select.option', '', JText::_($firstOption));
        for ($i = 1; $i <= 20; $i++) {
            $roomArr[] = JHTML::_('select.option', $i, $i);
        }
        return JHTML::_('select.genericlist', $roomArr, $name, 'class="' . $class . '" ' . $jsScript, 'value', 'text', $room);
    }

    public static function checkboxesCategory($name, $catArr)
    {
        $db = JFactory::getDbo();
        $db->setQuery("Select * from #__osrs_categories where published = '1' order by ordering");
        $rows = $db->loadObjectList();
        $tempArr = array();
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                if (in_array($row->id, $catArr)) {
                    $checked = "checked";
                }else{
                    $checked = "";
                }

                $tempArr[] = "<input type='checkbox' name='$name' value='$row->id' $checked> ".self::getLanguageFieldValue($row,'category_name');
            }
        }
        return $tempArr;
    }

    public static function dropdownCategory($name, $catArr, $class)
    {
        $onChangeScript = "";
        $parentArr = self::loadCategoryOptions($catArr, $onChangeScript);
        return JHTML::_('select.genericlist', $parentArr, $name, 'multiple class="' . $class . '" ' . $onChangeScript, 'value', 'text', $catArr);
    }

    //Load Categories Options of Multiple Dropdown Select List: Category
    public static function loadCategoriesOptions($onChangeScript)
    {
        global $mainframe, $lang_suffix;
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
        if ($app->isAdmin()) {
            $lang_suffix = "";
        } else {
            $lang_suffix = OSPHelper::getFieldSuffix();
        }
        $query = 'SELECT *,id as value,category_name' . $lang_suffix . ' AS text,category_name' . $lang_suffix . ' AS treename,category_name' . $lang_suffix . ' as category_name,parent_id as parent ' .
            ' FROM #__osrs_categories ' .
            ' WHERE published = 1';
        $user = JFactory::getUser();
        if (intval($user->id) > 0) {
            $special = HelperOspropertyCommon::checkSpecial();
            if ($special) {
                $query .= " and `access` in (0,1,2) ";
            } else {
                $query .= " and `access` in (0,1) ";
            }
        } else {
            $query .= " and `access` = '0' ";
        }
        $query .= ' ORDER BY parent_id, ordering';
        $db->setQuery($query);
        $mitems = $db->loadObjectList();
        // establish the hierarchy of the menu
        $children = array();
        if ($mitems) {
            // first pass - collect children
            foreach ($mitems as $v) {
                $pt = $v->parent_id;
                if ($v->treename == "") {
                    $v->treename = $v->category_name;
                }
                if ($v->title == "") {
                    $v->title = $v->category_name;
                }
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }

        // second pass - get an indent list of the items
        $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
        // assemble menu items to the array
        $parentArr = array();
        foreach ($list as $item) {
            //if($item->treename != ""){
            //$item->treename = str_replace("&nbsp;","*",$item->treename);
            //}
            $var = explode("*", $item->treename);

            if (count($var) > 0) {
                $treename = "";
                for ($i = 0; $i < count($var) - 1; $i++) {
                    $treename .= " _ ";
                }
            }
            $text = $item->treename;
            $parentArr[] = JHTML::_('select.option', $item->id, $text);
        }
        return $parentArr;
    }



    public static function getCategoryIdsOfProperty($pid)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('category_id')->from('#__osrs_property_categories')->where('pid="' . $pid . '"');
        $db->setQuery($query);
        $categoryIds = $db->loadColumn(0);
        return $categoryIds;
    }

    public static function getCategoryNamesOfProperty($pid)
    {
        global $lang_suffix, $mainframe;
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if ($mainframe->isAdmin()) {
            $lang_suffix = "";
        } else {
            $lang_suffix = OSPHelper::getFieldSuffix();
        }
        $user = JFactory::getUser();
        $permission = "";
        if (intval($user->id) > 0) {
            $special = HelperOspropertyCommon::checkSpecial();
            if ($special) {
                $permission .= " 1 = 1 and `access` in (0,1,2) ";
            } else {
                $permission .= " 1 = 1 and `access` in (0,1) ";
            }
        } else {
            $permission .= " 1 = 1  and `access` = '0' ";
        }
        $query = $db->getQuery(true);
        $query->select('category_name' . $lang_suffix)->from('#__osrs_categories')->where($permission . ' and id in (Select category_id from #__osrs_property_categories where pid ="' . $pid . '")');
        $db->setQuery($query);
        $categoryNames = $db->loadColumn(0);
        return implode(", ", $categoryNames);
    }

    public static function getCategoryNamesOfPropertyWithLinks($pid)
    {
        global $lang_suffix, $mainframe;
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $app = JFactory::getApplication();
        if ($app->isAdmin()) {
            $lang_suffix = "";
        } else {
            $lang_suffix = OSPHelper::getFieldSuffix();
        }
        $user = JFactory::getUser();
        $permission = "";
        if (intval($user->id) > 0) {
            $special = HelperOspropertyCommon::checkSpecial();
            if ($special) {
                $permission .= " 1 = 1 and `access` in (0,1,2) ";
            } else {
                $permission .= " 1 = 1 and `access` in (0,1) ";
            }
        } else {
            $permission .= " 1 = 1  and `access` = '0' ";
        }
        $query = $db->getQuery(true);
        $query->select('id, category_name' . $lang_suffix . ' as category_name')->from('#__osrs_categories')->where($permission . ' and id in (Select category_id from #__osrs_property_categories where pid ="' . $pid . '")');
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        $categoryArr = array();
        if (count($categories) > 0) {
            $needs = array();
            $needs[] = "category_listing";
            $needs[] = "lcategory";
            $itemid = OSPRoute::getItemid($needs);
            foreach ($categories as $category) {
                $id = $category->id;
                $category_name = $category->category_name;
                $link = JRoute::_('index.php?option=com_osproperty&task=category_details&id=' . $id . '&Itemid=' . $itemid);
                $categoryArr[] = "<a href='" . $link . "'>" . $category_name . "</a>";
            }
        }
        return implode(", ", $categoryArr);
    }

    public static function array_equal($a, $b)
    {
        return (is_array($a) && is_array($b) && array_diff($a, $b) === array_diff($b, $a));
    }

    public static function showBath($value)
    {
        return rtrim(rtrim($value,'0'),'.');
        return $value;
    }

    public static function showLotsize($value)
    {
        return rtrim(rtrim($value,'0'),'.');
        return $value;
    }

    public static function showSquare($value){

        return rtrim(rtrim($value,'0'),'.');
    }

    public static function checkView($taskArr, $menu_id)
    {
        //print_r($taskArr);
        //die();
        //$return = 0;
        //die();
        if ($menu_id > 0) {
            $db = JFactory::getDbo();
            $db->setQuery("Select * from #__menu where id = '$menu_id'");
            $menu = $db->loadObject();
            $menu_link = $menu->link;

            if (count($taskArr) > 0) {
                foreach ($taskArr as $task) {
                    if (strpos($menu_link, $task) !== false) {
                        $return = 1;
                    }
                }
            }
        }

        if ($return == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the correct image name
     *
     * @param unknown_type $image_name
     * @return unknown
     */
    public static function processImageName($image_name)
    {
        $image_name = str_replace(" ", "", $image_name);
        $image_name = str_replace("'", "", $image_name);
        $image_name = str_replace("\n", "", $image_name);
        $image_name = str_replace("\r", "", $image_name);
        $image_name = str_replace("\x00", "", $image_name);
        $image_name = str_replace("\x1a", "", $image_name);
        return $image_name;
    }

    public function checkPermissionOfCategories($catArr)
    {
        $returnArr = array();
        $user = JFactory::getUser();
        $permission = "";
        if (intval($user->id) > 0) {
            $special = HelperOspropertyCommon::checkSpecial();
            if ($special) {
                $permission .= " and `access` in (0,1,2) ";
            } else {
                $permission .= " and `access` in (0,1) ";
            }
        } else {
            $permission .= " and `access` = '0' ";
        }
        $db = JFactory::getDbo();
        if (count($catArr) > 0) {
            foreach ($catArr as $category_id) {
                if ($category_id > 0) {
                    $db->setQuery("Select count(id) from #__osrs_categories where id = '$category_id' $permission");
                    $count = $db->loadResult();
                    if ($count > 0) {
                        $returnArr[] = $category_id;
                    }
                }
            }
        }
        return $returnArr;
    }

    /**
     * Add property to Facebook when it is added/updated
     *
     * @param unknown_type $property
     */
    public function postPropertyToFacebook($property, $isNew)
    {
        $configClass = self::loadConfig();
        if (($configClass['add_fb'] == 1) and ($configClass['facebook_api'] != "") and ($configClass['application_secret'] != "")) {
            require JPATH_ROOT . '/components/com_osproperty/helpers/fb/facebook.php';
            $facebook = new Facebook(array('appId' => $configClass['facebook_api'], 'secret' => $configClass['application_secret'], 'cookie' => true));

            $url = JRoute::_("index.php?option=com_osproperty&task=property_details&id=$property->id");
            $url = JUri::getInstance()->toString(array('scheme', 'user', 'pass', 'host')) . $url;

            switch ($isNew) {
                case 1:
                    $message = JText::_('OS_FBLISTING_FB_NEW_TEXT');
                    break;
                default:
                    $message = JText::_('OS_FBLISTING_FB_UPDATE_TEXT');
                    break;
            }
            $message .= '@ ' . $url;

            //find thumb
            $db = JFactory::getDbo();
            $db->setQuery("Select * from #__osrs_photos where pro_id = '$property->id'");
            $photos = $db->loadObjectList();
            if (count($photos) > 0) {
                $photo = $photos[0];
                $image = $photo->image;
                if (file_exists(JPATH_ROOT . 'images/osproperty/properties/' . $property->id . '/thumb/' . $image)) {
                    $picture = JURI::root() . 'images/osproperty/properties/' . $property->id . '/thumb/' . $image;
                } else {
                    $picture = JUri::root() . 'components/com_osproperty/images/assets/nopropertyphoto.png';
                }
            } else {
                $picture = JUri::root() . 'components/com_osproperty/images/assets/nopropertyphoto.png';
            }
            $fbpost = array(
                'message' => $message,
                'name' => $property->sef . ", " . self::getLanguageFieldValue($property, 'pro_name'),
                'caption' => JText::_('OS_FBLISTING_LINK_CAPTION'),
                'link' => $url,
                'picture' => $picture
            );

            $result = $facebook->api('/me/feed/', 'post', $fbpost);

            return true;
        }
    }

    public static function addPropertyToQueue($id)
    {
        $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__osrs_new_properties where pid = '$id'");
        $count = $db->loadResult();
        if($count == 0) {
            $db->setQuery("Insert into #__osrs_new_properties (id,pid) values (NULL,'$id')");
            $db->query();
        }
    }

    /**
     * This function is used to show the location links above the Google map
     * @param $address
     * @return array|string
     */
    public static function showLocationAboveGoogle($address){
        $language = JFactory::getLanguage();
        $activate_language = $language->getTag();
        $activate_language = explode("-",$activate_language);
        $activate_language = $activate_language[0];
        ?>
        <div class="row-fluid" style="margin-top:10px;">
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_SCHOOLS');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_SCHOOLS');?> </a>		</div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_RESTAURANTS');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_RESTAURANTS');?> </a>		</div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_DOCTORS');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_DOCTORS');?> </a>		</div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_HOSPITALS');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_HOSPITALS');?> </a>		</div>
        </div>
        <div class="row-fluid">
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_RAILWAY');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_RAILWAY');?> </a>
            </div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_AIRPORTS');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_AIRPORTS');?> </a>
            </div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_SUPER_MARKET');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_SUPER_MARKET');?> </a>
            </div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_THEATRE');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_THEATRE');?> </a>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_UNIVERSITIES');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_UNIVERSITIES');?> </a>
            </div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_PARKS');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_PARKS');?> </a>
            </div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_KINDERGARTEN');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_KINDERGARTEN');?> </a>
            </div>
            <div class="span3" style="margin-left:10px;">
                <i class="osicon-search"></i> <a href="http://local.google.com/local?f=l&amp;hl=<?php echo $activate_language;?>&amp;q=category:+<?php echo JText::_('OS_SHOPPING_MALL');?>&amp;om=1&amp;near=<?php echo $address;?>" class="category" rel="nofollow"><?php echo JText::_('OS_SHOPPING_MALL');?> </a>
            </div>
        </div>
        <?php
    }

    public static function isSoldProperty($row,$configClass){
        $sold_property_types = $configClass['sold_property_types'];
        if($sold_property_types != ""){
            $sold_property_types = explode("|",$sold_property_types);
            if((in_array($row->pro_type,$sold_property_types)) and ($row->isSold == 1) and ($configClass['use_sold'] == 1)){
                return true;
            }
        }
        return false;
    }
}
?>