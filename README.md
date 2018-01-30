# GPA
GarryHost Plugins Autoloader

Default Config :
```
(You can edit this inside GPA-Class.php)
$config['cfg']['dir'] (Plugins Folder)
$config['cfg']['dir_condenser'] (Condenser Directory : this allows to condense your code in 1 php file works only with php files)
```

# Plugins Config Example : 
`/your_directory_plugins/plugins_name/GPA-autoloader.php (Config file name don't use other name)`

(Inside)
```
$GPA_PLUGINS['name']        = 'YourPluginsName'; // Plugins Name
$GPA_PLUGINS['role']        = 'classes'; // (classes or execute)
$GPA_PLUGINS['location']    = 'functions/'; // the folder that contains your classes (inside the plugins name folder)
$GPA_PLUGINS['version']     = '1.0'; // (Just For You)
$GPA_PLUGINS['status']      = 'enabled'; // (enabled or disabled)

$GPA_PLUGINS['condenser']   = 'enabled'; // (enabled or disabled) this allows to condense your code in 1 php file (works only with php files)
```
  
```
├── PluginsFolder/
│   ├── PluginsName/
│   │   ├── GPA-autoloader.php (Config File)
│   │   └── Function/ (Plugins Class Folder)
│   └── PluginsName2/
│       ├── GPA-autoloader.php (Config File)
│       └── Function/ (Plugins Class Folder)
```

# Include GPA on your website
```
include('GPA-Class.php')

// If you have a bug you can debug with this code
$config['PLUGINS_LOADED'] // Plugins loaded with this script
$config['PLUGINS_DISABLED'] // Plugins disabled ($GPA_PLUGINS['status'] inside GPA-autoloader.php)
$config['PLUGINS_ERROR'] // Plugins Error result example : 'file_directory' => 'MyPlugins1', 'message' => 'GPA not found'

use print_r() for display these variables
```
# Condenser PHP 
```
this allows to condense your code in 1 php file (works only with php files)
after having correctly configure the script you can refresh the page this will generate 1 Php file which contains all the codes that are enabled in the plugins

to have the file condense :
your_directory_condenser/GPA-condenser.php

to finish creating a plugin with the GPA-condenser.php then disable all other plugins
```
