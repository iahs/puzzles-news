Twitter Streaming API
========================

Stream Twitter feeds using PHP and OAuth.  We use the Twitters API v1.1 and a consumer developer key.

Overview
========================

- Tweets are cached to avoid exceeding Twitter’s limit of 150 requests for a user’s RSS and json feed per hour.
- A fallback is provided in case the twitter feed fails to load. this can be edited to suit your needs.
- A configuration parameter allows you to specify how many tweets are displayed
- Dates can optionally be displayed in “Twitter style”, e.g. "12 minutes ago"
- You can edit the HTML that wraps your tweets, tweet status and meta information

Parameters
========================

- Twitter handle.
- Cache file location.
- Tweets to display.
- Ignore replies.
- Include retweets.
- Twitter style dates. ("16 hours ago")
- Custom html.

Usage
========================

1. Update the consumer key, consumer secret, access token, and access token secret as appropriate.  
2. We make a call to our tweeter table to get twitter_ids, then we make a call on each twitter_id  
3. We cache the twitter feeds so that we can prevent reaching the 150 tweet/hr limit.

License
========================

The MIT License (MIT)

Copyright (c) 2013 Andrew Biggart

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
