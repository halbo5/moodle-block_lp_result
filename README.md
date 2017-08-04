moodle-block_lp_result
============================

[![Build Status](https://travis-ci.org/halbo5/moodle-block_lp_result.svg?branch=master)](https://travis-ci.org/halbo5/moodle-block_lp_result)

Moodle plugin to display in a table all the results for a learning plan. Each student that is in a given learning plan model is listed in one table's line with all the competencies status.


Requirements
------------

This plugin requires Moodle 3.3+ (not tested on older version)


Motivation for this plugin
--------------------------

Moodle competencies are a great functionnality for evaluating students. But it lacks some feature. With this block you can have in one table all the students you have to evaluate.  You see in one line all the results for all competencies for each student. You can easily observe how the group evolves. You can download the table or display it in your browser.


Installation
------------

Install the plugin like any other plugin to folder
/blocks/lp_result

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins


Usage & Settings
----------------

### 1. Settings

There are no admin settings.

### 2. Usage

Once you created a learning plan model, note its ID.
Add your block "Learning Plan Result" and edit configuration.
You can change the block's title.
You have to add the learning plan model ID.


Plugin repositories
-------------------

This plugin has not yet been tested on production server. When it will be done, it will be published and regularly updated in the Moodle plugins repository.


The latest development version can be found on Github:
https://github.com/halbo5/moodle-block_lp_result


Bug and problem reports / Support requests
------------------------------------------

This plugin is carefully developed and thoroughly tested, but bugs and problems can always appear.

Please report bugs and problems on Github:
https://github.com/halbo5/moodle-block_lp_result/issues

We will do our best to solve your problems, but please note that due to limited resources we can't always provide per-case support.


Feature proposals
-----------------

Due to limited resources, the functionality of this plugin is primarily implemented for our own local needs and published as-is to the community. We are aware that members of the community will have other needs and would love to see them solved by this plugin.

Please issue feature proposals on Github:
https://github.com/halbo5/moodle-block_lp_result/issues

Please create pull requests on Github:
https://github.com/halbo5/moodle-block_lp_result/pulls

We are always interested to read about your feature proposals or even get a pull request from you, but please accept that we can handle your issues only as feature _proposals_ and not as feature _requests_.


Moodle release support
----------------------

Due to limited resources, this plugin is only maintained for the most recent major release of Moodle. However, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until we can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that this plugin still works with a new major relase - please let us know on Github.

If you are running a legacy version of Moodle, but want or need to run the latest version of this plugin, you can get the latest version of the plugin, remove the line starting with $plugin->requires from version.php and use this latest plugin version then on your legacy Moodle. However, please note that you will run this setup completely at your own risk. We can't support this approach in any way and there is a undeniable risk for erratic behavior.


Right-to-left support
---------------------

This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send us a pull request on Github with modifications.


Copyright
---------

Alain Bolli

