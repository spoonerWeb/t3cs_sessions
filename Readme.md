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

* **BREAKING CHANGE**: Changes table fields for time slots. Please be aware and read the Update script.
* Twitter notification integration. Create a cron job to send reminder for upcoming sessions sent by your Twitter account.


## Update script for step 1.0.0 => 2.0.0

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

```sql  UPDATE tx_t3cssessions_domain_model_slot SET begin = begin + 7200;
        UPDATE tx_t3cssessions_domain_model_slot SET end = end + 7200; ```
