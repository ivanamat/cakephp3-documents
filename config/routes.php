<?php

/**
 * CakePHP 3.x - Documents
 * 
 * PHP version 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @category CakePHP3
 * 
 * @author   Ivan Amat <dev@ivanamat.es>
 * @copyright     Copyright 2016, Iván Amat
 * @license  MIT http://opensource.org/licenses/MIT
 * @link     https://github.com/ivanamat/cakephp3-documents
 * 
 */

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin(
    'Documents',
    ['path' => '/Documents'],
    function (RouteBuilder $routes) {

        $routes->connect('/', ['controller' => 'Documents', 'action' => 'index']);
        
        $routes->connect(
            '/:userShortcut',
            ['plugin' => 'Documents','controller' => 'Categories'],
            ['userShortcut' => '(?i:categories)']
        );

        $routes->connect(
            '/:userShortcut/:action',
            ['plugin' => 'Documents','controller' => 'Categories', 'action' => ':action'],
            ['pass' => array('action'),
            'userShortcut' => '(?i:categories)']
        );
        
        $routes->connect(
            '/:userShortcut/:action/:id',
            ['plugin' => 'Documents','controller' => 'Categories', 'action' => ':action',':id'],
            ['pass' => array('id','action'),
            'userShortcut' => '(?i:categories)']
        );
        
//        $routes->connect(
//            '/:userShortcut',
//            ['plugin' => 'Documents','controller' => 'Documents'],
//            ['userShortcut' => '(?i:documents)']
//        );

        $routes->connect(
            '/:userShortcut',
            ['plugin' => 'Documents','controller' => 'Documents', 'action' => 'add'],
            [
                'userShortcut' => '(?i:add)',
            ]
        );

        $routes->connect(
            '/:userShortcut/:categoryId',
            ['plugin' => 'Documents','controller' => 'Documents', 'action' => 'add',':categoryId'],
            [
                'userShortcut' => '(?i:add)',
                'categoryId' => '\d+', 
                'pass' => ['categoryId']
            ]
        );

        $routes->connect(
            '/:userShortcut/:id',
            ['plugin' => 'Documents','controller' => 'Documents', 'action' => 'edit',':id'],
            [
                'pass' => array('id'),
                'userShortcut' => '(?i:edit)',
            ]
        );

        $routes->connect(
            '/:userShortcut/:id',
            ['plugin' => 'Documents','controller' => 'Documents', 'action' => 'delete',':id'],
            [
                'pass' => array('id'),
                'userShortcut' => '(?i:delete)',
            ]
        );

        $routes->connect('/:slug:lastslash',array('plugin' => 'Documents','controller' => 'Documents', 'action' => 'view',),array(
            'pass'=>array('slug'),
            'slug'=>'.*?',
            'lastslash'=>'\/?',
            'userShortcut' => '(?i:documents)'
        ));
        
        $routes->fallbacks('DashedRoute');
    }
);