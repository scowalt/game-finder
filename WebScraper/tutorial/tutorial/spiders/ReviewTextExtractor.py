

import sys, getopt
import codecs
from bs4 import BeautifulSoup

class Extractor:
	def extractInfoFromIgn(self, text) :
		#pass string into BeautifulSoup
		reload(sys)
		sys.setdefaultencoding('UTF8')
		soup = BeautifulSoup(text)

		import re
		regex = re.compile("[0-9]* watching now")
		matches = regex.findall(soup.get_text())

		reviewText = []

		for link in soup.find_all('p') :
			text = link.get_text().encode('utf-8', 'replace')

			#ign has a sidebar with trending videos that tells you how many viewers
			#are watching each trending video, so we want to ignore that text too
			isNotPartOfTrendingSidebar = (len(regex.findall(text)) == 0)

			#ign also has a sidebar that has a wiki that is related to the game the review
			#belongs to. These text elements are short titles (usually no more than 4 words), and
			#most of the text content that is actually part of the review text is inside a 
			#<p> element with more than 4 words, so we can parse out the sidebar stuff by
			#ignoring elements with less than 4 words
			isNotPartOfGameWikiSidebar = (len(text.split(" ")) > 4)


			isNotCommentText = "You must be logged in to post a comment" not in text

			isNotCopyrightText = "IGN Entertainment, Inc." not in text

			isReviewText = isNotPartOfTrendingSidebar and isNotPartOfGameWikiSidebar and isNotCommentText and isNotCopyrightText
			
			if (isReviewText) :
				reviewText.append(text)

		return ' '.join(reviewText)

	def extractInfoFromDestructoid(self, text):
		reload(sys)
		sys.setdefaultencoding('UTF8')
		soup = BeautifulSoup(text)
		reviewText = []

		for link in soup.find_all('p') :
			text = link.get_text().encode('utf-8', 'replace')
			reviewText.append(text)


		return ' '.join(reviewText)