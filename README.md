# parsianPay
Easily integrate PHP application with parsian bank payment.

# Installation
``` bash
$ composer require esijafari2012/parsianPay
```

# Example Usage
# Example For Pay

``` bash
use Esijafari2012\ParsianPay\Pay;
use Esijafari2012\ParsianPay\Entities\PeyResult;

$pIPG=new Pay('scsdsdfbdsthsgfnfgndg');//set parsian pin
$OrderId = time() . rand(000,999); // factor number
$Amount  = 1000; // amount to pay
$CallbackUrl='http://example.ir/callback' ; // set callback url
$pr=$pIPG->payment($OrderId,$Amount,$CallbackUrl);
if($pr instanceof PeyResult){
    if(($pr->getStatus()==0)&&($pr->getToken()>0)){
        $pIPG->redirect();
    }
}
```

# Example For Callback
``` bash
use Esijafari2012\ParsianPay\Callback;
  
$pIPG=new Callback('scsdsdfbdsthsgfnfgndg');//set parsian pin
$getCallback = $pIPG->confirm();
 
if($getCallback['Status'] == 0){
    die(' Payment OK ');
}

echo $getCallback['Message'];
```


# Example For reverse
``` bash
use Esijafari2012\ParsianPay\Reverse;

$pIPG=new Reverse('scsdsdfbdsthsgfnfgndg');//set parsian pin
$getCallback = $pIPG->reverse(12545485);//reverse token payment
 
if($getCallback['Status'] == 0){
    die(' Reverse Payment OK ');
}

echo $getCallback['Message'];
```
