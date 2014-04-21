from scrapy.spider import Spider
from scrapy.selector import Selector
from scrapy.http import Request

from tutorial.items import IgnItem
from tutorial.DataHelper import DataHelper

import ignReviewTextExtractor


class IgnSpider(Spider):
    name = "ign"
    allowed_domains = ["ign.com"]
    start_urls = [
    	"http://www.ign.com/games/reviews"
        # "http://www.ign.com/articles/2014/03/10/final-fantasy-x-x-2-hd-remaster-review", 
        # "http://www.ign.com/articles/2014/05/01/republique-episode-2-metamorphosis-review"
    ]

    def parseReview(self, response):
    	sel = Selector(response)
    	item = IgnItem()

    	item['sourceName'] = 'ign'
    	item['gameTitle'] = sel.xpath('//a[@class="autolink"]/@title').extract()[0]
    	item['url'] = response.url #sel.xpath('//link[@rel="canonical"]/@href').extract()[0]
    	item['content'] = ignReviewTextExtractor.Extractor().extractInfo(response.body)
    	item['content'] = ' '.join(item['content'].split())

    	helper = DataHelper()
    	print(item)
    	helper.insertReviewAsItem(item)
    	return item

    def parse(self, response):
    	sel = Selector(response)
    	results = sel.xpath('//a[text() = "Review"][1]/@href').extract()
    	links = []
    	#get all links to reviews and parse each review
    	for link in results:
			if link not in links:
				links.append(link)
				print link
				reviewRequest = Request(link, callback=self.parseReview)
				yield reviewRequest

		#get the page with the next 25 review links
    	index = sel.xpath('//a[@id="is-more-reviews"]/@data-start').extract()[0]
    	newindex = int(index) + 25
    	maxIndex = 17500
    	if (newindex > 1000):
    		return

    	newurl = "http://www.ign.com/games/reviews?startIndex=" + str(newindex)
    	print newurl
    	request = Request(newurl, callback=self.parse)
    	yield request
