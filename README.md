# Wufoo HTML Wrapper

The Wufoo HTML wrapper is a PHP class that outputs Wufoo fields using their API. There is also a built in [Bootstrap](http://getbootstrap.com/) option to use those classes and styles.

The class returns errors on incorrect submissions, shows required fields and even the instruction text. It also supports multiple forms on one page. 

You can view a list of fields supported by the class below. This is still a work in progress, so pull requests are massively loved (make sure to use the `dev` branch!)

Having trouble? Get me on Twitter [@OwenTheTwit](https://twitter.com/owenthetwit).

## Installation

Either install this using composer or download and include the file in your project.

Don't forget to also create an account on [Wufoo](https://wufoo.com).

### Composer

`composer require owenr88/wufoo-html-wrapper`

### Manually

`include_once 'WufooHTMLWrapper.php';`

## Using the class

There are a few things required before using the class:

1. [Get your API Key](http://help.wufoo.com/articles/en_US/SurveyMonkeyArticleType/Wufoo-REST-API-V3#Findingthekey)
2. Get your account subdomain - i.e. https://**biglemon**.wufoo.com

**The example.php file in the project root is a great place to start and covers all required functions and options.**

### Initialization

Initialize the class on your page to start off the wrapper

`$wrapper = new WufooHTMLWrapper();`

### Enable Bootstrap

`$wrapper->enableBootstrap();`

### Build a form

This function accepts a form ID OR the form url slug. The ID can be found by clicking 'Share Form' in Wufoo and taking the ID from the URL (i.e. 'z172ip8e07gen9n'), or the slug can be taken directly from the URL (i.e. 'test-form'). 

**This function RETURNS the HTML. Make sure to echo it to the page.**

`$wrapper->buildForm( 'z172ip8e07gen9n' );`

or

`$wrapper->buildForm( 'test-form' );`

### Submit form data

`$wrapper->sendSubmission();`

## Current Fields Supported

### Standard

* Single Line Text
* Paragraph Text
* Multiple Choice
* Number
* Checkboxes
* Dropdown

### Fancy Pants

* Email

**Other formats MIGHT be supported, but they just use their type (i.e. number, email, etc) as the input field's type attribute**

## Changelog

### 1.0.0
* The very first version! Includes the class, an example and all reqired documentation.
