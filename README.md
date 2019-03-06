# Sudhaus7 Viewhelpers

This Extension provides a set of additional Viewhelpers and will add a hook to the RenderPostProcessHook facility of TYPO3. Additionally it will manage Metatags with the help of viewhelpers, automatically adding and overwriting Facebooks opengraph tags and others. 
It handles as well the generation of the cannonical URL. This makes this extension a mix of tools and seo optimizations 

### Usage
in your fluid template add this to the html-header:
xmlns:s7="http://typo3.org/ns/SUDHAUS7/Sudhaus7Viewhelpers/ViewHelpers"

or inline: 
{namespace s7=SUDHAUS7\Viewhelpers\ViewHelpers}

### Signal Slots

Classname: \SUDHAUS7\Sudhaus7Viewhelpers\Hooks\RenderPostProcessHook
All slots expect its parameters returned wrapped in an array
Slots:
- paramsAfterTitle - the whole page configuration after title generation. Parameters: $params
- metadataAfterImage - after guessing the image for example for og:image. Parameters: $params, $metaArray
- generateCannonical - chance to influence the cannonical URL. Parameter: $url
- newMetadata = array of the metadata to be set, wrapped in tags. Parameter: $newMeta
- finish = last chance to change $params. Parameter: $params

### Markers
the Marker ###CANONICALURL### will be replaced with the generated cannonical URL throughout the Document.


### Viewhelpers
Meta Viewhelper: <s7:meta key="" value="" auto="1"/>

for example: 
```html
<s7:meta key="title" value="my title" auto="1"/>
```
will create meta tags for the attribute title with the value of "my title". the auto feature will automatically create apporipriate tags for facebook, twitter and googleplus. valid keys for auto are:
title,type (see og:type), description,image,site,published,modified,section,keywords
other keys will be added literally as 
```html
<meta name="key" content="value"/>
```

more TODO
