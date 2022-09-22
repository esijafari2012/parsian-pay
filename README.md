# parsianPay
Easily integrate PHP application with parsian bank payment.

# Installation
``` bash
$ composer require esijafari2012/parsianPay
```

# Example Usage
# Example For Pay

``` bash
use Esijafari2012\ParsianPay\ParsianIPG;

$pIPG=new ParsianIPG('scsdsdfbdsthsgfnfgndg');//set parsian pin
$OrderId = time() . rand(000,999); // factor number
$Amount  = 1000; // amount to pay
$CallbackUrl='http://example.ir/callback' ; // set callback url
$pIPG->startPayment($OrderId,$Amount,$CallbackUrl);
```

# Example For Callback
``` bash
  
$pIPG=new ParsianIPG('scsdsdfbdsthsgfnfgndg');//set parsian pin
$getCallback = $pIPG->callback();
 
if($getCallback['Status'] == 0){
    die(' Payment OK ');
}

echo $getCallback['Message'];
```


# Example For reverse
``` bash
  
$pIPG=new ParsianIPG('scsdsdfbdsthsgfnfgndg');//set parsian pin
$getCallback = $pIPG->reverse(12545485);//reverse token payment
 
if($getCallback['Status'] == 0){
    die(' Reverse Payment OK ');
}

echo $getCallback['Message'];
```
