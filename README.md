# T2i Test

Application that displays the alarm (person)

## Setup

You need to have the mysql server, then you need to apply the following migration

```bash
migration/init.sql
```

Once it is done, you need to configure the database connection inside the following file

```bash
config/Database.php
```

Where you need to specify the database host, db name, username and password

Now we need to have the php5.6 on your linux machine, you need to run the following commands
```bash
sudo apt update
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php5.6
sudo apt install php5.6-common php5.6-cli php5.6-fpm
sudo apt install php5.6-mysql php5.6-gd php5.6-json php5.6-curl php5.6-zip php5.6-mbstring php5.6-xml php5.6-bcmath
```

To verify that everything is working fine, you can run the following command
```bash
php5.6 -v
```

## Run

To start the project you need to run the following command
```bash
php5.6 -S localhost:9090 -t public 
```