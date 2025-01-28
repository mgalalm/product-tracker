#!/usr/bin/env python
print('If you get error "ImportError: No module named \'six\'" install six:\n'+\
    '$ sudo pip install six');
print('To enable your free eval account and get CUSTOMER, YOURZONE and ' + \
    'YOURPASS, please contact sales@brightdata.com')
import sys
import ssl
ssl._create_default_https_context = ssl._create_unverified_context
if sys.version_info[0]==2:
    import six
    from six.moves.urllib import request
    opener = request.build_opener(
        request.ProxyHandler(
            {'http': 'http://brd-customer-hl_d1766d4b-zone-scraping_browser1:56r1f3gvppw6@brd.superproxy.io:33335',
            'https': 'http://brd-customer-hl_d1766d4b-zone-scraping_browser1:56r1f3gvppw6@brd.superproxy.io:33335'}))
   
if sys.version_info[0]==3:
    import urllib.request
    opener = urllib.request.build_opener(
        urllib.request.ProxyHandler(
            {'http': 'http://brd-customer-hl_d1766d4b-zone-scraping_browser1:56r1f3gvppw6@brd.superproxy.io:33335',
            'https': 'http://brd-customer-hl_d1766d4b-zone-scraping_browser1:56r1f3gvppw6@brd.superproxy.io:33335'}))
  # save to html 
with open('output.html', 'w') as f:
    f.write(opener.open('https://www.amazon.co.uk/dp/B0CJL4J6FG').read().decode('utf-8'))
  # print(opener.open('https://www.amazon.co.uk/dp/B0CJL4J6FG').read())