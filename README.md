# GPA
GarryHost Plugins Autoloader

edit : `$config['cfg']['dir'] (Plugins Folder)`

# Plugins Config Example : 
`/your_directory_plugins/plugins_name/GPA-autoloader.php (Default Name)`

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
