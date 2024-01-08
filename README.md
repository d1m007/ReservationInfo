# GLPi Reservation Info Plugin

Download the plugin here : https://github.com/d1m007/ReservationInfo/raw/main/misc/ReservationInfo.zip

## Introduction

The Reservation Info plugin show current reservation status in lists of items. It's useful to see which items are already booked at first sight.

## Screenshots

![Screenshot](./misc/ReservationInfo_Config.png)
![Screenshot](./misc/ReservationInfo_NetworkDevices.png)

## License

![license](./misc/GPLv3.0.svg)

It is distributed under the GNU GENERAL PUBLIC LICENSE Version 3 - please consult the file called [LICENSE](https://raw.githubusercontent.com/d1m007/ReservationInfo/main/LICENSE) for more details.

## Documentation

This plugin works with all types of bookable items: computers, monitors, software, network devices, devices, printers and phones.

It's compatible with GLPI 10.0.0 => 10.0.11. Translations available in en_EN, fr_FR, es_ES. It's up to you to add more.
The plugin database table is removed when uninstalling.

## Installation

To install the plugin, unzip files in the following directory:
'glpi/plugins/ReservationInfo/'
then install/enable it from the Setup/plugin panel.

Or use git:

```sh
cd /my/glpi/deployment/main/directory/plugins
git clone https://github.com/d1m007/ReservationInfo.git
```
