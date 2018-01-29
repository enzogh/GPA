# GPA
GarryHost Plugins Autoloader

edit : `$config['cfg']['dir'] (Plugins Folder)`

# Plugins Config Example : 
`/your_directory_plugins/plugins_name/GPA-autoloader.php (Config file name don't use other name)`

(Inside)
```
$GPA_PLUGINS['name']        = 'YourPluginsName'; // Plugins Name
$GPA_PLUGINS['role']        = 'classes'; // (classes or execute)
$GPA_PLUGINS['location']    = 'functions/'; // the folder that contains your classes (inside the plugins name folder)
$GPA_PLUGINS['version']     = '1.0'; // (Just For You)
$GPA_PLUGINS['status']      = 'enabled'; // (enabled or disabled)
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
