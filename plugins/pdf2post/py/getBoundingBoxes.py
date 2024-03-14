#!/usr/bin/python2.4
# -*- coding: utf-8 -*-
# coding: utf8

from operator import itemgetter
import re
import sys
import xml.etree.cElementTree as ET
from pdfminer.layout import LAParams, LTTextBox
from pdfminer.pdfpage import PDFPage
from pdfminer.pdfinterp import PDFResourceManager
from pdfminer.pdfinterp import PDFPageInterpreter
from pdfminer.converter import PDFPageAggregator

def outputBoundingBoxes():    
    pdf_in = sys.argv[1]
    fp = open(pdf_in, 'rb')
    rsrcmgr = PDFResourceManager()
    laparams = LAParams()
    device = PDFPageAggregator(rsrcmgr, laparams=laparams)
    interpreter = PDFPageInterpreter(rsrcmgr, device)
    pages = PDFPage.get_pages(fp)
    
    for page in pages:
        print('Processing next page...')
        interpreter.process_page(page)
        layout = device.get_result()
        for lobj in layout:
            if isinstance(lobj, LTTextBox):
                x, y, text = lobj.bbox[0], lobj.bbox[3], lobj.get_text()
                print('At %r is text: %s' % ((x, y), text))

if __name__ == "__main__":
    outputBoundingBoxes()