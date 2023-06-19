# worldMap
Downloadable online world map

## How does it look like inside?

## Installation
### 1. Download
Run the following command in your server (in the directory where you want to install the map):
```bash
git clone https://www.github.com/Kaki-In/worldMap
```

### 2. Set up the SQL environment
Import the `init.sql` file in your own database. 

## Configuration

To configure the map, open the `conf.php` project and modify the different values written:
 - `database-name` : the name of your database (default `worldMap`)
 - `database-server` : the server that hosts your database (default `localhost`)
 - `database-user` : the username of your database (default `root`)
 - `database-password` : the password of your database

## Results

If your `conf.php` file is well setted up, you'll be able to use points on your map. Else, an error will be displayed instead.

