# laravel-modules
Modules system for Laravel applications

## Table of Contents
* [Installation](#Installation)  
* [Usage](#Usage)  
* [Contributing](#Contributing)  
* [Credits](#Credits)  
* [License](#License)  
* [TODO list](#TODO-list)  

## Installation
Clone (or download) this repository  
```
git clone https://github.com/reenekt/laravel-modules.git
```

Then install composer dependencies
```
composer install
```

## Usage
### Basic usage
Run following command from your Laravel application folder
```
php artisan module:generate <module_name>
```
`module_name` - Name of module

This command will create module folder with default structure in modules folder (default: `app/Modules`)

## Contributing
See [CONTRIBUTING](CONTRIBUTING.md) file for more information

## Credits
Author: [reenekt](https://github.com/reenekt)

## License
See [LICENSE](LICENSE) file for more information

## TODO list
* [ ] Set up travis ci
* [ ] Publish on packagist
* [ ] Make this repository better (add wiki, make github page, add issue templates and other)
