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
