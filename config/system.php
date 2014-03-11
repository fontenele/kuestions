<?php

return array(
    'system' => array(
        'module' => array(
            'defaults' => array(
                'controller' => 'Kuestions\Controller\Main',
                'action' => 'index',
                'controllerAuth' => 'Kuestions\Controller\Main',
                'actionAuth' => 'login'
            )
        ),
        'view' => array(
            'layout' => 'layout.phtml',
            'error404' => 'error404.phtml',
            'datagrid' => array(
                'rowsPerPage' => 15
            )
        )
    )
);
