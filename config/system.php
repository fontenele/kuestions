<?php

return array(
    'system' => array(
        'module' => array(
            'defaults' => array(
                'controller' => 'Kuestions\Controller\Main',
                'action' => 'index'
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