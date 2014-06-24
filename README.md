Important
---------

This mirco-framework uses a number of components such as [Twig] for templating, [Less] for css pre-processing
amongst others that will be mentioned in the Tech Stack section. 
Therefore, additional steps are need to fully setup this mrico-framework.
Such steps are highlighted in the Step Instruction step

Setup Instructions
------------------
------------------

Installation
------------

After downloading the source files, you will need to run an additional git commands
inorder for this application to use the external modules such as [Twig] and [Less] 

In the root folder of the application, run the following git commands:

    $ git submodule update --init
    
This will perform a git clone on external modules that are needed.

Database Configuration
----------------------

In the etc/configs folder there is a settings.ini.php file which contains basic server configuration.
There are four variables that are needed to be adjustment for MySQL database configuration. 
Please follow the instruction given in the file


Tech Stack
----------

  - [Twig]
  - [Less][]
  - [Bootstrap][], [Font Awesome][], [Social Buttons][]
  - [jQuery][]
  - [OpenID][] sign in (Google, Facebook, Twitter)
  

Author of vecni
-------------------------
[Oshane Bailey]
![alt tag](https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xpa1/t1.0-1/c127.37.466.466/s148x148/76418_1578880746745_2542594_n.jpg)

  
[Oshane Bailey]: https://github.com/b4oshany
[Twig]: http://twig.sensiolabs.org/doc/installation.html
[bootstrap]: http://getbootstrap.com/
[font awesome]: http://fortawesome.github.com/Font-Awesome/
[jquery]: http://jquery.com/
[less]: http://lesscss.org/
[lesscss]: http://lesscss.org/
[openid]: http://en.wikipedia.org/wiki/OpenID
[social buttons]: http://lipis.github.io/bootstrap-social/
