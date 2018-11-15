Humhub Module: Performance Insights
========================================================

## Requirements

- PHP 5.5 or higher
- HumHub 1.2.2 or higher

## Installation

To install the module, extract the content of the .zip file into the HumHub
module folder: `humhub/protected/modules/performance_insights`.

The module then shows up in the Adminitration menu of HumHub where it
can be installed by clicking the "Enable" button.

Add following code in composer.json file

        "require": {
            "jonnyw/php-phantomjs": "4.*"
        },
        "config": {
            "bin-dir": "bin"
        },
        "scripts": {
            "post-install-cmd": [
                "PhantomInstaller\\Installer::installPhantomJS"
            ],
            "post-update-cmd": [
                "PhantomInstaller\\Installer::installPhantomJS"
            ]
        }
Finally, update composer

## Features

- Generate fake data for testing - Spaces and Users
- Analyze Page Performance
- Analyze Humhub Directory Space Search Performance
- Analyze Humhub Directory Member Search Performance

Author : Victor Diez  

Status : Beta
