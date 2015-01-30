<?php
namespace GbiliFileEntityModule;
return array(
    'doctrine' => include __DIR__ . '/doctrine.config.php',
    'doctrine_event_listeners' => include __DIR__ . '/doctrine_event_listeners.config.php',
    'service_manager' => include __DIR__ . '/service_manager.config.php',
);
