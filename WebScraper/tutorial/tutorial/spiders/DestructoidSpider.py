from scrapy.spider import Spider
from scrapy.selector import Selector
from scrapy.http import Request

from tutorial.items import ReviewItem
from tutorial.DataHelper import DataHelper

from ReviewTextExtractor import Extractor


class DestructoidSpider(Spider):
    name = "destructoid"
    allowed_domains = ["destructoid.com"]
    start_urls = [
    	"http://www.destructoid.com/products_index.phtml?display=short&filt=reviews&date_s=desc&t=Games&category=&name_s=&t=Games&score_s=&alpha=&start=0"
    ]

    def parseReview(self, response):
    	sel = Selector(response)
    	item = ReviewItem()

    	item['sourceName'] = 'destructoid'
    	item['gameTitle'] = sel.xpath('//a[@class="product_name_subnav"]/text()').extract()[0]
        item['gameTitle'] = item['gameTitle'].replace('\t', ' ').replace('\n', ' ')
        item['gameTitle'] = ' '.join(item['gameTitle'].split())
    	item['url'] = response.url
    	item['content'] = sel.xpath('//div[@class="large_post_container"]/div/p').extract()
    	item['content'] = Extractor().extractInfoFromDestructoid(' '.join(item['content']))
        item['content'] = ' '.join(item['content'].split())
        item['content'] = item['content'].replace('\\\'', "'")

        extracontent = sel.xpath('//div[@class="large_post_container"]/div/div/p').extract()
        extracontent = Extractor().extractInfoFromDestructoid(' '.join(item['content']))
        extracontent = ' '.join(item['content'].split())
        extracontent = item['content'].replace('\\\'', "'")
        item['content'] += extracontent

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
				print "http://www.destructoid.com/" + link
				reviewRequest = Request("http://www.destructoid.com/" + link, callback=self.parseReview)
				yield reviewRequest

		#get the page with the next 25 review links
    	nextReviewSetUrl = "http://www.destructoid.com/" + sel.xpath('//a[text()[contains(., "NEXT")]]/@href').extract()[0]

        print nextReviewSetUrl
        #get the index that we are at (i.e. how many games the scraper has seen so far)
        #is the last item in list on split on =
        i = int(nextReviewSetUrl.split('=')[-1])
        if (i > 1000):
            print("Quiting")
            return
    	#if the index is above 1000, then stop
        #TODO: change this to get all reviews from destructoid

    	request = Request(nextReviewSetUrl, callback=self.parse)
    	yield request
