
### **Example**

```php
$templater = new Templater();
$templater->loadFromStr("<!DOCTYPE html>
    <html lang=\"{LANG}\">
        <head>
		<meta charset=\"{CHARSET}\">
		<title>{TITLE}</title>
		{MEDIA_AFTER}
	</head>
	<body>
		{{print_r(["one", "two", "three"], true)}}
		@{{var_dump(array("test", "huest"))}}
		{CONTENT}
	</body>
		{MEDIA_BEFORE}
	</html>");
$templater->loadFromFile("template.html"); 					// load template data from file
$templater->assign( ["LANG", "charset"], ["en", "UTF-8"] ); 			// or
$templater->assign( "title", "Document title" );				// or
$templater->assign( [
    "media_after" => null,
	"media_before" => null
], null, Templater::ASSOCIATIVE_ARRAY);						// or
$templater->assign( "content", "<p>Hello world</p>" );				// or

echo $templater->toString();

```
