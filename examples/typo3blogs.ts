# This example lists the latest items of some
# German Blogs, filered if the posts are related
# to TYPO3.


plugin.tx_yql_pi1{
  select{
    fields = description, title, link, pubDate
    table = rss
    where = ((url="http://blog.marit.ag/feed/")OR(url="http://www.fi-ausbilden.de/blog/feed/")OR(url="http://www.sgtypo3.de/blog//feed/beitraege/2.0/rss.xml")OR(url="http://typo3felix.de/blog/feed")OR(url="http://typo3blogger.de/feed")OR(url="http://t3n.de/news/feed/")OR(url="http://www.typo3-blog.com/feed/"))AND((title LIKE "%TYPO%")OR(category LIKE "%TYPO%")OR(title LIKE "%FLOW3%")OR(category LIKE "%FLOW3%"))
    functions = | sort(field="pubDate") | reverse()
  }
  renderObj{
  
    10 = TEXT
    10.field = pubDate
    10.wrap = <div class="date">|</div>
    
    20 = TEXT
    20.field = title
    20.typolink.parameter.field = link
    20.wrap = <div class="title">|</div>
    
    30 = TEXT
    30.field = description
    30.crop = 140 | ... | 1
    30.wrap = <div class="description">|</div>
    
    wrap = <li>|</li>
  }
  limit = 20
  stdWrap.wrap = <ul>|</ul>
}