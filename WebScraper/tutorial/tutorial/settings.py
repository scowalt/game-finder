# Scrapy settings for tutorial project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/en/latest/topics/settings.html
#

BOT_NAME = 'tutorial'

SPIDER_MODULES = ['tutorial.spiders']
NEWSPIDER_MODULE = 'tutorial.spiders'

SPIDER_MIDDLEWARES = {
	'scrapy.contrib.spidermiddleware.offsite.OffsiteMiddleware': 100
}

# ITEM_PIPELINES = {
# 	'tutorial.pipeline.IgnPipeline': 300,
# 	'tutorial.pipeline.MysqlPipeline': 1000
# }

# Crawl responsibly by identifying yourself (and your website) on the user-agent
#USER_AGENT = 'tutorial (+http://www.yourdomain.com)'
