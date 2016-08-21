
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
		{CONTENT}
	</body>
		{MEDIA_BEFORE}
	</html>");
$templater->loadFromFile("template.html"); // load template data from file
$templater->assets( ["LANG", "charset"], ["en", "UTF-8"] );
$templater->assets( "title", "Document title" );
$templater->assets( [
    "media_after" => null,
	"media_before" => null
], null, Templater::ASSOCIATIVE_ARRAY);
$templater->assets( "content", "<p>Hello world</p>" );

echo $templater->toString();

```