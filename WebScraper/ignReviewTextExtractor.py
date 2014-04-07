

import sys, getopt



def extractInfo(fileName) :
	#read file into a string
	inputFile = open(fileName, "r")
	html = inputFile.read()

	#pass string into BeautifulSoup
	from bs4 import BeautifulSoup
	soup = BeautifulSoup(html)

	import re
	regex = re.compile("[0-9]* watching now")
	matches = regex.findall(soup.get_text())


	for link in soup.find_all('p') :
		text = link.get_text()

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
			print(text)


def main(argv):
	#code to get command line arguments
	inputfile = ''
	try:
		opts, args = getopt.getopt(argv, "hi:o", ["ifile="])
	except getopt.GetoptError:
		print("tut.py -i <inputfile>")
		sys.exit(2)
	for opt, arg in opts:
		if (opt in ("-i")):
			inputfile = arg
	if (inputfile == ""):
		print("Input file was null")
	else :
		#print("Input file is: " + inputfile)
		extractInfo(inputfile)

if __name__ == "__main__":
	main(sys.argv[1:])