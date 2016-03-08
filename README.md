JAPAN ADDRESSING
================

Current Status: dev-master (https://github.com/edgecreativemedia/japanaddressing)

This is currently underdevelopment.

A PHP 5.4+ address library for Japan’s address/postal system in both English & Japanese. It is powered by Japan Post’s csv dataset.

The library stores, sets and manipulates regions, prefectures and cities, as well as performing a lookup function for full Japanese addresses via it’s postal code (yubin-bango) in either language.

Features:

- Super fast address lookup via postal-code.
- Subdivision translations for all regions, prefectures and cities in Japan with kanji, romaji and English. 
- Translation for full Japanese addresses, but limited for special (JIGYOSYO) addresses in English.
- Easy PHP script for updating datasets, with checking current and available new CSV files from Japan Post’s website, as well as downloading the files required.

The dataset for postal-code addresses are stored locally in JSON format. To avoid loading over 50MB of data, the dataset is split in to 1000 JSON files per the first 3 digit postal code. This results in lookup functions being super fast and reducing server resources by only loading a maximum file size of 650KB. The dataset can be generated/updated via the locally stored script (/scripts/update-repository.php) by using Japan Post’s CSV files which are downloaded automatically when outdated.   

The datasets for subdivisions are stored locally in JSON format, from Google's [Address Data Service](https://i18napis.appspot.com/address) and also Japan Post’s CSV files (http://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip).


# Integrations

[Drupal 8 module] : https://www.drupal.org/project/japanaddress



