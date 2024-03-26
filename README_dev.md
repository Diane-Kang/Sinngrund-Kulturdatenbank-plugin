# Absicht

Map Applikation für https://kulturdatenbank-sinngrund.de/

Unterprojekte SinngrundAllianz

# Übersicht

## Notwendig Daten für Markers

### Post /Beitrag **Kategorie**

**Muss** einen von folgendes gewählt werden

- Kulturelle Sehenswürdigkeiten
- Brauchtum und Veranstaltungen
- Gemeinden
- Point of Interest
- Sagen + Legenden
- Sprache und Dialekt
- Thementouren

---

Alle Map, Filter, Marker logic beginnt hier

@index.php: `private $category_shortname_array`

@main_page.php and @single_post.php:

`$sinngrundKulturdatenbank->get_category_shortname_array();`

Post wird beim funktion `post_valid_check` checkt

---

### Post /Beitrag **Standort: geografische Daten**
