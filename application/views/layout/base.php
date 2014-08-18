<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>{{ title }}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="{{ viewport|default('width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, target-densitydpi=device-dpi') }}">
	<link type="text/css" href="{{ static_url }}modules/bootstrap/bootstrap.min.css" rel="stylesheet" media="screen" />
	<link type="text/css" href="{{ static_url }}modules/bootstrap/bootstrap-theme.min.css" rel="stylesheet" media="screen" />
	{% for css in css_files %}
	<link type="text/css" href="{{ css.url }}" rel="stylesheet" media="{{ css.media|default('screen') }}" />
	{% endfor %}
	<script type="text/javascript">
	{% block js_settings %}
	{% endblock %}
	</script>
	{% for js in js_files %}
	<script type="text/javascript" src="{{ js.url }}" charset="{{ js.charset|default('utf-8') }}"></script>
	{% endfor %}
</head>
<body>
	<div class="{{ page_class|join(' ') }}" id="root">
		{% block content %}
		{% endblock %}
	</div>
</body>
</html>
