#!/usr/bin/python
# -*- coding: utf-8 -*-
import urllib, sys, json
from datetime import datetime
from SPARQLWrapper import SPARQLWrapper, JSON, XML, N3, RDF


pubname = sys.argv[1]


query_str = '''
SELECT COUNT(?movie) SAMPLE(?movie)
WHERE {
	dbr:%s dct:subject ?o .
	?movie dct:subject ?o .
	FILTER (?movie != dbr:%s) .
} 
GROUP BY ?movie
ORDER BY DESC(count(?movie))
LIMIT 10
''' % (pubname, pubname)


# print(query_str);exit();

sparql = SPARQLWrapper("http://dbpedia.org/sparql")
sparql.setQuery(query_str)

sparql.setReturnFormat(JSON)
results = sparql.query().convert()

print(json.dumps(results))