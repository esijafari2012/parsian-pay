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
$pay->createLogger();// create logger is optional
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
  
$callback=new Callback('scsdsdfbdsthsgfnfgndg');//set parsian pin
$callback->createLogger();// create logger is optional
$confirmResult = $callback->confirm();
if($confirmResult$confirmResult instanceof ConfirmResult){
    if($confirmResult->getStatus() == 0){
        die(' Payment OK ');
    }
    echo $confirmResult->getMessage();
}

```


# Example For reverse
``` bash
use Esijafari2012\ParsianPay\Reverse;
use Esijafari2012\ParsianPay\Entities\PeyResult;

$reverse=new Reverse('scsdsdfbdsthsgfnfgndg');//set parsian pin
$reverse->createLogger();// create logger is optional
$payResult = $reverse->reverse(12545485);//reverse token payment
 
if($payResult instanceof PayResult){
    if($payResult->getStatus()==0){
        die(' Reverse Payment OK ');
    }
}

echo $payResult->getMessage();
```
