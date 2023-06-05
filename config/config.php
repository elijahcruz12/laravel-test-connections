<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /*
     * The default group of connections to use
     */
    'default' => 'main',

    /*
     * Connections can have a group, so you can use a flag to choose which connections you want to test.
     */
    'connections' => [
        'main' => ['database', 'redis', 'cache'],
    ],

    /*
     * Log to your logs if any connection fails
     */
    'log' => true,
];