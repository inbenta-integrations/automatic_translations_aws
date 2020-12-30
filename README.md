# KM AUTOMATIC TRANSLATION
 
### TABLE OF CONTENTS
* [Introduction](#introduction)
* [How does it work?](#how-does-it-work)
* [AWS Account](#aws-account)
    * [Identity and Access Management (IAM)](#identity-and-access-management-iam)
    * [Translation Metrics](#translation-metrics)
* [Prepare your Inbenta Instances](#prepare-your-inbenta-instances)
* [Dependencies](#dependencies)
 
### Introduction
Inbenta supports [42 languages](https://www.inbenta.com/en/languages-supported/#). Multi-language implementations are very popular among our customers. One of the challenges of multi-language projects is to create and maintain contents across multiple languages. To help our customers with this problem, we have created an application to automatically translate (We have used [Amazon Translate](https://aws.amazon.com/translate/). You could choose your choice of the translation service) and create contents across multiple language instances when you create one in your base language.
 
### How does it work?
This auto-translate application uses translation services such as Amazon Translate to create the translations. 

A webhook is created to use the Amazon Translate for translation. Multi-language instances are linked together in the settings of the base instance where the content will be created or edited. When content is saved (both new content & edit content) in the base knowledge instance, this Webhook gets triggered and that sends the content to Amazon Translate and creates respective content in other language instances based on the translated text received from Amazon. 

The following is the dynamic settings considered for translation.
* Title
* Answer text
* Alternative titles (if exists)
* Answer text for every user type (as long as the user types ID’s exist in target instance)

In addition to the translation of above-mentioned content attributes, this application also considers the data entered in other content attributes of the original content and saves them as is in all the language instances. 

Here is the list of additional content attributes considered:
* Publication date
* Expiration date
* Status
* Use for popular
* Related content
* Categories (as long as the ID exist in the target instance)

 
### AWS Account
The first thing required is an AWS Account. If you do not have an account yet, it can be created [here](https://portal.aws.amazon.com/billing/signup#/start). If you already have an account, sign in.

#### Identity and Access Management (IAM)

The next step is to get the Access Key ID and Secret Access Key. In order to do that, you need to create a user and assign it the proper permissions.

* In the [“IAM” main screen](https://console.aws.amazon.com/iam/), click on **Access management → Users**.
* Click on **Add user**.
* Enter a **user name** and select **Programmatic access**, for the **Access type**. This is used to enable the access key ID and secret access key.
* Select an existing user group or add a new one.
* If you are creating a group, click on **Create group** (or edit option as well).
* Select the policies **TranslateFullAccess** and **TranslateReadOnly**.
* Add tags to help organize the users.
* Once the user is created you can see the **Access key ID** and **Secret access key**.

#### Translation Metrics
Once everything is properly configured, you can see the metrics, on the [Amazon Translation Service](https://console.aws.amazon.com/translate/home) screen. [Here](https://docs.aws.amazon.com/translate/latest/dg/what-is-limits.html) are the limits to keep in mind.

### Prepare your Inbenta Instances

Reach out to your Inbenta Point of Contact to enable the following for you:

* **Multilanguage Related Instances** settings.
* Webhook that you create for the translation service. In your main instance go to **Settings -> Static -> On save content webhook**, (this is the ```KM_HEADER_KEY```, needed in the .env file). You will share the following details:

```env
    URL: https://automatic_translations_url.com
    Header Name: Apply-Translation
    Header Value: secretKey|en
```
>NOTE: “Header Value” is a string defined by customer, but is mandatory to add at the end a pipe character ( | ) and the language of the primary instance. Example “xxxxx|en”, “xxxxx|es”, “xxxxx|fr”, etc. For this value avoid the use of #. 
The “Header Name” must be “Apply-Translation”


* In the .env file, the following configuration needs to be added:
    * Auth URL for the KM API, given by Inbenta.
    * Header key value (previous step).
    * A "User Personal Secret Token" (```KM_UPST```). [Help Center Instructions](https://help.inbenta.com/en/general/administration/managing-credentials-for-developers/managing-your-ups-tokens/).
    * Api Key and Secret for every instance (origin and target instances).
    * AWS information (key, secret, region, version)

* Set all values in the **.env** file:
```env
AUTH_URL = 
KM_HEADER_KEY =
KM_UPST = 
KM_LANG_LIST = ES,EN
KM_API_KEY_ES = 
KM_SECRET_ES = 
KM_API_KEY_EN = 
KM_SECRET_EN = 
AWS_KEY = 
AWS_SECRET = 
AWS_REGION = #Example: us-west-2
AWS_VERSION = #Example: 2017-07-01
```
 
### Dependencies
This application needs 2 dependencies  `vlucas/phpdotenv` and `aws/aws-sdk-php` as a Composer dependency.
