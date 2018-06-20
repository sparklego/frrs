#!/usr/bin/python
# -*- coding: utf-8 -*-
import urllib, sys, json
from datetime import datetime
from SPARQLWrapper import SPARQLWrapper, JSON, XML, N3, RDF


pubname = sys.argv[1]
pubtype = sys.argv[2]


query_str = '''
SELECT COUNT(?pub) SAMPLE(?pub)
WHERE {
	?res rdf:type dbo:%s ;
	     dct:subject ?o .
	?pub dct:subject ?o .
	FILTER ( REGEX(?res, "%s") && (?pub != ?res) ) .
} 
GROUP BY ?pub
ORDER BY DESC(count(?pub))
LIMIT 15
''' % (pubtype, pubname)


# print(query_str);exit();

sparql = SPARQLWrapper("http://dbpedia.org/sparql")
sparql.setQuery(query_str)

sparql.setReturnFormat(JSON)
results = sparql.query().convert()

# print(results)
print(json.dumps(results))