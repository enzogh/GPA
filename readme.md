# GPA
GarryHost Plugins Autoloader

Default Config :
```
(You can edit this inside GPA-Class.php)
$config['CFG']['dir'] (Plugins Folder)
$config['CFG']['dir_condenser'] (Condenser Directory : this allows to condense your code in 1 php file works only with php files)
$config['CFG']['dir_secure_load'] (Secure Load Directory : scan your php files for found suspicious functions)
$config['CFG']['dir_encrypt_file'] (Encrypt File Directory : it allows to encrypt your plugins)
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
# Secure Load
```
scan your php files for found suspicious functions

Report file : 
your_directory_secureload/GPA-Secure-Result.txt

to make this system work it must set the variable on enabled ($config['CFG']['secure_load] = 'enabled')
```
# Encrypt File (Encrypt Your Code)
```
you can encrypt your plugins just change two variable ($config['CFG']['dir_encrypt_file'] = 'yourencrypt_directory' and $config['CFG']['encrypt_file'] = 'enabled' (or disabled))

after that it will generate a .gpa with all you plugins encrypt you have left your plugins with : $GPA_PLUGINS['status'] = 'disabled'; (Inside GPA-Autoloader.php)
it encrypts all your plugins that are activated so be careful before encryption activated your plugins

example of simple encryption : 
<GPA><GPA>d0a636c617373204d79636c6173737b0d0a0d0a202020207075626c69632066756e6374696f6e205f5f636f6e73747275637428297b0d0a20202020202020206563686f20276d79636c617373206c6f61646564273b0d0a202020207d0d0a0d0a202020207075626c69632066756e6374696f6e207465737428297b0d0a20202020202020206563686f20276f6b273b0d0a20202020202020206578656328276361636127293b0d0a2020202020202020706173737468727528277364736427293b0d0a202020207d0d0a7d<GPA><GPA>0d0a246465746573203d202763616361273b0d0a0d0a2474726f6c6c203d2027796f695e70273b<GPA><GPA>0d0a636c617373207365636f6e647b0d0a0d0a202020207075626c69632066756e6374696f6e20657a66647328297b0d0a20202020202020206563686f20276f6b273b0d0a202020207d0d0a0d0a7d

Final structure : 
├── encrypt/ (default name you can change it in the array) 
│   └── GPA-Encrypt.gpa (it's the file that contains your plugins)
```
