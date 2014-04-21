<?php

class Menu {

    private static $_controller;

    public static function getMenu($controller) {
        self::$_controller = $controller;
        $items = array(
            array('label' => '<i class="icon-dashboard"></i> Dashboard', 'url' => Yii::app()->homeUrl),
            array('label' => '<i class="icon-group"></i> Contactos', 'url' => array('/crm/contacto/admin'), 'access' => 'action_contacto_admin', 'active_rules' => array('module' => 'crm', 'controller' => 'contacto')),
            array('label' => '<i class="icon-fire-extinguisher"></i>Incidencias', 'url' => array('/incidencias/incidencia/admin'), 'access' => 'action_incidencia_admin', 'active_rules' => array('module' => 'incidencias', 'controller' => 'incidencia')),
            array('label' => '<i class="icon-calendar"></i> Calendario', 'url' => array('/eventos/calendario/index'), 'access' => 'action_calendario_index', 'active_rules' => array('module' => 'eventos')),
        );

        return self::generateMenu($items);
    }

    public static function getAdminMenu($controller) {
        self::$_controller = $controller;
        $items = array(
            array('label' => '<i class="icon-mail-reply"></i>  Regresar a la App', 'url' => Yii::app()->homeUrl),
            array('label' => '<i class="icon-user"></i>  Usuarios', 'url' => Yii::app()->user->ui->userManagementAdminUrl, 'access' => 'Cruge.ui.*', 'active_rules' => array('module' => 'cruge')),
            array('label' => '<i class="icon-upload-alt"></i>  Importar Archivo CSV', 'url' => array('/importcsv/'), 'access' => 'importar_archivo_csv', 'active_rules' => array('module' => 'importcsv')),
            array('label' => '<i class="icon-book"></i>  Catálogos', 'url' => '#', 'items' => array(
                    array('label' => 'Provincias', 'url' => array('/crm/provincia/admin'), 'access' => 'action_provincia_admin', 'active_rules' => array('module' => 'crm', 'controller' => 'provincia')),
                    array('label' => 'Ciudades', 'url' => array('/crm/ciudad/admin'), 'access' => 'action_ciudad_admin', 'active_rules' => array('module' => 'crm', 'controller' => 'ciudad')),
                    array('label' => 'Barrio', 'url' => array('/crm/barrio/admin'), 'access' => 'action_barrio_admin', 'active_rules' => array('module' => 'crm', 'controller' => 'barrio')),
                )),
            array('label' => '<i class="icon-fire-extinguisher"></i>  Incidencias', 'url' => '#', 'items' => array(
                    array('label' => 'Area', 'url' => array('/incidencias/incidenciaArea/admin'), 'access' => 'action_incidenciaArea_admin', 'active_rules' => array('module' => 'incidencias', 'controller' => 'incidenciaArea')),
                    array('label' => 'Tipo', 'url' => array('/incidencias/incidenciaTipo/admin'), 'access' => 'action_incidenciaTipo_admin', 'active_rules' => array('module' => 'incidencias', 'controller' => 'incidenciaTipo')),
                    array('label' => 'Categoria', 'url' => array('/incidencias/incidenciaCategoria/admin'), 'access' => 'action_incidenciaCategoria_admin', 'active_rules' => array('module' => 'incidencias', 'controller' => 'incidenciaCategoria')),
                    array('label' => 'Especialista', 'url' => array('/incidencias/incidenciaEspecialista/admin'), 'access' => 'action_incidenciaEspecialista_admin', 'active_rules' => array('module' => 'incidencias', 'controller' => 'incidenciaEspecialista')),
                    array('label' => 'Estado', 'url' => array('/incidencias/incidenciaEstado/admin'), 'access' => 'action_incidenciaEstado_admin', 'active_rules' => array('module' => 'incidencias', 'controller' => 'incidenciaEstado')),
                    array('label' => 'Prioridad', 'url' => array('/incidencias/incidenciaPrioridad/admin'), 'access' => 'action_incidenciaPrioridad_admin', 'active_rules' => array('module' => 'incidencias', 'controller' => 'incidenciaPrioridad')),
                )),
            array('label' => '<i class="icon-calendar"></i>  Eventos', 'url' => '#', 'items' => array(
                    array('label' => 'Prioridad', 'url' => array('/eventos/eventoPrioridad/admin'), 'access' => 'action_eventoPrioridad_admin', 'active_rules' => array('module' => 'eventos', 'controller' => 'eventoPrioridad')),
                 array('label' => 'Tipo', 'url' => array('/eventos/eventoTipo/admin'), 'access' => 'action_eventoTipo_admin', 'active_rules' => array('module' => 'eventos', 'controller' => 'eventoTipo')),
                )),
            array('label' => '<i class="icon-folder-open"></i>  Historiales', 'url' => '#', 'items' => array(
                )),
            array('label' => '<i class="icon-time"></i>  Actividades', 'url' => array('/actividades/actividad/admin'), 'access' => 'action_actividad_admin', 'active_rules' => array('module' => 'actividades')),
        );

        return self::generateMenu($items);
    }

