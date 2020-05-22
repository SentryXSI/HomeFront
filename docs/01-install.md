
### Installation

Download the latest release and extract the files to a directory.   

Or, clone the repo :  

    git clone sentryxsi/homefront 

### Post Install

1 : Choose a hostname eg ( homefront.mint )  

2 : Set up a virtual host for httpd.    

3 : Add homefront.mint domain to your hosts file.    

    192.168.198.32 homefront.mint www.homefront.mint homefront  

4 : Set http://homefront.mint to be your default start page in your browser.  

### Offline

To work offline ( without an active internet connection ), 
use a localhost ip for your domain. 

Eg: I prefer to use the IPv4 localhost :  

    127.0.0.1 homefront.mint www.homefront.mint homefront

Or use IPv6 :  

    ::1  homefront.mint www.homefront.mint homefront
    
