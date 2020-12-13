#!/bin/bash
rm -R pub/static/adminhtml
rm -R pub/static/frontend

bin/magento c:f

rm -R var/view_preprocessed/pub

> var/log/debug.log
> var/log/system.log

bin/magento setup:static-content:deploy en_GB -f
