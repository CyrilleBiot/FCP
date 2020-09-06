#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
pandas_csv.py
Utilitaire d'aide à la direction. Realisation fiche de continuité peda bepuis fichier csv ONDE
Recodé en GTK

__author__ = "Cyrille BIOT <cyrille@cbiot.fr>"
__copyright__ = "Copyleft"
__credits__ = "Cyrille BIOT <cyrille@cbiot.fr>"
__license__ = "GPL"
__version__ = "0.6"
__date__ = "2020/06/06"
__maintainer__ = "Cyrille BIOT <cyrille@cbiot.fr>"
__email__ = "cyrille@cbiot.fr"
__status__ = "Devel"
"""


import sys
import pandas
import pdfkit

# Recuperation du répertoire de travail, passé en paramètre du script
tmpDir = sys.argv[1]

# Lecture csv et réalisation d'un DataFrame
data = pandas.read_csv(tmpDir+'/CSVExtraction.csv',encoding='ISO-8859-14', delimiter=';', skipfooter=1, engine="python")
df = pandas.DataFrame(data, columns=['Prénom élève', 'Nom de famille élève','Civilité Responsable', 'Nom responsable', 'Prénom responsable','Téléphone domicile', 'Téléphone travail', 'Téléphone travail', 'Courriel'])

# Tri du DataFrame
df = df.sort_values(by=['Prénom élève'])

# Export du DataFrame en HTML
f = open(tmpDir+'/output.html','w',encoding='ISO-8859-14' )
a = df.to_html()
f.write(a)
f.close()

# Export du doc temp html en PDF
pdfkit.from_file(tmpDir+'/output.html', tmpDir+'/output.pdf')