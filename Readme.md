# Extension t3cs_sessions

This extension was created to provide a session plan for the TYPO3camp Stuttgart.
With the help of Responsive Guru [Sven Wolfermann](http://maddesigns.de) this extension shows the sessions by time and not
(like others) by room, so that you see all next sessions in a good overview even on a mobile phone.


## Features

### Version 1.0.0

* Responsive to show all sessions in a time order
* Set room name and sponsor (with logo)
* Star sessions you want to see (saved in LocalStorage)
* Past sessions won't be displayed

### Version 2.0.0

* **BREAKING CHANGE**: Changes table fields for time slots. Please be aware and read the [Update script](#update).
* Twitter notification integration. Create a cron job to send reminder for upcoming sessions sent by your Twitter account, which has to be [integrated](#twitter).

### Version 2.1.0

* Possibility to set the frontend plugin via Flexform (list sessions or list past sessions)
* Adds documentation

### Version 2.2.0

* Adds sorting for rooms
* Adds possibility to list sessions without breaks
* Adds composer.json

### Version 2.3.0

* [!!!] Use new page selector in flexform for record filter

## <a name="update"></a>Update script for step 1.0.0 => 2.0.0

**This you have to do before the extension update!**
Due to table field changes you have to convert the MySQL datetime field values to Unix timestamps:

1. Create new temporary table fields:
        <pre><code style="sql">
        ALTER TABLE tx_t3cssessions_domain_model_slot ADD begin_backup int(11) DEFAULT '0' NOT NULL;
        ALTER TABLE tx_t3cssessions_domain_model_slot ADD end_backup int(11) DEFAULT '0' NOT NULL;
        </code></pre>
1. Convert existing records from datetime to Unix timestamp:
        <pre><code style="sql">
        UPDATE tx_t3cssessions_domain_model_slot SET begin_backup = UNIX_TIMESTAMP(begin);
        UPDATE tx_t3cssessions_domain_model_slot SET end_backup = UNIX_TIMESTAMP(end);
        </code></pre>
1. Do the extension update (Fields "begin" and "end" will be changed from datetime to int(11))
1. Now save the timestamps back:
        <pre><code style="sql">
        UPDATE tx_t3cssessions_domain_model_slot SET begin = begin_backup;
        UPDATE tx_t3cssessions_domain_model_slot SET end = end_backup;
        </code></pre>
1. Due to the bug of the difference of 2 hours from database to frontend, add 2 hours:
        <pre><code style="sql">
        UPDATE tx_t3cssessions_domain_model_slot SET begin = begin + 7200;
        UPDATE tx_t3cssessions_domain_model_slot SET end = end + 7200;
        </code></pre>


## <a name="twitter"></a>Twitter integration

You have to create a Twitter App with the nice HowTo on http://www.pontikis.net/blog/auto_post_on_twitter_with_php.
After finishing the HowTo you just have to set your Twitter credentials into the extension configuration (in Extension Manager)
