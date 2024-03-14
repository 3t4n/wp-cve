#!/usr/bin/python2.4
# -*- coding: utf-8 -*-
# coding: utf8

from operator import itemgetter
import re
import sys
import xml.etree.cElementTree as ET

def parseXML():
    '''
    parse the document XML
    '''
    xml_in = sys.argv[1]
    #print (xml_in)
    # if two fragments of text are within LINE_TOLERANCE of each other they're on the same line
    LINE_TOLERANCE = 4

    # get the page elements
    tree = ET.ElementTree(file=xml_in)
    pages = tree.getroot()

    if pages.tag != "pages":
        sys.exit("ERROR: pages.tag is %s instead of pages!" % pages.tag)

    # step through the pages
    for page in pages:
        
        
        textboxes = page.findall("./textbox")
        
        for textlines in textboxes:
            # get all the textline elements
            #textlines = page.findall("./textbox/textline")
            #print "found %s textlines" % len(textlines)
    
            # step through the textlines
            ext_paragraphs = []
            all_text_lines = ''
            for textline in textlines:
                # get the boundaries of the textline
                line_bounds = [float(s) for s in textline.attrib["bbox"].split(',')]
                #print "line_bounds: %s" % line_bounds
    
                # get all the texts in this textline
                chars = list(textline)
                #print "found %s characters in this line." % len(chars)
    
                # combine all the characters into a single string
                line_text = ""
                for char in chars:
                    line_text = line_text + char.text
    
                # strip edge & multiple spaces
                line_text = re.sub(' +', ' ', line_text.strip())
    
                # save a description of the line
                #line = {'left': line_bounds[0], 'top': line_bounds[1], 'text': line_text}
                #lines.append(line)
                all_text_lines = all_text_lines + ' '+line_text
                
            
            allLines= {'left': line_bounds[0], 'top': line_bounds[1], 'text': all_text_lines}
            ext_paragraphs.append(allLines)
            #print "page %s has %s lines" % (page.attrib["id"], len(lines))
    
            # sort the lines by left, then top position
            ext_paragraphs.sort(key=itemgetter('left'))
            ext_paragraphs.sort(key=itemgetter('top'), reverse=True)
            consolidate = False
            if consolidate:
                # consolidate lines that have the same top (within tolerance)
                consolidated_paragraph = []
                line_segments = []
                line_top = ext_paragraphs[0]['top']
                for ext_paragraph in ext_paragraphs:
                    if abs(ext_paragraphs['top'] - line_top) < LINE_TOLERANCE:
                        line_segments.append(ext_paragraph)
        
                    else:
                        # assure that text segments appear in the correct order
                        line_segments.sort(key=itemgetter('left'))
                        # create a new line object combining partial texts, preserving the left-most text position
                        merged_line = dict(line_segments[0])
                        merged_line['text'] = ""
                        for item in line_segments:
                            merged_line['text'] = merged_line['text'] + " " + item['text']
        
                        consolidated_paragraph.append(merged_line)
        
                        # reset
                        line_segments = [ext_paragraph]
                        line_top = line['top']
        
                for ext_paragraph in consolidated_paragraph:
                    print(ext_paragraph['text'].encode('utf-8'))
            else:
                for ext_paragraph in ext_paragraphs:
                    print(ext_paragraph['text'].encode('utf-8'))

if __name__ == "__main__":
    parseXML()