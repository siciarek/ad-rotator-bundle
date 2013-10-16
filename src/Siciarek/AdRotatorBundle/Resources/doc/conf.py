# -*- coding: utf-8 -*-

import sys, os

# Project settings:
project = u'SiciarekAdRotatorBundle - docs'
author = u'Jacek Siciarek'
copyright = u'2013, Jacek Siciarek'
version = '1.0'
release = version

# Layout settings:
language = 'polish'
templates_path = ['_templates']
exclude_patterns = ['_build']
master_doc = 'index'
html_theme = 'nature'
html_sidebars = {'**': ['localtoc.html', 'relations.html']}
html_logo = 'images/siciarek.jpg'

# -- Options for LaTeX output --------------------------------------------------

latex_elements = {
# The paper size ('letterpaper' or 'a4paper').
'papersize': 'a4paper',

# The font size ('10pt', '11pt' or '12pt').
'pointsize': '12pt',

# Additional stuff for the LaTeX preamble.
#'preamble': '',

'babel': '\\usepackage[' + language + ']{babel}',
'classoptions': ',openany,oneside'
}

# Grouping the document tree into LaTeX files. List of tuples
# (source start file, target name, title, author, documentclass [howto/manual]).
latex_documents = [
  (master_doc, 'adrotator.tex', project, author, 'manual', False),
]

# The name of an image file (relative to this directory) to place at the top of
# the title page.
#latex_logo = 'images/siciarek.jpg'

# For "manual" documents, if this is true, then toplevel headings are parts,
# not chapters.
latex_use_parts = False

# If true, show page references after internal links.
latex_show_pagerefs = True

# If true, show URL addresses after external links.
#latex_show_urls = False

# Documents to append as an appendix to all manuals.
#latex_appendices = []

# If false, no module index is generated.
#latex_domain_indices = True
