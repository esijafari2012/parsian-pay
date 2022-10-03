# parsianPay
Easily integrate PHP application with parsian bank payment.

# Installation
``` bash
$ composer require esijafari2012/parsian-pay
```

# Example Usage
# Example For Pay

``` bash
use Esijafari2012\ParsianPay\Pay;
use Esijafari2012\ParsianPay\Entities\PayResult;

$pay=new Pay('scsdsdfbdsthsgfnfgndg');//set parsian pin
$OrderId = (float)(time() . rand(000,999)); // factor number
$Amount  = 1000; // amount to pay
$CallbackUrl='http://example.ir/callback' ; // set callback url
$pr=$pay->payment($OrderId,$Amount,$CallbackUrl);
if($payResult instanceof PayResult){
    if(($payResult->getStatus()==0)&&($payResult->getToken()>0)){
        $pay->redirect();// redirect to parsian bank gateway  for payment  
    }
}
```

# Example For Callback
``` bash
use Esijafari2012\ParsianPay\Callback;
use Esijafari2012\ParsianPay\Entities\ConfirmResult;
  
$pIPG=new Callback('scsdsdfbdsthsgfnfgndg');//set parsian pin
$cr = $pIPG->confirm();
if($cr instanceof ConfirmResult){
    if($cr->getStatus() == 0){
        die(' Payment OK ');
    }
}
echo $cr->getMessage();
```


# Example For reverse
``` bash
use Esijafari2012\ParsianPay\Reverse;
use Esijafari2012\ParsianPay\Entities\PeyResult;

$pIPG=new Reverse('scsdsdfbdsthsgfnfgndg');//set parsian pin
$pr = $pIPG->reverse(12545485);//reverse token payment
 
if($pr instanceof PeyResult){
    if($pr->getStatus()==0){
        die(' Reverse Payment OK ');
    }
}

echo $pr->getMessage();
```