    /**
     * Function to create a menu with acces rules and active item
     * @param array $items items to build the menu
     * @return array the formated menu
     */
    private static function generateMenu($items) {
        $menu = array();

        foreach ($items as $k => $item) {
            $access = false;
            $menu_item = $item;

            // Check children access
            if (isset($item['items'])) {
                $menu_item['items'] = array();
                // Check childrens access
                foreach ($item['items'] as $j => $children) {
                    if ($access = Yii::app()->user->checkAccess($children['access'])) {
                        $menu_item['items'][$j] = $children;
                        if (isset($children['active_rules']) && self::getActive($children['active_rules'])) {
                            $menu_item['items'][$j]['active'] = true;
                            $menu_item['active'] = true;
                        }
                    }
                }
            } else {
                // Check item access
                if (isset($item['access'])) {
                    $access = Yii::app()->user->checkAccess($item['access']);
                } else {
                    $access = true;
                }
                // Check active
                if (isset($item['active_rules'])) {
                    $menu_item['active'] = self::getActive($item['active_rules']);
                }
            }

            // If acces to the item or any child add to the menu
            if ($access) {
                $menu[] = $menu_item;
            }
        }

        return $menu;
    }

    /**
     * Function to compare the menu active rules with the current url
     * @param array $active_rules the array of rules to compare
     * @return boolean true if the rules match the current url
     */
    private static function getActive($active_rules) {
        $current = false;

        if (self::$_controller) {
            if (is_array(current($active_rules))) {
                foreach ($active_rules as $rule) {
                    $operator = isset($rule['operator']) ? $rule['operator'] : '==';

                    if (isset($rule['module']) && self::$_controller->module) {
                        if ($operator == "==")
                            $current = self::$_controller->module->id == $rule['module'];
                        if ($operator == "!=")
                            $current = self::$_controller->module->id != $rule['module'];
                    }
                    if (isset($rule['controller'])) {
                        if ($operator == "==")
                            $current = self::$_controller->id == $rule['controller'];
                        if ($operator == "!=")
                            $current = self::$_controller->id != $rule['controller'];
                    }
                    if (isset($rule['action'])) {
                        if ($operator == "==")
                            $current = self::$_controller->action->id == $rule['action'];
                        if ($operator == "!=")
                            $current = self::$_controller->action->id != $rule['action'];
                    }

                    if (!$current)
                        break;
                }
            } else {
                $operator = isset($active_rules['operator']) ? $active_rules['operator'] : '==';

                if (isset($active_rules['module']) && self::$_controller->module) {
                    if ($operator == "==")
                        $current = self::$_controller->module->id == $active_rules['module'];
                    if ($operator == "!=")
                        $current = self::$_controller->module->id != $active_rules['module'];
                }
                if (isset($active_rules['controller'])) {
                    if ($operator == "==")
                        $current = self::$_controller->id == $active_rules['controller'];
                    if ($operator == "!=")
                        $current = self::$_controller->id != $active_rules['controller'];
                }
                if (isset($active_rules['action'])) {
                    if ($operator == "==")
                        $current = self::$_controller->action->id == $active_rules['action'];
                    if ($operator == "!=")
                        $current = self::$_controller->action->id != $active_rules['action'];
                }
            }
        }
        return $current;
    }

}
