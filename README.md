# Recsech - Web Reconnaissance Tools

Recsech is a tool for doing Footprinting and Reconnaissance on a target web.

[![undefined](https://img.shields.io/github/release/radenvodka/Recsech.svg)](https://github.com/radenvodka/Recsech/releases/latest)
[![undefined](https://img.shields.io/github/last-commit/radenvodka/Recsech.svg)](https://github.com/radenvodka)
[![undefined](https://img.shields.io/github/languages/top/radenvodka/Recsech.svg)](https://github.com/radenvodka)
[![undefined](https://img.shields.io/github/commits-since/radenvodka/Recsech/latest.svg)](https://github.com/radenvodka/Recsech/tags)



[![undefined](https://badgen.net/badge//Windows/blue?icon=windows)](https://github.com/radenvodka/Recsech/issues/3) [![undefined](https://badgen.net/badge//Linux64/orange?icon=terminal)](https://github.com/radenvodka/Recsech/releases)
[![Open Source Love svg2](https://badges.frapsoft.com/os/v2/open-source.svg?v=103)](https://github.com/ellerbrock/open-source-badges/)
[![undefined](https://img.shields.io/github/contributors/radenvodka/recsech.svg)](https://github.com/radenvodka/Recsech/graphs/contributors)

[![asciicast](https://asciinema.org/a/Yv71F5OKtz4Ubg0YZt3Copm7L.svg)](https://asciinema.org/a/Yv71F5OKtz4Ubg0YZt3Copm7L)


## Features in tools

| Name                      | Release            | Release Date |
|---------------------------|--------------------|--------------|
| Auto request with Proxy   | :white_check_mark: | 01/05/19     |
| Find Email                | :white_check_mark: | 01/05/19     |
| HoneySpot Detected        | :white_check_mark: | 01/05/19     |
| Subdomain takeover        | :white_check_mark: | 01/05/19     |
| Check Technologies        | :white_check_mark: | 01/05/19     |
| Whois                     | :x:                | N/A          |
| Crlf injection            | :x:                | N/A          |
| Header Security           | :white_check_mark: | 01/05/19     |
| Update Check              | :white_check_mark: | 01/05/19     |
| Port Scanner              | :white_check_mark: | 02/05/19     |
| Sort Domain By IP         | :white_check_mark: | 02/05/19     |
| Wordpress audit           | :x:                | N/A          |
| Reconnaissance On Github  | :white_check_mark: | 02/05/19     |
| Language Selection        | :white_check_mark: | 02/05/19     |


## Requirements for using this tool

We need several requirements to use this tool to run smoothly.

##### Linux
![PHP 7.X](https://img.shields.io/badge/PHP-7.X-success.svg "PHP 7.X")
![PHP CURL](https://img.shields.io/badge/PHP%20CURL-ALL-success.svg "PHP CURL")
##### Windows
![PHP CURL](https://img.shields.io/badge/XAMPP-7.3.5-success.svg "XAMPP 7.X")

## Installation 

You can download the latest tarball by clicking [here](https://github.com/radenvodka/Recsech/tarball/master) or latest zipball by clicking  [here](https://github.com/radenvodka/Recsech/zipball/master).

Preferably, you can download sqlmap by cloning the [Git](https://github.com/radenvodka/Recsech) repository:

    git clone --depth 1 https://github.com/radenvodka/Recsech.git Recsech


##### Recsech Environment Windows (Command Prompt Windows) 

Download Recsech : 

<a href="https://github.com/radenvodka/Recsech/tree/RecsechWIN" target="_blank"><img alt="undefined" src="https://badgen.net/badge//Windows/blue?icon=windows"></a>

How to install to Windows CLI : 

1. Extract all files in C: \Windows
2. Edit Files `Recsech.bat` , then set your PHP patch (if you have installed xampp on your C drive you don't need to do this step) 
```
@echo off
set PATH=%PATH%;C:\xampp\php
title Recsech - Recon and Research
php "C:\Windows\Recsech.php" %1
```
3. Open cmd and do the Recsech command.

![Recsech](https://raw.githubusercontent.com/radenvodka/Recsech/RecsechWIN/run.PNG)


Usage
----

Enough to execute the command :

    php Recsech.php example.com

or if it doesn't work, use the command : 
    
    php Recsech.php debug

and don't forget to ask at [issue page](https://github.com/radenvodka/Recsech/issues)


## Contribution

The following is a user guide that helps develop this tool : 

##### DEVELOPERS

[![Radenvodka!](https://img.shields.io/badge/Radenvodka-DEVELOPERS-blueviolet.svg)](https://github.com/radenvodka)
[![GitHub followers](https://img.shields.io/github/followers/radenvodka.svg?style=social&label=Follow&maxAge=2592000)](https://github.com/radenvodka?tab=followers)


##### PUBLICATION

[![linuxsec!](https://img.shields.io/badge/LinuxSec-PUBLICATION%20MEDIA-RED.svg)](https://github.com/linuxsec)
[![GitHub followers](https://img.shields.io/github/followers/linuxsec.svg?style=social&label=Follow&maxAge=2592000)](https://github.com/linuxsec?tab=followers)


##### USER CONTRIBUTION - Thank You

[![noraj!](https://img.shields.io/badge/Noraj-CONTRIBUTION-blue.svg)](https://github.com/Noraj)
[![GitHub followers](https://img.shields.io/github/followers/Noraj.svg?style=social&label=Follow&maxAge=2592000)](https://github.com/Noraj?tab=followers)



    Help us to develop this tool, as a sign that you have contributed we will put your name in the contribution

## Thanks

Thank you for all.  How to support ?

1. You can follow people who help contribute as a sign of gratitude.
2. Publish or review on your blog. 

If you have additional information, you can make it on the [issue page](https://github.com/radenvodka/Recsech/issues).

## Donation 

    If you want to buy my coffee, you can send payments via BTC and Paypal.

[![Bitcoin](https://img.balancebadge.io/btc/14MjRX4476hh8gwFNCj6GCAsSQuj42qUVf.svg)](https://www.blockchain.com/btc/address/14MjRX4476hh8gwFNCj6GCAsSQuj42qUVf)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/radenvodka)

## Disclaimer

This is an open source for everyone, you may redistribute, modify, use patents and use privately without any obligation to redistribute. but it should be noted to include the source code of the library that was modified (not the source code of the entire program), include the license, include the original copyright of the author (radenvodka), and include any changes made (if modified). Users do not have the right to sue the creator when there is damage to the software or even demand if there is a problem caused by the makers of this tool. because every risk is caused by the user risk itself.


License : [![GPLv3 license](https://img.shields.io/badge/License-GPLv3-blue.svg)](http://perso.crans.org/besson/LICENSE.html)

***GPL v3.0 specifically designed to allow users to use software distributed through networks such as websites and online services***
