<?php return array (
  'providers' => 
  array (
    0 => 'Modular\\ConnectorDependencies\\Illuminate\\Filesystem\\FilesystemServiceProvider',
    1 => 'Modular\\ConnectorDependencies\\Illuminate\\Database\\DatabaseServiceProvider',
    2 => 'Modular\\ConnectorDependencies\\Illuminate\\Validation\\ValidationServiceProvider',
    3 => 'Modular\\ConnectorDependencies\\Ares\\Framework\\Setup\\RegisterWordpressAsPluginProvider',
    4 => 'Modular\\Connector\\Providers\\ManagerServiceProvider',
    5 => 'Modular\\Connector\\Providers\\EventServiceProvider',
  ),
  'eager' => 
  array (
    0 => 'Modular\\ConnectorDependencies\\Illuminate\\Filesystem\\FilesystemServiceProvider',
    1 => 'Modular\\ConnectorDependencies\\Illuminate\\Database\\DatabaseServiceProvider',
    2 => 'Modular\\ConnectorDependencies\\Ares\\Framework\\Setup\\RegisterWordpressAsPluginProvider',
    3 => 'Modular\\Connector\\Providers\\ManagerServiceProvider',
    4 => 'Modular\\Connector\\Providers\\EventServiceProvider',
  ),
  'deferred' => 
  array (
    'validator' => 'Modular\\ConnectorDependencies\\Illuminate\\Validation\\ValidationServiceProvider',
    'validation.presence' => 'Modular\\ConnectorDependencies\\Illuminate\\Validation\\ValidationServiceProvider',
  ),
  'when' => 
  array (
    'Modular\\ConnectorDependencies\\Illuminate\\Validation\\ValidationServiceProvider' => 
    array (
    ),
  ),
);