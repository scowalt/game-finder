
import pymysql
import MySQLdb
import dbcredentials

import sys

class DataHelper:

	def connectToDB(self):
		conn = pymysql.connect(
			dbcredentials.HOST, 
			dbcredentials.USERNAME, 
			dbcredentials.PASSWORD, 
			dbcredentials.DATABASE,
			charset='utf8',
			use_unicode=True)
		return conn

	# Runs the sql command passed in as a string
	# returns the results of that query
	def runSQL(self, sql):
		conn = self.connectToDB()
		cursor = conn.cursor(pymysql.cursors.DictCursor)
		cursor.execute(sql)
		conn.commit() #needed to add this line to have the 'inserts' actually work
		cursor.close()
		conn.close()
		return cursor

	def insertGame(self, title):
		sql = "INSERT INTO Games (Title) VALUES ('{0}');".format(pymysql.escape_string(title))
		return self.runSQL(sql)
		
	def insertSource(self, sourceName):
		sql = "INSERT INTO Sources (Name) VALUES ('{0}');".format(pymysql.escape_string(sourceName))
		return self.runSQL(sql)

	def reviewIsAlreadyInDatabase(self, gameId, sourceId):
		sql = "SELECT * FROM Reviews WHERE game_id={0} AND source_id={1}".format(gameId, sourceId)
		result = self.runSQL(sql).fetchone()
		if result is None:
			return False
		else:
			return True


	def insertReviewAsItem(self, item):
		self.insertReview(item['gameTitle'], item['sourceName'], item['url'], item['content'])

	# Given a game title, source name, url to the review, and content
	# this function will insert that info into the databse
	# if it finds that the game that this review corresponds to is
	# not in the database, it will insert it into database, likewise
	# with sources
	def insertReview(self, gameTitle, sourceName, url, content):
		gameId = self.findGameIdFromTitle(gameTitle)
		if gameId is None:
			print("Couldn't find game id for %s, inserting game into database" % gameTitle)
			self.insertGame(gameTitle)
			gameId = self.findGameIdFromTitle(gameTitle)

		sourceId = self.findSourceIdFromTitle(sourceName)
		if (sourceId) is None:
			print("Couldn't find source id for %s, inserting source into database" % sourceName)
			self.insertSource(sourceName.encode('utf8'))
			sourceId = self.findSourceIdFromTitle(sourceName.encode('utf8'))


		#don't insert review if we already have a review for that game from that source
		if self.reviewIsAlreadyInDatabase(gameId, sourceId) is True:
			print("Found that the given gameId, sourceId pair already has a review in the databse")
			return

		values = (gameId, sourceId, pymysql.escape_string(url), pymysql.escape_string(content).encode('utf-8'))
		sql = "INSERT INTO Reviews (game_id, source_id, Url, Content) VALUES ({0}, {1}, '{2}', \"{3}\");".format(gameId, sourceId, url, pymysql.escape_string(content))
		return self.runSQL(sql)


	def findGameIdFromTitle(self, gameTitle):
		sql = "SELECT game_id FROM Games WHERE Title='{0}'".format(pymysql.escape_string(gameTitle))
		result = self.runSQL(sql).fetchone()
		if result is None:
			print("Couldn't find game id for '%s'" % gameTitle)
			return None
		else:
			return result["game_id"]

	def findSourceIdFromTitle(self, sourceName):
		sql = "SELECT source_id FROM Sources WHERE Name='{0}'".format(pymysql.escape_string(sourceName))
		result = self.runSQL(sql).fetchone()
		if result is None:
			print("Couldn't find source id for '%s'" % sourceName)
			return None
		else:
			return result["source_id"]

