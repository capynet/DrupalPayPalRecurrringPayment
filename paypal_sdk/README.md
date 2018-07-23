# Drupal Paypal Recurring Payment


## Installation

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)



1)  Add dependency to composer.json file.
 ```
    "capynet/DrupalPayPalRecurrringPayment": {
        "type": "package",
        "package": {
            "name": "capynet/DrupalPayPalRecurrringPayment",
                "version": "dev-1.x",
                "type": "drupal-module",
                "source": {
                    "url": "https://github.com/capynet/DrupalPayPalRecurrringPayment.git",
                    "type": "git",
                    "reference": "master"
                }
        }
    }
 ```
2)  Once the module is installed DrupalPayPalRecurrringPayment dependency will need to be installed.

         cd paypal_sdk
         composer install
  
         
After execute **composer install** paypal/rest-api-sdk-php will be added to vendor folder.


### Notes

####Taxes
The tax is being hardcoded and it seems it is not being used. We still need to improve the code.

 ```
   $chargeModel->setType('TAX')
      ->setAmount(new Currency(array(
        'value' => $data['payment_amount'] * .21,
        'currency' => $data['payment_currency']
      )));
 ```
